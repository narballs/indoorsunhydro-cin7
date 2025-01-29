<?php

namespace App\Console\Commands;

use App\Models\AdminSetting;
use App\Models\ApiOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MarkShipped extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mark:order_shipped';

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
        $auto_mark_order_shipped = AdminSetting::where('option_name', 'auto_create_label')->first();

        if (!empty($auto_mark_order_shipped) && strtolower($auto_mark_order_shipped->option_value) == 'no') {
            Log::info('Auto Mark Order Shipped is disabled');
            return;
        }

        $client = new \GuzzleHttp\Client();

        $all_orders = ApiOrder::where('is_shipped', 0)
            ->where('label_created', 0)
            ->where('is_stripe', 1)
            ->where('shipment_price', '>', 0)
            ->whereNotNull('shipstation_orderId')
            ->where('payment_status', 'paid')
            ->where('isApproved', 1)
            ->where('created_at', '>=', '2025-01-09 12:23:51')
            ->where('shipping_carrier_code', 'ups_walleted')
            ->get();
    }
}
