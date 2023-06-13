<?php

namespace App\Helpers;

use GuzzleHttp\Client;

use App\Models\DailyApiLog;

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

        if (!empty($extra['api_end_point'])) {
            self::saveDailyApiLog($extra['api_end_point']);
        }

        $api_response = $res->getBody()->getContents();
        return $api_response;
    }

    public static function saveDailyApiLog($api_end_point)
    {
        $daily_api_log = DailyApiLog::where('date', date('Y-m-d'))
            ->where('api_endpoint', $api_end_point)
            ->first();
        
        if (empty($daily_api_log)) {
            $daily_api_log = new DailyApiLog();
            $daily_api_log->date = date('Y-m-d');
            $daily_api_log->api_endpoint = $api_end_point;
            $daily_api_log->count = 1;
            $daily_api_log->save();
        } else {
            $daily_api_log->count = $daily_api_log->count + 1;
            $daily_api_log->save();
        }

        return true;
    }
}