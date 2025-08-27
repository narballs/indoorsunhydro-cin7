<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\ApiOrder;
use App\Jobs\SalesOrders;
use App\Models\ApiOrderItem;
use App\Models\AdminSetting;



use App\Helpers\OrderHelper;
use App\Models\Contact;

class AutoOrdersSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AutoOrder:Sync {--minutes=3}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle() {
        
        $option = AdminSetting::where('option_name', 'auto_full_fill')->first();
        if ($option->option_value == 0) {
            return;
        }

        $disable_stripe_fullfull_over_1k = AdminSetting::where('option_name', 'disable_stripe_fullfull_over_1k')->first();

        $minutes = $this->option('minutes');

        $execution_time = date('Y-m-d H:i:s', strtotime('-' . $minutes . ' minutes'));

        $this->error('Execution Time: ' . $minutes . ' minutes ago => ' . $execution_time);

        $this->info('---------------------------------------------------------');

        $orders = ApiOrder::where('created_at', '<', $execution_time)
            ->where('order_id', null)
            ->where('isApproved', '<>', 2)
            ->with('user.contact')
            ->with('texClasses')
            ->get();
            
        if (empty($orders)) {
            $this->info('There are no orders to process.');
            return false;
        }

        foreach ($orders as $order) {
            if (empty($order->memberId)) {
                $this->info('Contact not found for user: ' . $order->memberId);
                continue;
            }

            $contact = Contact::where('contact_id', $order->memberId)->first();

            // if (!empty($contact) && $contact->is_test_user == 0) {
            //     if (!empty($order->is_stripe) && $order->is_stripe == 1 ) {
            //         if (strtolower($order->payment_status) === 'paid') {
            //             $order_data = OrderHelper::get_order_data_to_process($order);
            //             SalesOrders::dispatch('create_order', $order_data)->onQueue(env('QUEUE_NAME'));
            //             sleep(20);
            //         }
            //     } else {
            //         $order_data = OrderHelper::get_order_data_to_process($order);
            //         SalesOrders::dispatch('create_order', $order_data)->onQueue(env('QUEUE_NAME'));
            //         sleep(20);
            //     }
            // }


            $disableStripe = strtolower($disable_stripe_fullfull_over_1k->option_value) === 'yes';
            $orderTotal    = floatval($order->total_including_tax);

            if (!empty($contact) && $contact->is_test_user == 0) {
                $this->info('Order Date ' . $order->created_at);

                if (!empty($order->is_stripe) && $order->is_stripe == 1) {
                    if (strtolower($order->payment_status) === 'paid') {
                        // process only if not disabled OR under limit
                        if (!$disableStripe || $orderTotal <= 1000) {
                            $order_data = OrderHelper::get_order_data_to_process($order);
                            SalesOrders::dispatch('create_order', $order_data)->onQueue(env('QUEUE_NAME'));
                            sleep(20);
                        } else {
                            $this->warn("Stripe order above 1k blocked: {$order->id}");
                            continue;
                        }
                    }
                } else {
                    // Non-stripe / wholesale always go through
                    $order_data = OrderHelper::get_order_data_to_process($order);
                    SalesOrders::dispatch('create_order', $order_data)->onQueue(env('QUEUE_NAME'));
                    sleep(20);
                }
            }
        }

        $this->info('Finished.');

    }
}
