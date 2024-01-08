<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Google\Service\ShoppingContent;
use Google\Service\ShoppingContent\Product as ServiceProduct;
use Google\Service\ShoppingContent\Price;
use Google_Client;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Intervention\Image\Facades\Image;

class GoogleContentController extends Controller
{
    

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
        // if ($result->getStatusCode() == 200) {
        //     return redirect()->route('admin.view')->with('success', 'Products inserted successfully');
        // } else {
        //     return redirect()->route('admin.view')->with('error', 'Something went wrong');
        // }
        $result  = $this->list_products($token , $client);
        dd($result);
        
    }

    public function insertProducts($token , $client)
    {
        
        $product_array = [];
        $products = Product::with('options','options.defaultPrice', 'product_brand', 'categories' , 'product_views','apiorderItem', 'product_stock')
        ->with(['product_views','apiorderItem' , 'options' => function ($q) {
            $q->where('status', '!=', 'Disabled');
            
        }])
        ->whereHas('options.defaultPrice', function ($q) {
            $q->where('retailUSD', '!=', 0);
        })
        ->whereHas('categories' , function ($q) {
            $q->where('is_active', 1);
        })
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
                            'image_link' => !empty($product->images) ? $product->images : url(asset('theme/img/image_not_available.png')),
                            'price' => !empty($option->price[0]->retailUSD) ? $option->price[0]->retailUSD : 0,
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
        // $chunks = array_chunk($product_array, 100);
        $client->setAccessToken($token['access_token']); // Use the stored access token

        $service = new ShoppingContent($client);
        $result  = null;
        if (!empty($product_array) > 0) {
            foreach ($product_array as $index => $add_product) {
                $product = new ServiceProduct();
                $product->setOfferId($index);
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
                $product->setGoogleProductCategory($add_product['google_product_category']);
                $product->setGtin($add_product['barcode']);
                // $product->setmultipack('5000');
                // $product->setIdentifierExists(false);
                $product->setMpn($add_product['code']);
                $product->setAgeGroup('adult');
                $product->setColor('universal');
                $product->setGender('unisex');
                $product->setSizes(['Large']);
                $price = new Price();
                $price->setValue($add_product['price']);
                $price->setCurrency('USD');
        
                $product->setPrice($price);
                $merchant_id = config('services.google.merchant_center_id');

                $result = $service->products->insert($merchant_id, $product);
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

    public function list_products($token , $client) {
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
        $client->setAccessToken($token['access_token']);
        $service = new ShoppingContent($client);
        $merchant_id = config('services.google.merchant_center_id');
        $products = $service->products->listProducts($merchant_id);
    }
}