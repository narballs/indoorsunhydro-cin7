<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\MailHelper;
use App\Helpers\OrderHelper;
use App\Helpers\SettingHelper;
use App\Http\Controllers\Admin\OrderManagementController;
use Carbon\Carbon;
use App\Models\ApiOrder;
use App\Jobs\SalesOrders;
use App\Models\ApiOrderItem;
use App\Models\AdminSetting;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class CancelOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'if an order is left unpaid for 2hrs, auto cancel and send them cancel email template.';

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
        
        $pending_orders = ApiOrder::where('isApproved' , 0)
        ->where('is_stripe' ,'=', 1)
        ->where('payment_status' , '=' , 'unpaid')
        ->where('created_at', '<', now()->subHours(2))
        ->get();
        $best_products = Product::where('status', '!=', 'Inactive')->orderBy('views', 'DESC')->limit(4)->get();
        if (count($pending_orders) > 0) {
            foreach ($pending_orders as $pending_order) {
                $pending_order->isApproved = 2;
                $pending_order->save();
                $order_items = ApiOrderItem::with('order.texClasses', 'product.options')->where('order_id', $pending_order->id)->get();
                $count = $order_items->count();
                $customer = ApiOrder::with(['createdby'])->where('memberId', $pending_order->memberId)
                ->with('contact' , function($query) {
                    $query->orderBy('company');
                })
                ->with('apiOrderItem.product')
                ->where('id' , $pending_order->id)
                ->first();
                $currentOrder = ApiOrder::where('id', $pending_order->id)->with(
                    'contact',
                    'user.contact',
                    'apiOrderItem.product.options',
                    'texClasses'
                )->first();
                $addresses = [
                    'billing_address' => [
                        'firstName' => $customer->contact->firstName,
                        'lastName' => $customer->contact->lastName,
                        'address1' => $customer->contact->address1,
                        'address2' => $customer->contact->address2,
                        'city' => $customer->contact->city,
                        'state' => $customer->contact->state,
                        'zip' => $customer->contact->postCode,
                        'mobile' => $customer->contact->mobile,
                        'phone' => $customer->contact->phone,
                    ],
                    'shipping_address' => [
                        'postalAddress1' => $customer->contact->postalAddress1,
                        'postalAddress2' => $customer->contact->postalAddress2,
                        'phone' => $customer->contact->postalCity,
                        'postalCity' => $customer->contact->postalState,
                        'postalState' => $customer->contact->postalPostCode,
                        'postalPostCode' => $customer->contact->postalPostCode
                    ],
                    'payment_terms' => !empty($customer->contact->payment_terms) ? $customer->contact->payment_terms : '30 Days from Invoice',
                    'best_product' => $best_products,
                    'user_email' =>   $customer->contact->email,
                    'currentOrder' => $currentOrder,
                    'count' => $count,
                    'order_id' => $pending_order->order_id,
                    'company' => $currentOrder->contact->company, 
                    'order_status' => 'updated',
                    'delievery_method' => $currentOrder->logisticsCarrier,
                    'new_order_status' =>'Cancelled',
                    'previous_order_status' => 'Cancelled',
                ];
                $name = $customer->contact->firstName;
                $email =  $customer->contact->email;
                $reference  =  $currentOrder->reference;
                $data = [
                    'name' =>  $name,
                    'email' => $email,
                    'subject' => 'Order Cancelled',
                    'reference' => $reference,
                    'order_items' => $order_items,
                    'dateCreated' => Carbon::now(),
                    'addresses' => $addresses,
                    'best_product' => $best_products,
                    'user_email' => $email,
                    'currentOrder' => $currentOrder,
                    'count' => $count,
                    'from' => SettingHelper::getSetting('noreply_email_address')
                ];
                if (!empty($email)) {
                    $data['subject'] = 'Your Indoorsun Hydro order' .'#'.$currentOrder->id. ' ' .'status has been Cancelled';
                    $data['email'] = $email;
                    MailHelper::sendMailNotification('emails.cancel_order_email_template', $data);
                }    
                // if (!empty($users_with_role_admin)) {
                //     foreach ($users_with_role_admin as $role_admin) {
                //         $subject = 'Orders Not Fullfilled';
                //         $adminTemplate = 'emails.orders-not-fullfilled';
                //         $data['email'] = $role_admin->email;
                //         MailHelper::sendMailNotification('emails.orders-not-fullfilled', $data);
                //     }
                // }

            }
            $this->info('Order Cancelled');
            
        }
    }
}
