<?php

namespace App\Helpers;

use GuzzleHttp\Client;

use App\Models\DailyApiLog;
use App\Models\AdminSetting;
use App\Models\ProductStock;
use App\Models\ProductOption;
use App\Models\InventoryLocation;

use App\Helpers\SettingHelper;
use App\Models\ApiErrorLog;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;

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
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password_2');

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


    // public static function sendRequest($method, $url, $body = [], $extra = [])
    // {
    //     $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
    //     $cin7_auth_password1 = SettingHelper::getSetting('cin7_auth_password');
    //     $cin7_auth_password2 = SettingHelper::getSetting('cin7_auth_password_2');

    //     $client = new \GuzzleHttp\Client();

    //     $useFirstCredentials = true;

    //     while (true) {
    //         try {

    //             $api_password = $useFirstCredentials ? $cin7_auth_password1 : $cin7_auth_password2;
    //             $credentials = [
    //                 'username' => $cin7_auth_username,
    //                 'password' =>$api_password,
    //             ];

    //             $authHeaders = [
    //                 'headers' => ['Content-type' => 'application/json'],
    //                 'auth' => [$credentials['username'], $credentials['password']]
    //             ];

    //             if (!empty($body)) {
    //                 $authHeaders['json'] = $body;
    //             }

    //             switch ($method) {
    //                 case 'POST':
    //                     $res = $client->post($url, $authHeaders);
    //                     break;
    //                 case 'PUT':
    //                     $res = $client->put($url, $authHeaders);
    //                     break;
    //                 case 'GET':
    //                     $res = $client->get($url, $authHeaders);
    //                     break;
    //                 default:
    //                     $res = $client->get($url, $authHeaders);
    //                     break;
    //             }

    //             if (!empty($extra['api_end_point'])) {
    //                 self::saveDailyApiLog($extra['api_end_point']);
    //             }

    //             $api_response = $res->getBody()->getContents();

    //             $update_master_key_attempt = AdminSetting::where('option_name', 'master_key_attempt')->first();
    //             if ($update_master_key_attempt) {
    //                 $update_master_key_attempt->option_value = 1;
    //                 $update_master_key_attempt->save();
    //             }

                
    //             return $api_response;

    //         } catch (\Exception $e) {
    //             // Log the error
    //             $errorlog = new ApiErrorLog();
    //             $errorlog->payload = $e->getMessage();
    //             $errorlog->exception = $e->getCode();
    //             $errorlog->save();

    //             // Update master_key_attempt to 0 on failure
    //             $master_key_attempt = AdminSetting::where('option_name', 'master_key_attempt')->first();
    //             if ($master_key_attempt) {
    //                 $master_key_attempt->option_value = 0;
    //                 $master_key_attempt->save();
    //             }

    //             // Swap credentials
    //             $useFirstCredentials = !$useFirstCredentials;

    //             // Optionally sleep before retrying to avoid rapid retries
    //             sleep(5);
    //         }
    //     }
    // }



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


    // swap keys
    // public static function updateProductStock($product, $option_id) {
    //     $setting = AdminSetting::where('option_name', 'check_product_stock')->first();
    //     $total_stock = 0;
    //     $stock_updated = false;
    //     $branch_with_stocks = [];

    //     if (empty($setting) || ($setting->option_value !=  'Yes')) {
    //         return $stock_updated;
    //     }

    //     $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
    //     $cin7_auth_password1 = SettingHelper::getSetting('cin7_auth_password');
    //     $cin7_auth_password2 = SettingHelper::getSetting('cin7_auth_password_2');

    //     $useFirstCredentials = true;
    //     while (true) {
    //         try {
    //             $api_password = $useFirstCredentials ? $cin7_auth_password1 : $cin7_auth_password2;
    //             $url = 'https://api.cin7.com/api/v1/Stock?where=productId=' . $product->product_id . '&productOptionId=' . $option_id;
    //             $client2 = new \GuzzleHttp\Client();
    //             $res = $client2->request(
    //                 'GET',
    //                 $url,
    //                 [
    //                     'auth' => [
    //                         $cin7_auth_username,
    //                         $api_password
    //                     ]
    //                 ]
    //             );

    //             $inventory = $res->getBody()->getContents();
    //             $location_inventories = json_decode($inventory);
    //             if (empty($location_inventories)) {
    //                 return [
    //                     'stock_updated' => $stock_updated,
    //                     'branch_with_stocks' => null,

    //                 ];
    //             }
    //             $inactive_inventory_locations = InventoryLocation::where('status', 0)->pluck('cin7_branch_id')->toArray();
                
    //             // $skip_branches = [172, 173, 174];
    //             $skip_branches = $inactive_inventory_locations;
    //             $branch_ids = [];
    //             foreach ($location_inventories as $location_inventory) {
    //                 if (in_array($location_inventory->branchId, $skip_branches)) {
    //                     continue;
    //                 }
    //                 $branch_with_stocks[] = [
    //                     'branch_id' => $location_inventory->branchId,
    //                     'branch_name' => $location_inventory->branchName,
    //                     'available' => $location_inventory->available
    //                 ];

                    
    //                 $product_stock = ProductStock::where('branch_id' , $location_inventory->branchId)
    //                     ->where('product_id' ,  $product->product_id)
    //                     ->where('option_id' , $option_id)
    //                     ->first();

                    
                    
    //                 if (!empty($product_stock)) {
    //                     $product_stock->available_stock = $location_inventory->available >=0 ? $location_inventory->available : 0;
    //                     $product_stock->save();
                        
    //                     $branch_ids[] = $location_inventory->branchId;
    //                     if (!in_array($location_inventory->branchId, $skip_branches)) {
    //                         $total_stock += $location_inventory->available >=0 ? $location_inventory->available : 0;
    //                     }
    //                 }
    //                 else {
    //                     $product_stock = ProductStock::create([
    //                         'available_stock' => $location_inventory->available >=0 ? $location_inventory->available : 0,
    //                         'branch_id' => $location_inventory->branchId,
    //                         'product_id' => $product->product_id,
    //                         'branch_name' => $location_inventory->branchName,
    //                         'option_id' => $option_id
    //                     ]);
    //                     if (!in_array($location_inventory->branchId, $skip_branches)) {
    //                         $total_stock += $location_inventory->available >=0 ? $location_inventory->available : 0;
    //                     }
    //                     // $total_stock += $product_stock->available_stock;
    //                 }

                    

    //                 $stock_updated = true;
    //             }
    //             $update_product_option = ProductOption::where('option_id' , $option_id)->first();
    //             if (!empty($update_product_option)) {
    //                 $update_product_option->stockAvailable = $total_stock;
    //                 $update_product_option->save();
    //             } 
                
    //             if (!empty($branch_ids)) {
    //                 ProductStock::where('product_id' ,  $product->product_id)
    //                     ->where('option_id' , $option_id)
    //                     ->whereNotIn('branch_id', $branch_ids)
    //                     ->delete();
    //             }
                    
    //             self::saveDailyApiLog('product_detail_update_stock');

    //             $update_master_key_attempt = AdminSetting::where('option_name', 'master_key_attempt')->first();
    //             if ($update_master_key_attempt) {
    //                 $update_master_key_attempt->option_value = 1;
    //                 $update_master_key_attempt->save();
    //             }

    //             break;
    //         }
    //         catch (\Exception $e) {
    //             // Update master_key_attempt to 0 on failure
    //             $master_key_attempt = AdminSetting::where('option_name', 'master_key_attempt')->first();
    //             if ($master_key_attempt) {
    //                 $master_key_attempt->option_value = 0;
    //                 $master_key_attempt->save();
    //             }
    //             // Swap credentials
    //             $useFirstCredentials = !$useFirstCredentials;
    //             self::saveDailyApiLog('product_detail_update_stock');
    //             return $stock_updated;
    //         }
    //     }

    //     return [
    //         'stock_updated' => $stock_updated,
    //         'branch_with_stocks' => $branch_with_stocks,
    //         'total_stock' => $total_stock
    //     ];
    // }


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
                    $product_stock->available_stock = $location_inventory->available >=0 ? $location_inventory->available : 0;
                    $product_stock->save();
                    
                    $branch_ids[] = $location_inventory->branchId;
                    if (!in_array($location_inventory->branchId, $skip_branches)) {
                        $total_stock += $location_inventory->available >=0 ? $location_inventory->available : 0;
                    }
                }
                else {
                    $product_stock = ProductStock::create([
                        'available_stock' => $location_inventory->available >=0 ? $location_inventory->available : 0,
                        'branch_id' => $location_inventory->branchId,
                        'product_id' => $product->product_id,
                        'branch_name' => $location_inventory->branchName,
                        'option_id' => $option_id
                    ]);
                    if (!in_array($location_inventory->branchId, $skip_branches)) {
                        $total_stock += $location_inventory->available >=0 ? $location_inventory->available : 0;
                    }
                    // $total_stock += $product_stock->available_stock;
                }

                

                $stock_updated = true;
            }
            $update_product_option = ProductOption::where('option_id' , $option_id)->first();
            if (!empty($update_product_option)) {
                $update_product_option->stockAvailable = $total_stock;
                $update_product_option->save();
            } 
            
            if (!empty($branch_ids)) {
                ProductStock::where('product_id' ,  $product->product_id)
                    ->where('option_id' , $option_id)
                    ->whereNotIn('branch_id', $branch_ids)
                    ->delete();
            }
                
            self::saveDailyApiLog('product_detail_update_stock');
        }
        catch (\Exception $e) {
            self::saveDailyApiLog('product_detail_update_stock');
            return $stock_updated;
        }

        return [
            'stock_updated' => $stock_updated,
            'branch_with_stocks' => $branch_with_stocks,
            'total_stock' => $total_stock
        ];
    }



    public static function update_product_stock_on_local($cart_items)
    {
        if (empty($cart_items)) {
            return false;
        }

        foreach ($cart_items as $cart_item) {
            $product_id = $cart_item['product_id'];
            $option_id = $cart_item['option_id'];
            $quantity = $cart_item['quantity'];
            
            $product_option = ProductOption::where('option_id', $option_id)
            ->where('product_id', $product_id)
            ->where('stockAvailable', '>', 0)
            ->first();

            $product_stock = ProductStock::where('product_id' ,  $product_id)
            ->where('option_id' , $option_id)
            ->first();

            if (!empty($product_option)) {
                $product_option->stockAvailable = !empty($product_option->stockAvailable) && intval($product_option->stockAvailable) > 0 ? intval($product_option->stockAvailable) - $quantity :  0;
                $product_option->save();
            }

            if (!empty($product_stock)) {
                $product_stock->available_stock = !empty($product_stock->available_stock) && intval($product_stock->available_stock) > 0 ? intval($product_stock->available_stock) - $quantity :  0;
                $product_stock->save();
            }
        }

        return true;
    }


    public static function cart_detail ($total_quantity , $grand_total) {
        $contact_id = Session::get('contact_id');
        $total = 0;
        $grand_total = 0;
        if (!auth()->user()) {
            $cart_items = Session::get('cart');
            if (!empty($cart_items)) {
                foreach ($cart_items as $cart) {
                    $total_q[] = $cart['quantity'];
                    $total_quantity = array_sum($total_q);
                    $total_price[] = $cart['price'] * $cart['quantity'];
                    $grand_total = array_sum($total_price);
                }
            }
        } else {
            $cart_items = Cart::where('contact_id', $contact_id)
            ->where('user_id' , auth()->user()->id)
            ->get();
            if (count($cart_items) > 0) {
                foreach ($cart_items as $cart) {
                    $total_q[] = $cart->quantity;
                    $total_quantity = array_sum($total_q);
                    $total_price[] = $cart->price * $cart->quantity;
                    $grand_total = array_sum($total_price);
                }
            }
        }
    
        return [
            'total_quantity' => $total_quantity,
            'grand_total' => $grand_total
        ];

    }
}