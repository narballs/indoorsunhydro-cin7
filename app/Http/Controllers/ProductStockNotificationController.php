<?php

namespace App\Http\Controllers;

use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use Illuminate\Http\Request;
use App\Models\ProductStockNotification;
use App\Models\Product;
use App\Models\User;
use App\Models\SpecificAdminNotification;
use Illuminate\Support\Facades\DB;

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
}
