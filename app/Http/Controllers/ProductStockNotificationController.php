<?php

namespace App\Http\Controllers;

use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use Illuminate\Http\Request;
use App\Models\ProductStockNotification;
use App\Models\Product;
use App\Models\ProductStockNotificationAlternative;
use App\Models\User;
use App\Models\SpecificAdminNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ProductStockNotificationController extends Controller
{
    public function notify_user_about_product_stock(Request $request) {
        $email = null;
        $status = false;
        if (auth()->user()) {
            $email = auth()->user()->email;
        } else {
            $email = $request->email;
        }

        $request->validate(
            [
                'email' => 'required',
            ]
        );

        $product_id = $request->product_id;
        $sku = $request->sku;
        $email = $request->email;
        $check_product_stock_notification = ProductStockNotification::where('product_id', $product_id)->where('sku', $sku)->where('email', $email)->first();
        if (!empty($check_product_stock_notification)) {
            return response()->json([
                'message' => 'You have already requested for this product stock notification.',
                'status' => true
            ]);
        }
        $product_stock_notification = ProductStockNotification::create([
            'product_id' => $product_id,
            'sku' => $sku,
            'email' => $email,
        ]);

        if ($product_stock_notification == true) {
            $data = [
                'product' => $product_stock_notification->product,
                'product_options' => $product_stock_notification->product->product_options,
                'email' => $email,
                'subject' => 'Product Stock Request',
                'from' => SettingHelper::getSetting('noreply_email_address')
            ];
            $specific_admin_notifications = SpecificAdminNotification::all();
            foreach ($specific_admin_notifications as $specific_admin_notification) {
                $data['email'] = $specific_admin_notification->email;
                $mail = MailHelper::stockMailNotification('emails.admin-stock-notification', $data);
            }
            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'message' => 'You will be notified when the product is back in stock.',
            'product_stock_notification' => $product_stock_notification,
            'status' => $status
        ]);
    }

    public function search_alternate_products(Request $request) {
        $searchvalue = $request->search;
        $replace_special_characters = preg_replace('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', ' ', $searchvalue);
        $explode_search_value = explode(' ', $replace_special_characters);
        $pricing = 'RetailUSD';
        $products = Product::with(['product_views','apiorderItem' , 'options' => function ($q) {
            $q->where('status', '!=', 'Disabled');
        }])
        ->where(function (Builder $query) use ($explode_search_value) {
            foreach ($explode_search_value as $searchvalue) {
                $query->where('name', 'LIKE', '%' . $searchvalue . '%')
                ->where('status', '!=', 'Inactive');
            }
        })
        ->orWhere(function (Builder $query) use ($searchvalue) {
            $query->where('code', 'LIKE', '%' . $searchvalue . '%')
            ->where('status', '!=', 'Inactive');
        })
        ->orWhereExists(function ($q) use ($searchvalue) {
            $q->select(DB::raw(1))
            ->from('product_options')
            ->whereColumn('products.product_id', 'product_options.product_id')
            ->where('code',  $searchvalue )
            ->where('status', '!=', 'Disabled');
        })
        ->where('status', '!=', 'Inactive')
        ->whereHas('options.defaultPrice' , function($q) use ($pricing){
            $q->where($pricing , '>' , 0)->where($pricing , '!=' , null);
        })
        ->get();

        return response()->json([
            'products' => $products,
            'success' => true
        ]);

    }

    public function add_alternative_product(Request $request) {
        $product_ids = $request->product_ids;
        $product_stock_notification_id = $request->product_stock_notification_id;
        $get_user_email = ProductStockNotification::with('product')->where('id', $product_stock_notification_id)->first();

        try {
            foreach ($product_ids as $product_id) {
                $check_product_stock_notification_alternative = ProductStockNotificationAlternative::where('product_id', $product_id)->where('product_stock_notification_id', $product_stock_notification_id)->first();
                if (!empty($check_product_stock_notification_alternative)) {
                    return response()->json([
                        'message' => 'You have already added this product as an alternative.',
                        'status' => true
                    ]);
                }

                $product_stock_notification_alternative = new ProductStockNotificationAlternative();
                $product_stock_notification_alternative->product_id = $product_id;
                $product_stock_notification_alternative->product_stock_notification_id = $product_stock_notification_id;
                $product_stock_notification_alternative->save();

                $user_notify = ProductStockNotification::with('product')->where('email', $get_user_email->email)->where('product_id', $product_id)->first();
                $find_product = Product::with('options')->where('product_id', $product_id)->first();
                if (empty($user_notify)) {
                    $product_stock_notification = ProductStockNotification::create([
                        'product_id' => $find_product->id,
                        'sku' => $find_product->code,
                        'email' => $get_user_email->email,
                    ]);
                }

                $data = [
                    'product' => $find_product,
                    'product_options' => $find_product->product_options,
                    'email' => $get_user_email->email,
                    'subject' => 'Product Stock Notification',
                    'request_title' => $get_user_email->product->name . ' is out of stock. Try this Instead !',
                    'from' => SettingHelper::getSetting('noreply_email_address')
                ];

                $mail = MailHelper::stockMailNotification('emails.user-stock-notification', $data);

                if ($mail) {
                    $product_stock_notification->is_notified = 1;
                    $product_stock_notification->status = 1;
                    $product_stock_notification->save();
                }
            }

            return response()->json([
                'message' => 'Product added as an alternative successfully to the user and user notified.',
                'status' => true
            ]);
        }
        catch (\Exception $e) 
        {
            return response()->json([
                'message' => 'Something went wrong.',
                'status' => false
            ]);
        }
    
    }

    public function alternate_products_history (Request $request) {
        $product_stock_notification_id = $request->product_stock_notification_id;
        $product_stock_notification_alternatives = ProductStockNotificationAlternative::with('product', 'productStockNotification')->where('product_stock_notification_id', $product_stock_notification_id)->get();
        return response()->json([
            'product_stock_notification_alternatives' => $product_stock_notification_alternatives,
            'status' => true
        ]);
    }

    public function notify_users_from_alternate_history(Request $request) {
        $product_id = $request->product_id;
        $sku = $request->sku;
        $email = $request->email;
        $check_product_stock_notification = ProductStockNotification::with('product')->where('product_id', $product_id)->where('sku', $sku)->where('email', $email)->first();
        if (!empty($check_product_stock_notification)) {
            return response()->json([
                'message' => 'User already requested for this product stock notification.',
                'status' => true
            ]);
        }
        $product_stock_notification = ProductStockNotification::create([
            'product_id' => $product_id,
            'sku' => $sku,
            'email' => $email,
            'status' => 1,
            'is_notified' => 1
        ]);
        $product_stock_notification_users = ProductStockNotification::with('product' , 'product.options')
        ->where('id', $product_stock_notification->id)
        ->first();
        if ($product_stock_notification_users) {
            
            $data = [
                'email' => $product_stock_notification_users->email,
                'product' => $product_stock_notification_users->product,
                'product_options' => $product_stock_notification_users->product->options,
                'subject' => 'Product Stock Notification',
                'request_title' => 'The product you requested is out of stock but we have an alternative for you. Please check the product below',
                'from' => SettingHelper::getSetting('noreply_email_address')
            ];

            $mail = MailHelper::stockMailNotification('emails.user-stock-notification', $data);
            if ($mail) {
                $product_stock_notification->is_notified = 1;
                $product_stock_notification->status = 1;
                $product_stock_notification->save();
                return response()->json([
                    'message' => 'User notified successfully.',
                    'product_stock_notification' => $product_stock_notification,
                    'status' => true
                ]);
            } else {
                return response()->json([
                    'message' => 'Something went wrong.',
                    'status' => false
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Product not found.',
                'status' => false
            ]);
        }

        
    }
}
