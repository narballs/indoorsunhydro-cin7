<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class GetProductDimensionController extends Controller
{
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . config('services.ai.ai_key'),
                'Content-Type'  => 'application/json',
            ],
        ]);
    }
    
    public function get_product_dimension(Request $request)
    {
        $apiKey = config('services.ai.ai_key');
        $question = 'what does this product "Electrical Tap #1 - LARGE" do?';
        $response = $this->client->post('chat/completions', [
            'json' => [
                'model' => 'gpt-3.5-turbo',  // or 'gpt-4' if available
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $question],
                ],
                'max_tokens' => 250,
            ],
        ]);

        $body = json_decode($response->getBody()->getContents(), true);
        dd($body);
        return $body['choices'][0]['message']['content'] ?? 'Sorry, I could not find an answer.';
        // $prompt = 'Please find the weight of the product where SKU=JRP79060 and where category=  Nutrients';
        // $client = new Client([
        //     'base_uri' => 'https://api.openai.com/v1/',
        //     'headers' => [
        //         'Authorization' => 'Bearer ' . $apiKey,
        //         'Content-Type' => 'application/json',
        //     ],
        // ]);
        // $response = $client->post('completions', [
        //     'json' => [
        //         'model' => 'text-embedding-ada-002', // Specify the GPT-3 model you want to use
        //         'prompt' => $prompt,
        //         'max_tokens' => 150, 
        //         "temperature"=> 0.8
        //     ],
        // ]);
        // $data = json_decode($response->getBody()->getContents(), true);
        // dd($data);
        // $generatedResponse = $data['choices'][0]['text'];
        // dd($generatedResponse);
        // return response()->json(['response' => $generatedResponse]);
        // $textDescription = 'what does this product "Electrical Tap #1 - LARGE" do?';
        
        // // Make a POST request to the API endpoint with the text description
        // $client = new Client();
        // $response = $client->post('https://api.openai.com/v1/engines/davinci-002/completions', [
        //     'headers' => [
        //         'Authorization' => 'Bearer ' . $apiKey,
        //         'Content-Type' => 'application/json',
        //     ],
        //     'json' => [
        //         'prompt' => $textDescription,
        //         'max_tokens' => 150,
        //         'temperature' => 0.7,
        //         'top_p' => 1,
        //         'frequency_penalty' => 0,
        //         'presence_penalty' => 0,
        //         'stop' => ['\n']
        //     ]
        // ]);

        // $responseData = json_decode($response->getBody(), true);
        // dd($responseData);
        // $extractedText = $responseData['choices'][0]['text'];

        // // Parse the extracted text to get weight and dimensions
        // $weight = $this->extractValue($extractedText, 'Weight');
        // $dimensions = $this->extractValue($extractedText, 'Dimensions');

        // return response()->json(['weight' => $weight, 'dimensions' => $dimensions]);
    }

    private function extractValue($text, $attribute)
    {
        // Extract the value of the attribute from the text
        preg_match("/$attribute: (.*?)(?:\n|$)/", $text, $matches);
        if (isset($matches[1])) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }
}