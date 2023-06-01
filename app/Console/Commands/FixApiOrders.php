<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\ApiOrder;
use App\Models\ApiOrderItem;

class FixApiOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:api_orders';

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
    public function handle()
    {
        
        $api_orders = ApiOrder::where('reference', 'like', '%QCOM%')->get();

        $order_ids = [];

        if (!empty($api_orders)) {
            foreach ($api_orders as $api_order) {

                $order_ids[$api_order->id] = $api_order->id;

                $this->info($api_order->id);
            }
        }

        if (!empty($order_ids)) {
            $other_api_orders = ApiOrder::whereNotIn('id', $order_ids)->get();

            if (!empty($other_api_orders)) {
                foreach ($other_api_orders as $other_order) {
                    $api_order_items = ApiOrderItem::where('order_id', $other_order->order_id)->get();

                    if (!empty($api_order_items)) {
                        foreach ($api_order_items as $api_order_item) {
                            $api_order_item->delete();
                        }
                    }

                    $other_order->delete();
                }
            }

        }

        $other_api_orders = ApiOrder::where('created_at', '2023-05-31 00:35:08')->where('updated_at', '2023-05-31 00:35:08')->get();
        if (!empty($other_api_orders)) {
            foreach ($other_api_orders as $other_order) {
                $api_order_items = ApiOrderItem::where('order_id', $other_order->order_id)->get();

                if (!empty($api_order_items)) {
                    foreach ($api_order_items as $api_order_item) {
                        $api_order_item->delete();
                    }
                }

                $other_order->delete();
            }
        }

        $other_api_orders = ApiOrder::where('created_at', '2023-05-31 00:35:09')->where('updated_at', '2023-05-31 00:35:09')->get();
        if (!empty($other_api_orders)) {
            foreach ($other_api_orders as $other_order) {
                $api_order_items = ApiOrderItem::where('order_id', $other_order->order_id)->get();

                if (!empty($api_order_items)) {
                    foreach ($api_order_items as $api_order_item) {
                        $api_order_item->delete();
                    }
                }

                $other_order->delete();
            }
        }

        $other_api_orders = ApiOrder::where('created_at', '2023-05-31 09:35:09')->where('updated_at', '2023-05-31 09:35:09')->get();
        if (!empty($other_api_orders)) {
            foreach ($other_api_orders as $other_order) {
                $api_order_items = ApiOrderItem::where('order_id', $other_order->order_id)->get();

                if (!empty($api_order_items)) {
                    foreach ($api_order_items as $api_order_item) {
                        $api_order_item->delete();
                    }
                }

                $other_order->delete();
            }
        }

    }
}
