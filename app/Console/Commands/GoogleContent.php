<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminSetting;
use App\Models\Category;
use App\Models\Pricingnew;
use App\Models\Product;
use App\Models\ProductOption;
use Google\Client as GoogleClient;
use Google\Service\ShoppingContent;
use Google\Service\ShoppingContent\Product as ServiceProduct;
use Google\Service\ShoppingContent\Price;

class GoogleContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:gmc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync products with Google Merchant Center';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Initialize Google Client
        $client = new GoogleClient();
        $client->setAuthConfig('master_credentials.json');
        $client->setScopes([
            'openid',
            'profile',
            'email',
            'https://www.googleapis.com/auth/content', // Add other necessary scopes
        ]);

        // Automatically authorize using domain-wide delegation
        // $client->setSubject('indoorsunhydro@indoorsunhydro.iam.gserviceaccount.com');
        $token = $client->fetchAccessTokenWithAssertion();
        // Check if access token is retrieved successfully
        if (isset($token['access_token'])) {
            // Insert products to Google Merchant Center
            $deletePriceZeroProducts = $this->removeZeroPriceProducts($client, $token);
            $responseRemoved = $this->removeDisapprovedProducts($client, $token);
            $result = $this->insertProducts($client, $token);

            if ($result) {
                $this->info('Products inserted successfully.');
            } else {
                $this->error('Failed to insert products.');
            }

            $responseDeleted = $this->delete_inactive_products($client, $token);
            $responseRemoved = $this->removeDisapprovedProducts($client, $token);
        } else {
            $this->error('Failed to retrieve access token.');
        }
    }

    /**
     * Insert products to Google Merchant Center.
     *
     * @param GoogleClient $client
     * @param array $token
     * @return mixed
     */
    private function insertProducts($client , $token)
    {
        
        $price_column = null;
        $default_price_column = AdminSetting::where('option_name', 'default_price_column')->first();
        if (!empty($default_price_column)) {
            $price_column = $default_price_column->option_value;
        }
        else {
            $price_column = 'retailUSD';
        }
        $product_array = [];
        $product_categories = Category::where('is_active', 1)->pluck('category_id')->toArray();
        $all_products_ids = Product::whereIn('category_id' , $product_categories)
        ->pluck('product_id')->toArray();
        $product_options_ids = ProductOption::whereIn('product_id' , $all_products_ids)
        ->where('status', '!=', 'Disabled')
        ->where('optionWeight', '>', 0)
        ->pluck('option_id')->toArray();
        $product_pricing_option_ids = Pricingnew::whereIn('option_id' , $product_options_ids)
        ->where($price_column , '!=', null)
        ->where($price_column , '>' , 0)
        ->pluck('option_id')
        ->toArray();
        $products_ids = ProductOption::whereIn('option_id' , $product_pricing_option_ids)
        ->pluck('product_id')->toArray();
        $products = Product::with('options','options.defaultPrice','product_brand','product_image','categories')->whereIn('product_id' , $products_ids)
        ->where('status' , '!=' , 'Inactive')
        ->where('barcode' , '!=' , '')
        ->get();
        if (count($products) > 0) {
            foreach ($products as $product) {
                
                if (count($product->options) > 0) {
                    foreach ($product->options as $option) {
                        $category = 'General > General';
                        if (!empty($product->categories)) {
                            if (!empty($product->categories->category_id) && $product->categories->parent_id == 0) {
                                $category = $product->categories->category_id;
                            } else if (!empty($product->categories->parent_id) && !empty($product->categories->category_id) && $product->categories->parent_id != 0) {
                                $category = $product->categories->parent_id;
                            }
                        }
                        else {
                            $category = 'General > General';
                        }
                        
                        $product_array[] = [
                            'id' => $product->id,
                            'title' => $product->name,
                            'code' => $product->code,
                            'description' => !empty($product->description) ? strip_tags($product->description) : 'No description available',
                            'link' => url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug),
                            'image_link' => !empty($product->product_image->image) ? url(asset('theme/products/images/' . $product->product_image->image)) : url(asset('theme/img/image_not_available.png')),
                            'price' => !empty($option->price[0]->$price_column) && $option->price[0]->$price_column > 0  ? $option->price[0]->$price_column : 0,
                            'condition' => 'new',
                            'availability' => !empty($option) && $option->stockAvailable > 0 ? 'In stock' : 'Out of stock',
                            'brand' => !empty($product->product_brand->name) ? $product->product_brand->name : 'General brand',
                            'barcode' => $product->barcode,
                            'google_product_category' => $category,
                            'product_weight' => $option->optionWeight,
                        ];
                    }
                }
            }
        }
        
        // $chunks = array_chunk($product_array, 100);
        $client->setAccessToken($token['access_token']); // Use the stored access token

        $service = new ShoppingContent($client);
        $result  = null;

        $productStatusList = [];
        $feedIds = [];
        $pageToken = null;
        do {
            try {
                $products = $service->products->listProducts(config('services.google.merchant_center_id'), ['maxResults' => 250, 'pageToken' => $pageToken]);
                foreach ($products->getResources() as $product) {
                    $productId = $product['id'];
                    $mpn = $product['mpn'];
                    $productStatusList[] = [
                        'id' => $productId,
                        'mpn' => $mpn,
                    ];
                }
                $pageToken = $products->getNextPageToken();
            } catch (\Google\Service\Exception $e) {
                report($e);
                return $this->error('Failed to retrieve products from Google Merchant Center.');
            }
        } while (!empty($pageToken));
        if (!empty($product_array)) {
            foreach ($product_array as $index => $add_product) {
                $isDuplicate = false;

                foreach ($productStatusList as $existingProduct) {
                    if ($add_product['code'] === $existingProduct['mpn']) {
                        $isDuplicate = true;
                        break;
                    }
                }
                if (!$isDuplicate) {
                    $product = new ServiceProduct();
                    $product->setOfferId(substr($add_product['code'], 0, 50));
                    $product->setTitle($add_product['title']);
                    $product->setDescription($add_product['description']);
                    $product->setLink($add_product['link']);
                    $product->setImageLink($add_product['image_link']);
                    $product->setContentLanguage('en');
                    $product->setTargetCountry('US');
                    $product->setChannel('online');
                    $product->setAvailability($add_product['availability']);
                    $product->setCondition($add_product['condition']);
                    $product->setBrand($add_product['brand']);
                    // $product->setGoogleProductCategory($add_product['google_product_category']);
                    $product->setGtin($add_product['barcode']);
                    // $product->setmultipack('5000');
                    // $product->setIdentifierExists(false);
                    $product->setMpn($add_product['code']);
                    $product->setAgeGroup('adult');
                    // $product->setColor('universal');
                    $product->setGender('unisex');
                    // $product->setSizes(['Large']);

                    $shippingWeight = new \Google\Service\ShoppingContent\ProductShippingWeight();
                    $shippingWeight->setValue($add_product['product_weight']);
                    $shippingWeight->setUnit('lb');
                    $product->setShippingWeight($shippingWeight);

                    $price = new Price();
                    $price->setValue($add_product['price']);
                    $price->setCurrency('USD');
            
                    $product->setPrice($price);
                    $merchant_id = config('services.google.merchant_center_id');
                    $result = $service->products->insert($merchant_id, $product);
                } else {
                    $product = new ServiceProduct();
                    $product->setOfferId(substr($add_product['code'], 0, 50));
                    $product->setTitle($add_product['title']);
                    $product->setDescription($add_product['description']);
                    $product->setLink($add_product['link']);
                    $product->setImageLink($add_product['image_link']);
                    $product->setContentLanguage('en');
                    $product->setTargetCountry('US');
                    $product->setChannel('online');
                    $product->setAvailability($add_product['availability']);
                    $product->setCondition($add_product['condition']);
                    $product->setBrand($add_product['brand']);
                    // $product->setGoogleProductCategory($add_product['google_product_category']);
                    $product->setGtin($add_product['barcode']);
                    // $product->setmultipack('5000');
                    // $product->setIdentifierExists(false);
                    $product->setMpn($add_product['code']);
                    $product->setAgeGroup('adult');
                    // $product->setColor('universal');
                    $product->setGender('unisex');
                    // $product->setSizes(['Large']);

                    $shippingWeight = new \Google\Service\ShoppingContent\ProductShippingWeight();
                    $shippingWeight->setValue($add_product['product_weight']);
                    $shippingWeight->setUnit('lb');
                    $product->setShippingWeight($shippingWeight);

                    $price = new Price();
                    $price->setValue($add_product['price']);
                    $price->setCurrency('USD');
            
                    $product->setPrice($price);
                    $merchant_id = config('services.google.merchant_center_id');
                    $result = $service->products->insert($merchant_id, $product);
                }

            }
            $this->info('Products inserted successfully.');
            return $result;              
        } else {
            $this->error('No products to insert.');
            return false;
        }
    }

    private function delete_inactive_products($client, $token) {
        $client->setAccessToken($token['access_token']); // Use the stored access token
    
        $service = new ShoppingContent($client);
    
        $pageToken = null;
        do {
            // Fetch products from GMC with pagination
            $productsGMC = $service->products->listProducts(config('services.google.merchant_center_id'), [
                'maxResults' => 250,
                'pageToken' => $pageToken,
            ]);
    
            foreach ($productsGMC->getResources() as $productGMC) {
                $productIdGMC = $productGMC['id'];
                $mpnGMC = $productGMC['mpn'];
    
                // Check if the product in GMC exists in your database and is inactive
                $inactiveProduct = Product::where('code', $mpnGMC)->where('status', 'Inactive')->first();
    
                // If the product exists in your database and is inactive, delete it from GMC
                if ($inactiveProduct) {
                    try {
                        $service->products->delete(config('services.google.merchant_center_id'), $productIdGMC);
                        $this->info('Product with MPN ' . $mpnGMC . ' deleted from Google Merchant Center.');
                    } catch (\Google\Service\Exception $e) {
                        report($e);
                        // $this->error('inactive'.' '. $e);
                        $this->error('Failed to delete product with MPN ' . $mpnGMC . ' from Google Merchant Center.');
                    }
                }
            }
    
            // Get the next page token for pagination
            $pageToken = $productsGMC->getNextPageToken();
        } while (!empty($pageToken));
    }
    private function removeDisapprovedProducts($client, $token)
    {
        $client->setAccessToken($token['access_token']); // Use the stored access token

        $service = new ShoppingContent($client);

        $productStatusList = [];
        $pageToken = null;
        do {
            try {
                $productStatuses = $service->productstatuses->listProductstatuses(config('services.google.merchant_center_id'), [
                    'maxResults' => 250,
                    'pageToken' => $pageToken
                ]);

                foreach ($productStatuses->getResources() as $productStatus) {
                    if (!empty($productStatus) && (!empty($productStatus->getItemLevelIssues()))) {
                        foreach ($productStatus->getItemLevelIssues() as $issue) {
                            if ($issue->getServability() === 'disapproved') {
                                $productId = $productStatus->getProductId();
                                $productStatusList[] = $productId;
                                try {
                                    $service->products->delete(config('services.google.merchant_center_id'), $productId);
                                    $this->info('Product with ID ' . $productId . ' deleted from Google Merchant Center.');
                                } catch (\Google\Service\Exception $e) {
                                    report($e);
                                    // $this->error('disapproved'.' '. $e);
                                    $this->error('Failed to delete product with ID ' . $productId . ' from Google Merchant Center.');
                                }
                            }
                        }
                    }
                }

                $pageToken = $productStatuses->getNextPageToken();
            } catch (\Google\Service\Exception $e) {
                report($e);
                return $this->error('Failed to retrieve product statuses from Google Merchant Center.');
            }
        } while (!empty($pageToken));
    }

    private function removeZeroPriceProducts($client, $token)
    {
        $client->setAccessToken($token['access_token']); // Use the stored access token

        $service = new ShoppingContent($client);

        $productPriceList = [];
        $pageToken = null;
        do {
            try {
                $productPrices = $service->products->listProducts(config('services.google.merchant_center_id'), [
                    'maxResults' => 250,
                    'pageToken' => $pageToken
                ]);

                foreach ($productPrices->getResources() as $productPrice) {
                    if (!empty($productPrice) && (!empty($productPrice->getPrice()))) {
                        $price_value = $productPrice->getPrice()->getValue();
                        if (floatval($price_value) == 0) {
                            $productId = $productPrice['id'];
                            $productPriceList[] = $productId;
                            try {
                                $service->products->delete(config('services.google.merchant_center_id'), $productId);
                                $this->info('Product with ID ' . $productId . ' deleted from Google Merchant Center.');
                            } catch (\Google\Service\Exception $e) {
                                report($e);
                                // $this->error('disapproved'.' '. $e);
                                $this->error('Failed to delete product with ID ' . $productId . ' from Google Merchant Center.');
                            }
                        }
                    }
                }

                $pageToken = $productPrices->getNextPageToken();
            } catch (\Google\Service\Exception $e) {
                report($e);
                return $this->error('Failed to retrieve product statuses from Google Merchant Center.');
            }
        } while (!empty($pageToken));
    }


}
