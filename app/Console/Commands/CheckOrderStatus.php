<?php

namespace App\Console\Commands;

use App\Helpers\MailHelper;
use App\Helpers\OrderHelper;
use App\Helpers\SettingHelper;
use App\Http\Controllers\Admin\OrderManagementController;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\ApiOrder;
use App\Jobs\SalesOrders;
use App\Models\ApiOrderItem;
use App\Models\AdminSetting;
use App\Models\SpecificAdminNotification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CheckOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:orderstatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the order of the status if they are not fullfilled';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    // public function handle()
    // {
        
    //     $pending_orders = ApiOrder::with(['createdby', 'processedby', 'contact'])
    //     ->where('order_id' , null)
    //     ->where('isApproved' , 0)
    //     ->where('payment_status' , '!=' , 'unpaid')
    //     ->where('payment_status' , '!=' , 'pending')
    //     ->where('created_at', '<', now()->subHours(3))
    //     ->orderBy('id' , 'Desc')->get();

    //     if (count($pending_orders) > 0) {
    //         $this->info(count($pending_orders) . ' => Pending Orders Found');
    //         $get_order_ids = [];
    //         foreach ($pending_orders as $order) {
    //             $get_order_ids[] = $order->id;
    //         }
    //         $order_ids = implode(',', $get_order_ids);
    //         $data = [
    //             'orders' => $pending_orders,
    //             'count_orders' => count($pending_orders),
    //             'name' =>  'Admin',
    //             'order_ids' => $order_ids,
    //             'email' => '',
    //             'contact_email' => '',
    //             'subject' => 'Pending ' .' '. 'Orders',
    //             'from' => SettingHelper::getSetting('noreply_email_address'),
    //         ];    
            
    //         $specific_admin_notifications = SpecificAdminNotification::all();
    //         if (count($specific_admin_notifications) > 0) {
    //             foreach ($specific_admin_notifications as $specific_admin_notification) {
    //                 $subject = 'Orders Not Fullfilled';
    //                 $adminTemplate = 'emails.orders-not-fullfilled';
    //                 $data['email'] = $specific_admin_notification->email;

    //                 MailHelper::sendMailNotification('emails.orders-not-fullfilled', $data);
    //             }
    //         }
    //     }
    // }


    public function handle() {
        $pending_orders = ApiOrder::with(['createdby', 'processedby', 'contact'])
            ->whereNull('order_id')
            ->where('isApproved', 0)
            ->whereNotIn('payment_status', ['unpaid', 'pending'])
            ->where('created_at', '<', now()->subHours(3))
            ->orderBy('id', 'desc')
            ->get();

        if (count($pending_orders) > 0) {
            $this->info(count($pending_orders) . ' => Pending Orders Found');
            
            $get_order_ids = [];
            foreach ($pending_orders as $order) {
                $get_order_ids[] = $order->id;
            }
            $order_ids = implode(',', $get_order_ids);

            $data = [
                'orders' => $pending_orders,
                'count_orders' => count($pending_orders),
                'name' => 'Admin',
                'order_ids' => $order_ids,
                'email' => '', // will be set below
                'contact_email' => '',
                'subject' => 'Pending Orders',
                'from' => SettingHelper::getSetting('noreply_email_address'),
            ];

            
            $specific_admin_notifications = SpecificAdminNotification::all();
            if ($specific_admin_notifications->isNotEmpty()) {
                foreach ($specific_admin_notifications as $specific_admin_notification) {
                    // Check if this admin should receive order notifications
                    if (!$specific_admin_notification->recieve_order_notification) {
                        continue;
                    }

                    $data['email'] = $specific_admin_notification->email;
                    MailHelper::sendMailNotification('emails.orders-not-fullfilled', $data);
                }
            }

            
        }
    }


}
