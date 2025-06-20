<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class FacebookConversionService
{
    public static function sendPurchaseEvent($email, $phone, $firstName, $lastName, $city, $state, $zip, $value, $reference)
    {
        $hashed = fn($val) => hash('sha256', strtolower(trim($val)));
        $eventId = $reference;

        $payload = [
            'data' => [
                [
                    'event_name'    => 'Purchase',
                    'event_time'    => time(),
                    'action_source' => 'website',
                    'event_id'      => $eventId,
                    'user_data'     => array_filter([
                        'em' => $email ? [$hashed($email)] : null,
                        'ph' => $phone ? [$hashed($phone)] : null,
                        'fn' => $firstName ? [$hashed($firstName)] : null,
                        'ln' => $lastName ? [$hashed($lastName)] : null,
                        'ct' => $city ? [$hashed($city)] : null,
                        'st' => $state ? [$hashed($state)] : null,
                        'zp' => $zip ? [$hashed($zip)] : null,
                    ]),
                    'custom_data' => [
                        'currency' => 'USD',
                        'value'    => $value
                    ]
                ]
            ]
        ];

        $response = Http::post("https://graph.facebook.com/v18.0/" . env('FB_PIXEL_ID') . "/events", array_merge($payload, [
            'access_token' => env('FB_META_ACCESS_TOKEN'),
        ]));


        return true;
    }

}
