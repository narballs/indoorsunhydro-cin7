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
        // $authHeaders = [
        //     'headers' => ['Content-type' => 'application/json'],
        //     'auth' => [
        //         'IndoorSunHydro2US',
        //         '625ab949593e4cd4908b9f42758009f5'
        //     ],
        // ];

        if (!empty($body)) {
            $authHeaders['json'] = $body;
        }

        $client = new \GuzzleHttp\Client();
        
        $res = [];
        switch ($method) {
            case 'POST':
                $res = $client->post($url, $authHeaders);
                break;
            case 'PUT':
                $res = $client->put($url, $authHeaders);
                break;
            case 'GET':
                $res = $client->get($url, $authHeaders); 
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