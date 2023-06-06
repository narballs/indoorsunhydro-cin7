<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApiOrder;
use App\Models\ApiOrderItem;

class SyncOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Sync:Orders';

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
        $total_order_pages = 3;
        $client = new \GuzzleHttp\Client();
        // for ($i = 1; $i <= $total_order_pages; $i++) {
            //$this->info('Processing page#' . $i);

            $res = $client->request(
                'GET',
                //'https://api.cin7.com/api/v1/SalesOrders/?page=' . $i,
                "https://api.cin7.com/api/v1/SalesOrders?where=status='Void'",
                [
                    'auth' => [
                        env('API_USER'),
                        env('API_PASSWORD')
                    ]
                ]
            );
            $api_orders = $res->getBody()->getContents();
            //dd($api_orders);
            $api_orders = json_decode($api_orders);
            

            if (!empty($api_orders)) {
                foreach ($api_orders as $api_order) {
                    $qcom_order = ApiOrder::where('reference', $api_order->reference)->first();
                    //dd($qcom_order);
                    if ($qcom_order) {

                        $this->info('Api order ids' . $qcom_order->reference);
                        $qcom_order->isVoid = 1;
                        $qcom_order->isApproved = 0;
                        $qcom_order->save();
                    }
          
                }
            }
        // }
    }
}