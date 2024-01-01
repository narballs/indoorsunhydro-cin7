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
    protected $description = 'Check thr order of the status if they are not fullfilled';

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
    public function handle()
    {
        // $option = AdminSetting::where('option_name', 'auto_full_fill')->first();
        // if ($option->option_value == 0) {
        //     return;
        // }

        

        // $this->info('---------------------------------------------------------');

        $pending_orders = ApiOrder::with(['createdby', 'processedby', 'contact'])
        ->where('order_id' , null)
        ->where('isApproved' , 0)
        // ->whereBetween('created_at', [Carbon::now()->subHours(3), Carbon::now()])
        ->where('created_at', '<', now()->subHours(3))
        ->orderBy('id' , 'Desc')->get();

        if (count($pending_orders) > 0) {
            $this->info(count($pending_orders) . ' => Pending Orders Found');
            $admin_users = DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');

            $admin_users = $admin_users->toArray();

            $users_with_role_admin = User::select("email")
                ->whereIn('id', $admin_users)
                ->get();
            $get_order_ids = [];
            foreach ($pending_orders as $order) {
                $get_order_ids[] = $order->id;
            }
            $order_ids = implode(',', $get_order_ids);
            $data = [
                'orders' => $pending_orders,
                'count_orders' => count($pending_orders),
                'name' =>  'Admin',
                'order_ids' => $order_ids,
                'email' => '',
                'contact_email' => '',
                'subject' => 'Pending ' .' '. 'Orders',
                'from' => SettingHelper::getSetting('noreply_email_address'),
            ];    
            if (!empty($users_with_role_admin)) {
                foreach ($users_with_role_admin as $role_admin) {
                    $subject = 'Orders Not Fullfilled';
                    $adminTemplate = 'emails.orders-not-fullfilled';
                    $data['email'] = $role_admin->email;
                    MailHelper::sendMailNotification('emails.orders-not-fullfilled', $data);
                }
            }
        }
    }
}
