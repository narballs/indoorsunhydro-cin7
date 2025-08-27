<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class DimensionHelper
{
    public static function resolve($option, int $qty = 1, bool $useAI = true): array
    {
        $weight = (float)($option->optionWeight ?? 0);

        // 1) DB values if available
        $dbLength = (float)($option->products->length ?? 0);
        $dbWidth  = (float)($option->products->width  ?? 0);
        $dbHeight = (float)($option->products->height ?? 0);

        if ($dbLength > 0 && $dbWidth > 0 && $dbHeight > 0) {
            return self::adjustForQuantity($dbLength, $dbWidth, $dbHeight, $weight, $qty);
        }

        // 2) Optional AI prediction
        if ($useAI) {
            try {
                [$L, $W, $H] = self::predictWithAI($option->products->name ?? 'Unknown', $weight);
                if ($L > 0 && $W > 0 && $H > 0) {
                    return self::adjustForQuantity($L, $W, $H, $weight, $qty);
                }
            } catch (\Exception $e) {
                // fail silently and fallback
            }
        }

        // 3) Fallback: only weight
        return [0, 0, 0, $weight * $qty];
    }

    /**
     * Adjust dimensions for quantity using cube-root packing.
     */
    private static function adjustForQuantity(float $L, float $W, float $H, float $weight, int $qty): array
    {
        if ($qty <= 1) {
            return [$L, $W, $H, $weight];
        }

        $perSide = ceil(pow($qty, 1/3)); 
        $unitsPerLayer = $perSide * $perSide;
        $layers = ceil($qty / $unitsPerLayer);

        $length = $L * $perSide;
        $width  = $W * $perSide;
        $height = $H * $layers;

        return [$length, $width, $height, $weight * $qty];
    }

    /**
     * Merge multiple products (different SKUs) into one carton.
     */
    public static function mergeCarton(array $dims): array
    {
        if (empty($dims)) {
            return [0, 0, 0, 0];
        }

        $maxL = 0; $maxW = 0; $totalH = 0; $totalWt = 0;

        foreach ($dims as [$L, $W, $H, $Wt]) {
            $maxL = max($maxL, $L);
            $maxW = max($maxW, $W);
            $totalH += $H;
            $totalWt += $Wt;
        }

        return [$maxL, $maxW, $totalH, $totalWt];
    }

    /**
     * Ask AI to predict dimensions if missing.
     */
    private static function predictWithAI(string $name, float $weight): array
    {
        $apiKey = config('services.ai.ai_key');
        if (empty($apiKey)) {
            return [0, 0, 0];
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
            (float)($data['width']  ?? 0),
            (float)($data['height'] ?? 0),
        ];
    }
}
