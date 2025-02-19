<?php
namespace App\Http\Controllers;

use App\Models\AdminSetting;
use App\Models\Category;
use App\Models\Pricingnew;
use App\Models\Product;
use App\Models\ProductOption;
use Google\Service\ShoppingContent;
use Google\Service\ShoppingContent\Product as ServiceProduct;
use Google\Service\ShoppingContent\PriceInsights;
use Google\Service\ShoppingContent\Price;
use Google_Client;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Intervention\Image\Facades\Image;

class GoogleContentController extends Controller
{
    

    function __construct()
    {
        $this->middleware(['role:Admin']);
    }
    public function authorizeGoogle()
    {
        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->setScopes(['https://www.googleapis.com/auth/content']);

        return redirect($client->createAuthUrl());
    }

    public function handleCallback(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->setScopes([
            'openid',
            'profile',
            'email',
            'https://www.googleapis.com/auth/content', // Add other necessary scopes
        ]);
        $code = $request->input('code');
        $token = $client->fetchAccessTokenWithAuthCode($code);
        // $result  = $this->insertProducts($token , $client);
        $result  = $this->retriveProducts($token , $client);
        if ($result->getStatusCode() == 200) {
            return redirect()->route('admin.view')->with('success', 'Products inserted successfully');
        } else {
            return redirect()->route('admin.view')->with('error', 'Something went wrong');
        }
    }

    public function insertProducts($token , $client)
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
        // $products = Product::with('options','options.defaultPrice', 'product_brand','product_image','categories' , 'product_views','apiorderItem', 'product_stock')
        // ->with(['product_views','apiorderItem' , 'options' => function ($q) {
        //     $q->where('status', '!=', 'Disabled')
        //     ->where('optionWeight', '>', 0);
            
