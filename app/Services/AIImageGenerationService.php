<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIImageGenerationService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPEN_AI_KEY');  // Use the correct key for your AI service
    }

    public function generateAndSaveImage($product)
    {
        // Use a more descriptive and structured prompt
        $prompt = "A high-quality, realistic product image of a " . $product->name . ". 
                   The product should be centered, well-lit, and shown in a clean environment.";

        // Call OpenAI's DALL·E API to generate the image
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post('https://api.openai.com/v1/images/generations', [
            'prompt' => $prompt,  
            'n' => 1,  // Generate 1 image
            'size' => '256x256',  // High-resolution image
        ]);

        dd($response->json());

        if ($response->successful()) {
            // Extract the image URL from the response
            return $response->json()['data'][0]['url'];
        }

        return null;  // Return null if generation fails
    }
}
