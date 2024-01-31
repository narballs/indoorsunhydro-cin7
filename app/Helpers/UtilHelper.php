<?php

namespace App\Helpers;

use GuzzleHttp\Client;

use App\Models\DailyApiLog;
use App\Models\AdminSetting;
use App\Models\ProductStock;
use App\Models\ProductOption;
use App\Models\InventoryLocation;

use App\Helpers\SettingHelper;

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
        
        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');

        $authHeaders = [
            'headers' => ['Content-type' => 'application/json'],
            'auth' => [
                $cin7_auth_username,
                $cin7_auth_password
            ]
        ];

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

    public static function updateProductStock($product, $option_id) {
        $setting = AdminSetting::where('option_name', 'check_product_stock')->first();
        $total_stock = 0;
        $stock_updated = false;
        $branch_with_stocks = [];

        if (empty($setting) || ($setting->option_value !=  'Yes')) {
            return $stock_updated;
        }

        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');

        try {
            $url = 'https://api.cin7.com/api/v1/Stock?where=productId=' . $product->product_id . '&productOptionId=' . $option_id;
            $client2 = new \GuzzleHttp\Client();
            $res = $client2->request(
                'GET',
                $url,
                [
                    'auth' => [
                        $cin7_auth_username,
                        $cin7_auth_password
                    ]
                ]
            );

            $inventory = $res->getBody()->getContents();
            $location_inventories = json_decode($inventory);
            if (empty($location_inventories)) {
                return [
                    'stock_updated' => $stock_updated,
                    'branch_with_stocks' => null,

                ];
            }
            $inactive_inventory_locations = InventoryLocation::where('status', 0)->pluck('cin7_branch_id')->toArray();
            
            // $skip_branches = [172, 173, 174];
            $skip_branches = $inactive_inventory_locations;
            $branch_ids = [];
            foreach ($location_inventories as $location_inventory) {
                if (in_array($location_inventory->branchId, $skip_branches)) {
                    continue;
                }
                $branch_with_stocks[] = [
                    'branch_id' => $location_inventory->branchId,
                    'branch_name' => $location_inventory->branchName,
                    'available' => $location_inventory->available
                ];
                $product_stock = ProductStock::where('branch_id' , $location_inventory->branchId)
                    ->where('product_id' ,  $product->product_id)
                    ->where('option_id' , $option_id)
                    ->first();
                
                if (!empty($product_stock)) {
                    $product_stock->available_stock = $location_inventory->available;
                    $product_stock->save();
                    
                    $branch_ids[] = $location_inventory->branchId;
                    if (!in_array($location_inventory->branchId, $skip_branches)) {
                        $total_stock += $location_inventory->available;
                    }
                }
                else {
                    $product_stock = ProductStock::create([
                        'available_stock' => $location_inventory->available,
                        'branch_id' => $location_inventory->branchId,
                        'product_id' => $product->product_id,
                        'branch_name' => $location_inventory->branchName,
                        'option_id' => $option_id
                    ]);
                    if (!in_array($location_inventory->branchId, $skip_branches)) {
                        $total_stock += $location_inventory->available;
                    }
                    // $total_stock += $product_stock->available_stock;
                }

                $stock_updated = true;
            }
            
            if (!empty($branch_ids)) {
                ProductStock::where('product_id' ,  $product->product_id)
                    ->where('option_id' , $option_id)
                    ->whereNotIn('branch_id', $branch_ids)
                    ->delete();
            }
                
            self::saveDailyApiLog('product_stock');
        }
        catch (\Exception $e) {
            self::saveDailyApiLog('product_stock');
            return $stock_updated;
        }

        return [
            'stock_updated' => $stock_updated,
            'branch_with_stocks' => $branch_with_stocks,
            'total_stock' => $total_stock
        ];
    }
}