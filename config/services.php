<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET')
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'refund_webhook_secret' => env('STRIPE_REFUND_WEBHOOK_SECRET'),
    ],
    'shipstation' => [
        'key' => env('SHIPMENT_KEY'),
        'secret' => env('SHIPMENT_SECRET'),
        'host_url' => env('SHIPMENT_HOST_URL'),
        'shipment_order_url' => env('SHIPMENT_ORDER_URL'),
        'shipment_label_url' => env('SHIPMENT_LABEL_URL'),
    ],
    'ai' => [
        'ai_key' => env('OPEN_AI_KEY'),
    ],
    'cin7' => [
        'get_contact_url' => env('Cin7_GET_CONTACT_URL'),
        'get_stripe_public_key' => env('Cin7_STRIPE_PUBLIC_KEY'),
        'get_stripe_secret_key' => env('Cin7_STRIPE_SECRET_KEY'),
        'wholesale_payment_configuration' => env('wholesale_payment_confirmation_key'),
    ],
    'google_address_validator' => [
        'address_validator_google_key' => env('Address_validator_api_key'),
    ],
    'google' => [
        'api_key' => env('GOOGLE_API_KEY'),
        'client_id' => env('GOOGLE_OAUTH_CLIENT_ID'),
        'client_secret' => env('GOOGLE_OAUTH_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_OAUTH_REDIRECT_URI'),
        'merchant_center_id' => env('GOOGLE_MERCHANT_CENTER_ID'),
        'scopes' => [
            'https://www.googleapis.com/auth/content', // Add other necessary scopes
        ],
    ],

];
