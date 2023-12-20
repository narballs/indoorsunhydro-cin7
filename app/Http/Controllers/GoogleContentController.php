<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Google_Client;
use Google_Service_ShoppingContent;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class GoogleContentController extends Controller
{
    

    // public function createProductFeed(Request $request)
    // {
    //     $merchantId = '5309938228';
    //     $apiUrl = "https://shoppingcontent.googleapis.com/content/v2.1/5309938228/products";
    //     $get_token = Socialite::driver('google')->user();
    //     $productData = [];
    //     $products = Product::with('options','options.defaultPrice', 'brand', 'categories' , 'product_views','apiorderItem', 'product_stock')
    //     ->where('status' , '!=' , 'Inactive')
    //     ->get();
    //     if (count($products) > 0) {
    //         foreach ($products as $product) {
    //             if (count($product->options) > 0) {
    //                 foreach ($product->options as $option) {
    //                     $productData[] = [
    //                         'id' => $product->id,
    //                         'title' => $product->name,
    //                         'description' => $product->description,
    //                         'link' => url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug),
    //                         'image_link' => !empty($product->images) ?  $product->images : asset('theme/img/image_not_available.png'),
    //                         'price' => !empty($option->price[0]->retailUSD) ? $option->price[0]->retailUSD : $product->price,
    //                         'condition' => 'new',
    //                         'availability' => 'in stock',
    //                         'brand' => !empty($product->brand[0]->name) ? $product->brand[0]->name : 'No brand',
    //                         'google_product_category' => !empty($product->categories->name) ? $product->categories->name : 'No category',
    //                     ];
    //                 }
    //             }
    //         }
    //     }

        
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $get_token->token, // Replace with your actual access token
    //         'Content-Type' => 'application/json',
    //     ])->post($apiUrl, $productData);
    //     dd($response);
    //     if ($response->getStatusCode() == 200) {
    //         $responseData = json_decode($response->getBody(), true);
    //         return response()->json($responseData);
    //     } else {
    //         $errorData = json_decode($response->getBody(), true);
    //         return response()->json($errorData, $response->getStatusCode());
    //     }
    // }

    
    // public function redirectToGoogle()
    // {
        
    //     return Socialite::driver('google')
    //     ->scopes([
    //         'openid',
    //         'profile',
    //         'email',
    //         'https://www.googleapis.com/auth/content', // Add other necessary scopes
    //     ])
    //     ->redirect();
       
        
    // }


    public function authorizeGoogle()
    {
        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect_uri'));
        $client->setScopes(['https://www.googleapis.com/auth/content']);

        return redirect($client->createAuthUrl());
    }

    public function handleCallback(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->setScopes(['https://www.googleapis.com/auth/content']);

        $code = $request->input('code');
        $token = $client->fetchAccessTokenWithAuthCode($code);
        // Store $token['access_token'] securely for future requests

        return redirect()->route('google.insertProducts');
    }

    public function insertProducts()
    {
        // Replace with your actual product data retrieval logic from the database
        $products = Product::all();
        $get_token = Socialite::driver('google')->user();
        $client = new Google_Client();
        $client->setAccessToken($get_token['access_token']); // Use the stored access token

        $service = new Google_Service_ShoppingContent($client);

        foreach ($products as $product) {
            $item = new Google_Service_ShoppingContent_Product();
            // Set product data
            $item->setOfferId($product->id);
            $item->setTitle($product->title);
            $item->setDescription($product->description);
            // Add other product attributes

            $request = new Google_Service_ShoppingContent_ProductsCustomBatchRequestEntry();
            $request->setMethod('insert');
            $request->setMerchantId(config('services.google.merchant_id'));
            $request->setProduct($item);

            $batchRequests[] = $request;
        }

        $batchRequest = new Google_Service_ShoppingContent_ProductsCustomBatchRequest();
        $batchRequest->setEntries($batchRequests);

        $batchResponse = $service->products->custombatch($batchRequest);

        // Handle batch response as needed

        return response()->json($batchResponse);
    }
}