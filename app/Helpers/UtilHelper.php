<?php

namespace App\Helpers;

use GuzzleHttp\Client;

use App\Models\DailyApiLog;
use App\Models\AdminSetting;
use App\Models\ProductStock;
use App\Models\ProductOption;
use App\Models\InventoryLocation;

use App\Helpers\SettingHelper;
use App\Models\ApiEndpointRequest;
use App\Models\ApiErrorLog;
use App\Models\ApiKeys;
use App\Models\Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
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


        $cin7api_key_for_other_jobs =  ApiKeys::where('password', $cin7_auth_password)
        ->where('is_active', 1)
        ->first();
        
        if (!empty($cin7api_key_for_other_jobs)) {
            $cin7_auth_username = $cin7api_key_for_other_jobs->username;
            $cin7_auth_password = $cin7api_key_for_other_jobs->password;
            $thresold = $cin7api_key_for_other_jobs->threshold;
            $request_count = !empty($cin7api_key_for_other_jobs->request_count) ? $cin7api_key_for_other_jobs->request_count : 0;
            $api_key_id = $cin7api_key_for_other_jobs->id;
        } else {
            Log::info('No active api key found');
            return false;
        }

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
            self::saveEndpointRequestLog('Sync Order',$extra['api_end_point'], $api_key_id);
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


    // public static function saveEndpointRequestLog($title, $url, $api_key_id) {
    //     $todayStart = Carbon::today()->startOfDay();
    //     $todayEnd = Carbon::today()->endOfDay();

    //     // Check if there is an entry for today
    //     $daily_endpoint_request_count = ApiEndpointRequest::where('api_key_id', $api_key_id)
    //     ->where('title', $title)
    //     ->whereBetween('created_at', [$todayStart, $todayEnd])
    //     ->first();
        
    //     if ($daily_endpoint_request_count) {
    //         $daily_endpoint_request_count->increment('request_count');
    //     } else {
    //         ApiEndpointRequest::create([
    //             'api_key_id' => $api_key_id,
    //             'url' => $url,
    //             'title' => $title,
    //             'request_count' => 1,
    //             'created_at' => Carbon::now(),
    //         ]);
    //     }

    //     $total_requests_today = ApiEndpointRequest::where('api_key_id', $api_key_id)
    //         ->where('created_at', '>=', $todayStart)
    //         ->sum('request_count');

    //     // Update the request count in the `ApiKeys` model
    //     $apiKey = ApiKeys::where('id', $api_key_id)->where('is_active', 1)->first();
    //     if ($apiKey) {
    //         $apiKey->update([
    //             'request_count' => $total_requests_today
    //         ]);
    //     }
    
    //     return true;
    // }

    public static function saveEndpointRequestLog($title, $url, $api_key_id) {
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();
    
        // Find today's request log for the given API key and title
        $dailyRequest = ApiEndpointRequest::firstOrNew([
            'api_key_id' => $api_key_id,
            'title' => $title,
        ], [
            'url' => $url,
            'request_count' => 0, 
            'created_at' => Carbon::now()
        ]);
    
        // Increment request count or initialize it
        $dailyRequest->request_count += 1;
        $dailyRequest->save();
    
        // Calculate today's total requests for the API key
        $totalRequestsToday = ApiEndpointRequest::where('api_key_id', $api_key_id)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('request_count');
    
        // Update the request count in the `ApiKeys` table if key is active
        ApiKeys::where('id', $api_key_id)->where('is_active', 1)
            ->update(['request_count' => $totalRequestsToday]);
    
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

        $cin7api_key_for_other_jobs =  ApiKeys::where('password', $cin7_auth_password)
        ->where('is_active', 1)
        ->first();
        
        if (!empty($cin7api_key_for_other_jobs)) {
            $cin7_auth_username = $cin7api_key_for_other_jobs->username;
            $cin7_auth_password = $cin7api_key_for_other_jobs->password;
            $thresold = $cin7api_key_for_other_jobs->threshold;
            $request_count = !empty($cin7api_key_for_other_jobs->request_count) ? $cin7api_key_for_other_jobs->request_count : 0;
            $api_key_id = $cin7api_key_for_other_jobs->id;
        } else {
            Log::info('No active api key found');
            return false;
        }

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


            self::saveEndpointRequestLog('Sync Stock Detail','https://api.cin7.com/api/v1/Stock', $api_key_id);

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


    public static function update_product_stock_on_cancellation($order)
    {
        
        if (empty($order)) {
            return false;
        }

        if (count($order->apiOrderItem) == 0) {
            return false;
        }

        foreach ($order->apiOrderItem as $order_item) {
            $product_id = $order_item->product_id;
            $option_id = $order_item->option_id;
            $quantity = intval($order_item->quantity);
            
            $product_option = ProductOption::where('option_id', $option_id)
            ->where('product_id', $product_id)
            ->first();

            $product_stock = ProductStock::where('product_id' ,  $product_id)
            ->where('option_id' , $option_id)
            ->first();

            if (!empty($product_option)) {
                $product_option->stockAvailable = intval($product_option->stockAvailable) + $quantity;
                $product_option->save();
            }

            if (!empty($product_stock)) {
                $product_option->available_stock = intval($product_option->available_stock) + $quantity;
                $product_stock->save();
            }
        }

        return true;
    }


    // public static function cart_detail ($total_quantity , $grand_total) {
    //     $company = session()->get('company');
    //     $contact_id = session()->get('contact_id');
    //     $total = 0;
    //     $grand_total = 0;
    //     if (!auth()->user()) {
    //         $cart_items = Session::get('cart');
    //         if (!empty($cart_items)) {
    //             foreach ($cart_items as $cart) {
    //                 $total_q[] = $cart['quantity'];
    //                 $total_quantity = array_sum($total_q);
    //                 $total_price[] = $cart['price'] * $cart['quantity'];
    //                 $grand_total = array_sum($total_price);
    //             }
    //         }
    //     } else {
    //         if (empty($company) || (!$company) && (!empty(!auth()->user()))) {
    //             $cart_items = Cart::where('user_id' , auth()->user()->id)
    //             ->get();
    //             if (count($cart_items) > 0) {
    //                 foreach ($cart_items as $cart) {
    //                     $total_q[] = $cart->quantity;
    //                     $total_quantity = array_sum($total_q);
    //                     $total_price[] = $cart->price * $cart->quantity;
    //                     $grand_total = array_sum($total_price);
    //                 }
    //             }
    //         }
    //         else {
    //             $cart_items = Cart::where('contact_id', $contact_id)
    //             ->where('user_id' , auth()->user()->id)
    //             ->get();
    //             if (count($cart_items) > 0) {
    //                 foreach ($cart_items as $cart) {
    //                     $total_q[] = $cart->quantity;
    //                     $total_quantity = array_sum($total_q);
    //                     $total_price[] = $cart->price * $cart->quantity;
    //                     $grand_total = array_sum($total_price);
    //                 }
    //             }
    //         }
    //     }
    
    //     return [
    //         'total_quantity' => $total_quantity,
    //         'grand_total' => $grand_total
    //     ];

    // }

    public static function cart_detail()
{
    $company = session()->get('company');
    $contact_id = session()->get('contact_id');
    $total_quantity = 0;
    $grand_total = 0;
    $total_q = [];
    $total_price = [];

    // Handle unauthenticated (guest) users with session-based cart
    if (!auth()->check()) {
        $cart_items = Session::get('cart', []); // Default to empty array if session cart is missing
        if (!empty($cart_items)) {
            foreach ($cart_items as $cart) {
                $total_q[] = $cart['quantity'];
                $total_price[] = $cart['price'] * $cart['quantity'];
            }
            // Calculate totals after the loop
            $total_quantity = array_sum($total_q);
            $grand_total = array_sum($total_price);
        }
    }
    // Handle authenticated users with database-based cart
    else {
        $query = Cart::where('user_id', auth()->user()->id);

        // If the user has a company (contact_id), filter by it
        if (!empty($contact_id)) {
            $query->where('contact_id', $contact_id);
        }

        // Retrieve cart items
        $cart_items = $query->get();

        if ($cart_items->isNotEmpty()) {
            foreach ($cart_items as $cart) {
                $total_q[] = $cart->quantity;
                $total_price[] = $cart->price * $cart->quantity;
            }
            // Calculate totals after the loop
            $total_quantity = array_sum($total_q);
            $grand_total = array_sum($total_price);
        }
    }

    // Return the cart details
    return [
        'total_quantity' => $total_quantity,
        'grand_total' => $grand_total,
    ];
}

}