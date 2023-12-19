<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_ShoppingContent;
use Google_Service_ShoppingContent_Product;
use Google_Service_ShoppingContent_Price;
use Google_Service_ShoppingContent_ProductsCustomBatchRequest;

class GoogleContentController extends Controller
{
    public function createProductFeed()
    {
        // Set your credentials file path
        $credentialsPath = base_path('credentials.json');

        // Set your Merchant Center ID
        $merchantId = 'your-merchant-id';

        // Create a Google API client
        $client = new Google_Client();
        $client->setAuthConfig($credentialsPath);
        $client->setScopes(['https://www.googleapis.com/auth/content']);

        // Create a Content API service
        $contentApi = new Google_Service_ShoppingContent($client);

        // Create an array of products (you can dynamically fetch this from your database or another source)
        $products = [
            [
                'id' => '1',
                'title' => 'Indoor Plant',
                'description' => 'Beautiful indoor plant for your home',
                'link' => 'https://example.com/plant',
                'image_link' => 'https://example.com/plant_image.jpg',
                'price' => 29.99,
                'condition' => 'new',
                'availability' => 'in stock',
                'brand' => 'YourBrand',
                'google_product_category' => 'Home & Garden > Plants > Indoor Plants',
            ],
            // Add more products as needed
        ];

        // Loop through products and create product entries
        $productEntries = [];
        foreach ($products as $product) {
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

            $productEntries[] = $productEntry;
        }

        // Create a batch request to insert the products
        $batchRequest = new Google_Service_ShoppingContent_ProductsCustomBatchRequest();
        $batchRequest->setEntries($productEntries);

        // Execute the batch request
        $batchResponse = $contentApi->products->custombatch($batchRequest, $merchantId);

        // Check the batch response for errors
        $successCount = 0;
        $errorMessages = [];

        foreach ($batchResponse->entries as $entry) {
            if ($entry->getErrors()) {
                // Handle errors
                foreach ($entry->getErrors() as $error) {
                    $errorMessages[] = "Product ID: " . $error->getProductId() . ", Error: " . $error->getMessage();
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
    }
}
