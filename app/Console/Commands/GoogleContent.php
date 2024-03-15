<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminSetting;
use App\Models\Category;
use App\Models\Pricingnew;
use App\Models\Product;
use App\Models\ProductOption;
use Google\Service\ShoppingContent;
use Google\Service\ShoppingContent\Product as ServiceProduct;
use Google\Service\ShoppingContent\Price;
use Google_Client;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Intervention\Image\Facades\Image;

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
    protected $description = 'Command description';

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
        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob'); // Use 'urn:ietf:wg:oauth:2.0:oob' for command-line authentication
        $client->setScopes([
            'openid',
            'profile',
            'email',
            'https://www.googleapis.com/auth/content'
        ]);

        $authUrl = $client->createAuthUrl();
        
        // Prompt the user to visit the URL for authentication
        $this->line('Please visit the following URL to authenticate:');
        $this->line($authUrl);
        
        // Wait for the user to enter the authorization code
        $code = $this->ask('Enter the authorization code:');
        
        // Fetch access token using the authorization code
        $token = $client->fetchAccessTokenWithAuthCode($code);
        
        // Handle the authentication result
        if (isset($token['error'])) {
            $this->error('Authentication failed: ' . $token['error_description']);
            return;
        }
        
        // If authentication succeeded, proceed with syncing products
        $result = $this->insertProducts($token, $client);
        
        // Handle the result
        if ($result) {
            $this->info('Products inserted successfully.');
        } else {
            $this->error('Failed to insert products.');
        }
    }

    public function insertProducts($token, $client)
    {
        $price_column = null;
        $default_price_column = AdminSetting::where('option_name', 'default_price_column')->first();
        if (!empty($default_price_column)) {
            $price_column = $default_price_column->option_value;
        } else {
            $price_column = 'retailUSD';
        }
        $product_array = [];

        $product_categories = Category::where('is_active', 1)->pluck('category_id')->toArray();
        $all_products_ids = Product::whereIn('category_id', $product_categories)
            ->pluck('product_id')->toArray();
        $product_options_ids = ProductOption::whereIn('product_id', $all_products_ids)
            ->where('status', '!=', 'Disabled')
            ->where('optionWeight', '>', 0)
            ->pluck('option_id')->toArray();
        $product_pricing_option_ids = Pricingnew::whereIn('option_id', $product_options_ids)
            ->where($price_column, '>', 0)
            ->pluck('option_id')
            ->toArray();
        $products_ids = ProductOption::whereIn('option_id', $product_pricing_option_ids)
            ->pluck('product_id')->toArray();
        $products = Product::with('options', 'options.defaultPrice', 'product_brand', 'product_image', 'categories')->whereIn('product_id', $products_ids)
            ->where('status', '!=', 'Inactive')
            ->where('barcode', '!=', '')
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
                        } else {
                            $category = 'General > General';
                        }

                        $product_array[] = [
                            'id' => $product->id,
                            'title' => $product->name,
                            'code' => $product->code,
                            'description' => !empty($product->description) ? strip_tags($product->description) : 'No description available',
                            'link' => url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug),
                            'image_link' => !empty($product->product_image->image) ? url(asset('theme/products/images/' . $product->product_image->image)) : url(asset('theme/img/image_not_available.png')),
                            'price' => !empty($option->price[0]->$price_column) ? $option->price[0]->$price_column : 0,
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

        $client->setAccessToken($token['access_token']); // Use the stored access token

        $service = new ShoppingContent($client);
        $result = null;

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
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to retrieve products from Google Merchant Center.'
                ]);
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
                    // Handle the case when the product is duplicate
                    // You can skip insertion or update existing product
                }
            }
            return $result;
        } else {
            return $result;
        }
    }



    
}
