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
        // $result  = $this->delete_feed_products($token , $client);
        $result  = $this->insertProducts($token , $client);
        if ($result->getStatusCode() == 200) {
            return redirect()->route('admin.view')->with('success', 'Products inserted successfully');
        } else {
            return redirect()->route('admin.view')->with('error', 'Something went wrong');
        }
    }

    public function insertProducts($token , $client)
    {
        
        $product_array = [];
        $products = Product::with('options','options.defaultPrice', 'product_brand', 'categories' , 'product_views','apiorderItem', 'product_stock')
        ->with(['product_views','apiorderItem' , 'options' => function ($q) {
            $q->where('status', '!=', 'Disabled');
        }])
        ->where('status' , '!=' , 'Inactive')
        ->get();
        if (count($products) > 0) {
            foreach ($products as $product) {
                if (count($product->options) > 0) {
                    foreach ($product->options as $option) {
                        $product_array[] = [
                            'id' => $product->id,
                            'title' => $product->name,
                            'description' => !empty($product->description) ? strip_tags($product->description) : 'No description available',
                            'link' => url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug),
                            'image_link' => !empty($product->images) ?  $product->images : asset('theme/img/image_not_available.png'),
                            'price' => !empty($option->price[0]->retailUSD) ? $option->price[0]->retailUSD : 0,
                            'condition' => 'new',
                            'availability' => 'In stock',
                            'brand' => !empty($product->product_brand->name) ? $product->product_brand->name : 'General brand',
                            'google_product_category' => !empty($product->categories->name) ? $product->categories->name : 'General category',
                        ];
                    }
                }
            }
        }
        $chunks = array_chunk($product_array, 100);
        $client->setAccessToken($token['access_token']); // Use the stored access token

        $service = new ShoppingContent($client);
        $result  = null;
        if (count($chunks) > 0) {
            foreach ($chunks as $product_chunk) {
                foreach ($product_chunk as $index => $add_product) {
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
                    $product->setGoogleProductCategory($add_product['google_product_category']);
                    $product->setGtin('9780007350896');
            
                    $price = new Price();
                    $price->setValue($add_product['price']);
                    $price->setCurrency('USD');
            
                    $product->setPrice($price);
                    $merchant_id = config('services.google.merchant_center_id');

                    $result = $service->products->delete($merchant_id, $product);
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

    // public function delete_feed_products($token , $client) {
    //     $apiEndpoint = 'https://shoppingcontent.googleapis.com/content/v2.1/{merchantId}/products';
    //     $merchant_id = config('services.google.merchant_center_id');
    //     $apiEndpoint = str_replace('{merchantId}', $merchant_id, $apiEndpoint);

    //     // Create a Guzzle client
    //     $client->setAccessToken($token);
    //     if ($client->isAccessTokenExpired()) {
    //         $newToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    //         $client->setAccessToken($newToken);
    //     } 

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $token['access_token'],
    //         'Content-Type' => 'application/json',
    //     ])->get($apiEndpoint);
        
    //     $body = $response->body();
    //     $responseData = json_decode($body, true);
    //     dd($responseData);
    //     // Check the response status or handle errors accordingly
    //     if ($response->getStatusCode() == 200) {
            
    //         foreach ($responseData['resources'] as $product) {
    //             $apiEndpoint_2 = 'https://shoppingcontent.googleapis.com/content/v2.1/{merchantId}/products/{productId}';
    //             $apiEndpoint_2 = str_replace(['{merchantId}', '{productId}'], [$merchant_id, $product['id']], $apiEndpoint_2);

    //             // Send DELETE request
    //             $response = Http::withHeaders([
    //                 'Authorization' => 'Bearer ' . $token['access_token'],
    //                 'Content-Type' => 'application/json',
    //             ])->delete($apiEndpoint_2);
    //         }
    //     } else {
    //         // Handle errors
    //         echo "Error retrieving products: " . $responseData['error']['message'];
    //     }
    // }
}