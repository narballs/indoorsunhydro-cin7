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
    ],
    'shipstation' => [
        'key' => env('SHIPMENT_KEY'),
        'secret' => env('SHIPMENT_SECRET'),
        'host_url' => env('SHIPMENT_HOST_URL'),
        'shipment_order_url' => env('SHIPMENT_ORDER_URL'),
        'shipment_label_url' => env('SHIPMENT_LABEL_URL'),
    ],
    'cin7' => [
        'get_contact_url' => env('Cin7_GET_CONTACT_URL'),
    ],

];
