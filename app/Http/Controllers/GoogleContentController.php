<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_ShoppingContent;
use Google_Service_ShoppingContent_Product;
use Google_Service_ShoppingContent_ProductsCustomBatchRequest;
use Google_Service_ShoppingContent_ProductsCustomBatchRequestEntry;
use Google_Service_ShoppingContent_Price;

class GoogleContentController extends Controller
{
    public function createProductFeed(Request $request)
    {
        
        $product_array = [];
        $products = Product::with('options','options.defaultPrice', 'brand', 'categories' , 'product_views','apiorderItem', 'product_stock')
        ->where('status' , '!=' , 'Inactive')
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
        // Set your credentials file path
        $credentialsPath = base_path('localcredentials.json');
        $merchantId  = env('merchent_id');
        // Create a Google API client
        $client = new Google_Client();
        $client->setAuthConfig($credentialsPath);
        $client->setScopes(['https://www.googleapis.com/auth/content']);
        $appUrl = env('APP_URL');
        $redirectUri = $appUrl .'/'.'auth/callback/google' ; // Replace with your actual redirect URI
        $client->setRedirectUri($redirectUri);
        if ($request->has('code')) {
            $token = $client->fetchAccessTokenWithAuthCode($request->input('code'));
            $accessToken = $token['access_token'];
            $contentApi = new Google_Service_ShoppingContent($client);
            $contentApi->setAccessToken($accessToken);

            
            $batchRequest = new Google_Service_ShoppingContent_ProductsCustomBatchRequest();
        
            // Loop through products and create product entries
            foreach ($product_array as $product) {
                $productEntry = new Google_Service_ShoppingContent_Product();
                $productEntry->setOfferId($product['id']);
                $productEntry->setTitle($product['title']);
                $productEntry->setDescription($product['description']);
                $productEntry->setLink($product['link']);
                $productEntry->setImageLink($product['image_link']);
        
                // Set the Price
                $price = new Google_Service_ShoppingContent_Price();
                $price->setValue($product['price']);
                $price->setCurrency('USD'); // Adjust the currency as needed
                $productEntry->setPrice($price);
        
                $productEntry->setCondition($product['condition']);
                $productEntry->setAvailability($product['availability']);
                $productEntry->setBrand($product['brand']);
                $productEntry->setGoogleProductCategory($product['google_product_category']);
                $batchEntry = new Google_Service_ShoppingContent_ProductsCustomBatchRequestEntry();
                $batchEntry->setMethod('insert');
                $batchEntry->setProduct($productEntry);
                $batchEntries[] = $batchEntry;
            }
            $batchRequest->setEntries($batchEntries);
            $batchResponse = $contentApi->products->custombatch($batchRequest, (array)$merchantId);
            // Check if $batchResponse is an array and contains 'entries'
            if (is_array($batchResponse) && isset($batchResponse['entries'])) {
                $successCount = 0;
                $errorMessages = [];

                foreach ($batchResponse['entries'] as $entry) {
                    if (isset($entry['errors']) && !empty($entry['errors'])) {
                        // Handle errors
                        foreach ($entry['errors'] as $error) {
                            $errorMessages[] = "Product ID: " . $error['productId'] . ", Error: " . $error['message'];
                        }
                    } else {
                        // Count successful product additions
                        $successCount++;
                    }
                }

                // Handle the results based on your needs
                if ($successCount > 0) {
                    // Redirect with success message
                    return redirect()->route('product.feed')->with('success', "{$successCount} products added successfully.");
                } else {
                    // Redirect with error messages
                    return redirect()->route('product.feed')->with('error', implode("\n", $errorMessages));
                }
            } else {
                // Handle the case where $batchResponse is not an array or does not contain 'entries'
                echo 'Error: $batchResponse is not an array or does not contain "entries".';
            }
        } else {
            // Redirect to Google's OAuth 2.0 server
            $authUrl = $client->createAuthUrl();
            return redirect()->away($authUrl);
        }
    }
}