        // }])
        // ->whereHas('options.defaultPrice', function ($q) use ($price_column) {
        //     $q->where($price_column, '>', 0);
        // })
        // ->whereHas('categories' , function ($q) {
        //     $q->where('is_active', 1);
        // })
        // ->where('status' , '!=' , 'Inactive')
        // ->where('barcode' , '!=' , '')
        // ->get();
        $product_categories = Category::where('is_active', 1)->pluck('category_id')->toArray();
        $all_products_ids = Product::whereIn('category_id' , $product_categories)
        ->pluck('product_id')->toArray();
        $product_options_ids = ProductOption::whereIn('product_id' , $all_products_ids)
        ->where('status', '!=', 'Disabled')
        ->where('optionWeight', '>', 0)
        ->pluck('option_id')->toArray();
        $product_pricing_option_ids = Pricingnew::whereIn('option_id' , $product_options_ids)
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
                    // if (!empty($product->images)) {
                    //     $response  = Http::get($product->images);
                    //     if ($response->getStatusCode() == 200) {
                    //         $image = $product->images;
                    //     } else {
                    //         $image = url(asset('theme/img/image_not_available.png'));
                    //     }
                    // }  else {
                    //     $image = url(asset('theme/img/image_not_available.png'));
                    // }
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
            return response()->json([
                'status' => 'success',
                'message' => 'Products inserted successfully'
            ]);              
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No products found'
            ]);
        }
    }


    public function list_products() {
        $price_column = null;
        $default_price_column = AdminSetting::where('option_name', 'default_price_column')->first();
        if (!empty($default_price_column)) {
            $price_column = $default_price_column->option_value;
        }
        else {
            $price_column = 'retailUSD';
        }
        $product_array = [];
        // $products = Product::with('options','options.defaultPrice', 'product_brand','product_image','categories' , 'product_views','apiorderItem', 'product_stock')
        // ->with(['product_views','apiorderItem' , 'options' => function ($q) {
        //     $q->where('status', '!=', 'Disabled');
            
        // }])
        // ->whereHas('options.defaultPrice', function ($q)  use ($price_column){
        //     $q->where($price_column, '>', 0);
        // })
        // ->whereHas('categories' , function ($q) {
        //     $q->where('is_active', 1);
        // })
        // ->where('status' , '!=' , 'Inactive')
        // // ->where('barcode' , '!=' , '')
        // ->take(10)
        // ->get();
        $product_categories = Category::where('is_active', 1)->pluck('category_id')->toArray();
        $all_products_ids = Product::whereIn('category_id' , $product_categories)
        ->pluck('product_id')->toArray();
        $product_options_ids = ProductOption::whereIn('product_id' , $all_products_ids)
        ->where('status', '!=', 'Disabled')
        ->where('optionWeight', '>', 0)
        ->pluck('option_id')->toArray();
        $product_pricing_option_ids = Pricingnew::whereIn('option_id' , $product_options_ids)
        ->where($price_column , '>' , 0)
        ->pluck('option_id')
        ->toArray();
        $products_ids = ProductOption::whereIn('option_id' , $product_pricing_option_ids)
        ->pluck('product_id')->toArray();
        $products = Product::with('options','options.defaultPrice','product_brand','product_image','categories')
        ->whereIn('product_id' , $products_ids)
        ->where('status' , '!=' , 'Inactive')
        ->where('barcode' , '!=' , '')
        ->get();
        dd($products->count());
        if (count($products) > 0) {
            foreach ($products as $product) {
                
                if (count($product->options) > 0) {
                    // if (!empty($product->images)) {
                    //     $response  = Http::get($product->images);
                    //     if ($response->getStatusCode() == 200) {
                    //         $image = $product->images;
                    //     } else {
                    //         $image = url(asset('theme/img/image_not_available.png'));
                    //     }
                    // }  else {
                    //     $image = url(asset('theme/img/image_not_available.png'));
                    // }
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
                            'image_link' => !empty($product->product_image->image) ? url(asset('theme/products/images/' .$product->product_image->image)) : url(asset('theme/img/image_not_available.png')),
                            'price' => !empty($option->price[0]->$price_column) ? $option->price[0]->$price_column : 0,
                            'condition' => 'new',
                            'availability' => 'In stock',
                            'brand' => !empty($product->product_brand->name) ? $product->product_brand->name : 'General brand',
                            'barcode' => $product->barcode,
                            'google_product_category' => $category,
                        ];
                    }
                }
            }
        }
    }



    public function handleCallbackRetrieve(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->setScopes([
            'openid',
            'profile',
            'email',
            'https://www.googleapis.com/auth/content', // Add other necessary scopes
        ]);
        $code = $request->input('code');
        $token = $client->fetchAccessTokenWithAuthCode($code);
        $result  = $this->retriveProducts($token , $client);
        
    }

    // public function retriveProducts($token , $client) {
    //     $merchantId =config('services.google.merchant_center_id');
    //     $client->setAccessToken($token['access_token']);
    //     $service = new ShoppingContent($client);
    //     $parameters = [];
    //     do {
    //         $products = $service->products->listProducts($merchantId, $parameters);

    //         dd($products);
            
    //         if (!empty($products->getResources())) {
    //             foreach ($products->getResources() as $product) {
    //                 $productId = $product->getId();
    //                 $title = $product->getTitle();
    //                 $price = $product->getPrice();
    //                 $availability = $product->getAvailability();
    //                 $condition = $product->getCondition();
    //                 $brand = $product->getBrand();
    //                 $gtin = $product->getGtin();
    //                 $mpn = $product->getMpn();
    //                 $link = $product->getLink();
    //                 $image_link = $product->getImageLink();
    //                 $description = $product->getDescription();
    //                 $category = $product->getGoogleProductCategory();
    //                 $product_weight = $product->getShippingWeight();
    //                 $product_array[] = [
    //                     'id' => $productId,
    //                     'title' => $title,
    //                     'price' => $price,
    //                     'availability' => $availability,
    //                     'condition' => $condition,
    //                     'brand' => $brand,
    //                     'gtin' => $gtin,
    //                     'mpn' => $mpn,
    //                     'link' => $link,
    //                     'image_link' => $image_link,
    //                     'description' => $description,
    //                     'category' => $category,
    //                     'product_weight' => $product_weight,
    //                 ];
    //             }
    //         }
    //     } while (!empty($products->getNextPageToken()));
    //     // dd($product_array);
    // }

    // public function retriveProducts($token , $client) {
    //     $merchantId = config('services.google.merchant_center_id');
    //     $client->setAccessToken($token['access_token']);
    //     $service = new ShoppingContent($client);
    //     $parameters = [];
    //     $product_array = [];
    
    //     do {
    //         $products = $service->products->listProducts($merchantId, $parameters);
    
    //         // dd($products->getResources());
    
    //         if (!empty($products->getResources())) {
    //             foreach ($products->getResources() as $product) {
    //                 $suggestedPrice = $product->getSalePrice();
    
    //                 // Only add product if suggested sale price is available
    //                 if ($suggestedPrice) {
    //                     $product_array[] = [
    //                         'id' => $product->getId(),
    //                         'title' => $product->getTitle(),
    //                         'price' => $product->getPrice(),
    //                         'suggested_price' => $suggestedPrice,
    //                         'availability' => $product->getAvailability(),
    //                         'condition' => $product->getCondition(),
    //                         'brand' => $product->getBrand(),
    //                         'gtin' => $product->getGtin(),
    //                         'mpn' => $product->getMpn(),
    //                         'link' => $product->getLink(),
    //                         'image_link' => $product->getImageLink(),
    //                         'description' => $product->getDescription(),
    //                         'category' => $product->getGoogleProductCategory(),
    //                         'product_weight' => $product->getShippingWeight(),
    //                     ];
    //                 }
    //             }
    //         }
    //     } while (!empty($products->getNextPageToken()));
    
    //     // dd($product_array);
    
    //     dd($product_array);
    // }

    public function retriveProducts($token, $client) {
        $merchantId = config('services.google.merchant_center_id');
        $client->setAccessToken($token['access_token']);
        $service = new ShoppingContent($client);
        $parameters = ['maxResults' => 10]; // Set max results to 100
        $product_array = [];
    
        do {
            $products = $service->products->listProducts($merchantId, $parameters);
            dd($products);
    
            if (!empty($products->getResources())) {
                foreach ($products->getResources() as $product) {
                    $suggestedPrice = $product->getPrice();
    
                    if ($suggestedPrice) {
                        $product_array[] = [
                            'id' => $product->getId(),
                            'title' => $product->getTitle(),
                            'price' => $product->getPrice(),
                            'suggested_price' => $suggestedPrice,
                            'availability' => $product->getAvailability(),
                            'condition' => $product->getCondition(),
                            'brand' => $product->getBrand(),
                            'gtin' => $product->getGtin(),
                            'mpn' => $product->getMpn(),
                            'link' => $product->getLink(),
                            'image_link' => $product->getImageLink(),
                            'description' => $product->getDescription(),
                            'category' => $product->getGoogleProductCategory(),
                            'product_weight' => $product->getShippingWeight(),
                        ];
                    }
                }
            }
    
            // Check for pagination and update parameters
            $parameters['pageToken'] = $products->getNextPageToken();
        } while (!empty($parameters['pageToken']));
    
        dd($product_array);
    }
    
    
    
}