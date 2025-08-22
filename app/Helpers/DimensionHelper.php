<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class DimensionHelper
{
    /**
     * Resolve product dimensions & weight.
     * Priority:
     *   1) DB values (adjusted for qty)
     *   2) AI estimate (if enabled)
     *   3) Only weight (0 dimensions)
     */
    public static function resolve($option, int $qty = 1, bool $useAI = false): array
    {
        $weight = (float)($option->optionWeight ?? 0);

        // 1) DB values if available
        $dbLength = (float)($option->products->length ?? 0);
        $dbWidth  = (float)($option->products->width ?? 0);
        $dbHeight = (float)($option->products->height ?? 0);

        if ($dbLength > 0 && $dbWidth > 0 && $dbHeight > 0) {
            return self::adjustForQuantity($dbLength, $dbWidth, $dbHeight, $weight, $qty);
        }

        // 2) Optional AI
        if ($useAI) {
            try {
                $aiDims = self::predictWithAI($option->products->name ?? 'Unknown', $weight);
                if ($aiDims[0] > 0 && $aiDims[1] > 0 && $aiDims[2] > 0) {
                    return self::adjustForQuantity($aiDims[0], $aiDims[1], $aiDims[2], $weight, $qty);
                }
            } catch (\Exception $e) {
                // fail silently
            }
        }

        // 3) Only weight (no dimensions)
        return [0, 0, 0, $weight * $qty];
    }

    private static function adjustForQuantity(float $L, float $W, float $H, float $weight, int $qty): array
    {
        if ($qty <= 1) {
            return [$L, $W, $H, $weight];
        }

        if ($L > 0 && $W > 0 && $H > 0) {
            return [$L, $W, $H * $qty, $weight * $qty];
        }

        return [0, 0, 0, $weight * $qty];
    }

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
}
