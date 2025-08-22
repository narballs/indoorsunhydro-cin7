<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class DimensionHelper
{
    /**
     * Resolve product dimensions & weight.
     * Priority:
     *   1) DB values (adjusted for qty)
     *   2) AI estimate
     *   3) Formula fallback
     */
    public static function resolve($option, int $qty = 1): array
    {
        $weight = (float)($option->optionWeight ?? 0);

        // 1) Use DB values if available
        $dbLength = (float)($option->products->length ?? 0);
        $dbWidth  = (float)($option->products->width ?? 0);
        $dbHeight = (float)($option->products->height ?? 0);

        if ($dbLength > 0 && $dbWidth > 0 && $dbHeight > 0) {
            return self::adjustForQuantity($dbLength, $dbWidth, $dbHeight, $weight, $qty);
        }

        // 2) Try AI estimate
        try {
            $aiDims = self::predictWithAI($option->products->name ?? 'Unknown', $weight);
            if ($aiDims[0] > 0 && $aiDims[1] > 0 && $aiDims[2] > 0) {
                return self::adjustForQuantity($aiDims[0], $aiDims[1], $aiDims[2], $weight, $qty);
            }
        } catch (\Exception $e) {
            // fail silently → fallback
        }

        // 3) Formula fallback (last resort)
        $fallback = self::fallbackFormula($weight);
        return self::adjustForQuantity($fallback[0], $fallback[1], $fallback[2], $weight, $qty);
    }


    /**
     * Adjust dimensions for multiple quantity
     * (stacking products in layers inside one box)
     */
    private static function adjustForQuantity(float $L, float $W, float $H, float $weight, int $qty): array
    {
        if ($qty <= 1) {
            return [$L, $W, $H, $weight];
        }

        // Assume best fit stacking (greedy):
        // Try to keep L and W same, and stack height
        $stackH = $H * $qty;

        // total weight scales linearly
        $totalWeight = $weight * $qty;

        // return adjusted "virtual box"
        return [$L, $W, $stackH, $totalWeight];
    }


    /**
     * Ask AI (OpenAI GPT) for dimension estimates
     */
    private static function predictWithAI(string $name, float $weight): array
    {
        $apiKey = config('services.ai.ai_key');
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
            $weight,
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

        $side = max(4, min($side, 60));

        return [$side, $side, $side, $weight];
    }
}
