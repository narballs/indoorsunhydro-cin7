<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIImageGenerationService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPEN_AI_KEY');  // Use the correct key for your AI service
    }

    // public function generateAndSaveImage($product)
    // {
    //     // Check if product name, description, and SKU are available
    //     $productName = $product->name ? $product->name : 'Unknown Product';
    //     $productDescription = $product->description ? $product->description : 'No description available';
    //     $productSKU = $product->sku ? $product->sku : 'No SKU available';

    //     // Construct a more detailed and specific prompt
    //     $prompt = "Generate a high-quality product image of {$productName} (SKU: {$productSKU}). 
    //                 The product should be shown with clear and realistic details, including its color, shape, size, and texture. 
    //                 The background should be simple and neutral, and the product should be centered in the image. 
    //                 The product should appear exactly as it would in a retail environment. 
    //                 If there is no description available, focus on the general appearance and usability of the product.";

    //     // Call OpenAI's DALL·E API to generate the image
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $this->apiKey,
    //         'Content-Type' => 'application/json',
    //     ])->post('https://api.openai.com/v1/images/generations', [
    //         'prompt' => $prompt,
    //         'n' => 5,  // Generate only one image
    //         'size' => '256x256',  // Larger image size for better quality
    //     ]);

    //     dd($response->json());

    //     if ($response->successful()) {
    //         // Extract the generated image URL
    //         return $response->json()['data'][0]['url']; // Return the image URL
    //     }

    //     return null;  // Return null if image generation fails
    // }

    public function generateAndSaveImage($product)
    {
        $apiKey = env('GOOGLE_API_KEY');  // Your Google API Key
        $cx = env('GOOGLE_CX');  // Your Custom Search Engine ID (CX)
        $imageLinks = [];

        // Fetch product details
        $productName = $product->name ?: 'Unknown Product';
        $productDescription = $product->description ?: 'No description available';
        $productSKU = $product->sku ?: 'No SKU available';

        // Call Google Custom Search API
        $response = Http::get("https://www.googleapis.com/customsearch/v1", [
            'q' => $productName,
            'cx' => $cx,
            'key' => $apiKey,
            'searchType' => 'image',  // To get image results
        ]);

        // Check if the response is successful
        if ($response->successful()) {
            $data = $response->json();
            
            // Ensure there are items in the response
            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $imageLink = $item['link'] ?? null;
                    
                    // If image link exists, add it to the array
                    if ($imageLink) {
                        $imageLinks[] = $imageLink;
                    }

                    // Stop once we've collected 5 images
                    if (count($imageLinks) === 5) {
                        break;
                    }
                }
            } else {
                Log::info('No image items found for product: ' . $productName);
            }
            
            return $imageLinks;
        }

        // Log the raw response for debugging
        Log::error('API Response Failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        // Return an empty array if no images were found or the API failed
        return [];
    }

}
