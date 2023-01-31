<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class UtilHelper
{
    /**
     * get user option_name value.
     *
     * @param  method
     * @param  url
     * @param  body
     * @return extra
     */
    public static function sendRequest($method, $url, $body = [], $extra = [])
    {
        $authHeaders = [
            'headers' => ['Content-type' => 'application/json'],
            'auth' => [
                env('API_USER'),
                env('API_PASSWORD')
            ]
        ];
        $parameters = [
            'headers' => ['Content-type' => 'application/json'],
            'auth' => [
                'IndoorSunHydro2US',
                '625ab949593e4cd4908b9f42758009f5'
            ],
        ];

        if (!empty($body)) {
            $parameters['json'] = $body;
        }

        $client = new \GuzzleHttp\Client();

        switch ($method) {
            case 'POST':
                $res = $client->post($url, $parameters);
                break;
            case 'PUT':
                // code...
                break;
            case 'GET':
                $res = $client->get($url, $parameters);
                break;

            default:
                $res = $client->get($url, [
                    'auth' => $authHeaders
                ]);
                break;
        }

        $api_response = $res->getBody()->getContents();

        return $api_response;
    }
}