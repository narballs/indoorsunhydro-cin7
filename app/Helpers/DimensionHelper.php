<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class DimensionHelper
{
    /**
     * Resolve product dimensions & weight.
     * Priority:
     *   1) DB values
     *   2) AI estimate
     *   3) Formula fallback
     */
    public static function resolve($option): array
    {
        $weight = (float)($option->optionWeight ?? 0);

        // 1) Always try AI (product name + weight only)
        try {
            $aiDims = self::predictWithAI($option->products->name, $weight);
            if ($aiDims[0] > 0 && $aiDims[1] > 0 && $aiDims[2] > 0) {
                return $aiDims;
            }
        } catch (\Exception $e) {
            // fail silently → fallback
        }

        // 2) Formula fallback (last resort)
        return self::fallbackFormula($weight);
    }



    /**
     * Ask AI (OpenAI GPT) for dimension estimates
     */
    private static function predictWithAI(string $name, float $weight): array
    {
        $apiKey = config('services.ai.ai_key'); // configure in services.php
        if (empty($apiKey)) {
            return [0, 0, 0, $weight];
        }

        $client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ],
            'timeout' => 15,
        ]);

        $prompt = "Estimate realistic shipping dimensions (in inches).
        Product: {$name}, Weight: {$weight} lbs.
        Respond ONLY with JSON: {\"length\":x,\"width\":y,\"height\":z}.";

        $response = $client->post('chat/completions', [
            'json' => [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a shipping assistant.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.2,
                'max_tokens' => 200,
            ],
        ]);

        $body = json_decode($response->getBody()->getContents(), true);
        $aiText = $body['choices'][0]['message']['content'] ?? '{}';
        $data = json_decode($aiText, true);

        return [
            (float)($data['length'] ?? 0),
            (float)($data['width'] ?? 0),
            (float)($data['height'] ?? 0),
            $weight, // always keep original DB/unit weight
        ];
    }


    /**
     * Formula fallback when both DB + AI fail
     */
    private static function fallbackFormula(float $weight): array
    {
        $weight = ($weight > 0) ? $weight : 0.5;

        // assume density ~ 10 lb/ft³ = 0.006 lb/in³
        $density = 0.006;
        $volume = $weight / $density;
        $side = pow($volume, 1/3);

        // clamp to reasonable box sizes
        $side = max(4, min($side, 60));

        return [$side, $side, $side, $weight];
    }
}
