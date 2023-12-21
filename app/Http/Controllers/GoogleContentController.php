<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Google\Service\ShoppingContent;
use Google\Service\ShoppingContent\Price;
use Google_Client;
use Google_Service_ShoppingContent;
use Google_Service_ShoppingContent_Product;
use Google_Service_ShoppingContent_ProductsCustomBatchRequest;
use Google_Service_ShoppingContent_ProductsCustomBatchRequestEntry;
use Google_Service_ShoppingContent_Price;
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
        $this->insertProducts($token , $client);
    }

    public function insertProducts($token , $client)
    {
        
        $product_array = [];
        $products = Product::with('options','options.defaultPrice', 'brand', 'categories' , 'product_views','apiorderItem', 'product_stock')
        ->where('status' , '!=' , 'Inactive')
        ->take(10)
        ->get();
        if (count($products) > 0) {
            foreach ($products as $product) {
                if (count($product->options) > 0) {
                    foreach ($product->options as $option) {
                        $product_array[] = [
                            'id' => $product->id,
                            'title' => $product->name,
                            'description' => $product->description,
                            'link' => url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug),
                            'image_link' => !empty($product->images) ?  $product->images : asset('theme/img/image_not_available.png'),
                            'price' => !empty($option->price[0]->retailUSD) ? $option->price[0]->retailUSD : $product->price,
                            'condition' => 'new',
                            'availability' => 'in stock',
                            'brand' => !empty($product->brand[0]->name) ? $product->brand[0]->name : 'No brand',
                            'google_product_category' => !empty($product->categories->name) ? $product->categories->name : 'No category',
                        ];
                    }
                }
            }
        }
        // $client = new Google_Client();
        $client->setAccessToken($token['access_token']); // Use the stored access token

        $service = new ShoppingContent($client);

        $batchRequests = [];
        foreach ($product_array as $index => $product) {
            $item =  new ShoppingContent\Product();
            // Set product data
            $item->setOfferId($product['id']);
            $item->setTitle($product['title']);
            $item->setDescription($product['description']);
            $item->setLink($product['link']);
            $item->setImageLink($product['image_link']);
    
            // Set the Price
            $price = new Price();
            $price->setValue($product['price']);
            $price->setCurrency('USD'); // Adjust the currency as needed
            $item->setPrice($price);
    
            $item->setCondition($product['condition']);
            $item->setAvailability($product['availability']);
            $item->setBrand($product['brand']);
            $item->setGoogleProductCategory($product['google_product_category']);
            $item->setChannel('local');
            $item->setcontentLanguage('en');
            $item->settargetCountry('US');
            $request = new ShoppingContent\ProductsCustomBatchRequestEntry();
            $request->setMethod('insert');
            $request->setBatchId($product['id']); // Use a unique identifier for each entry
            $request->setMerchantId(config('services.google.merchant_center_id'));
            $request->setProduct($item);
    
            $batchRequests[] = $request;
        }
    
        $batchRequest = new ShoppingContent\ProductsCustomBatchRequest();
        $batchRequest->setEntries($batchRequests);
        
        try {
            $batchResponse = $service->products->custombatch($batchRequest);
            foreach ($batchResponse->entries as $entry) {
                // Access information about each operation
                $product = $entry->product;
                // Extract specific details
                $productId = $product->id;
                $title = $product->title;

                // Output or process the information as needed
                echo "Product ID: $productId\n";
                echo "Title: $title\n";
                echo "-----------------\n";
            }
        } catch (\Google_Service_Exception $e) {
            // Handle exceptions (e.g., authentication issues, API errors)
            echo "Error: " . $e->getMessage();
        }
    }
}