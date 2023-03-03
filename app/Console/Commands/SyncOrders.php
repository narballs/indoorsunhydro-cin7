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
        for ($i = 1; $i <= $total_order_pages; $i++) {
            $this->info('Processing page#' . $i);

            $res = $client->request(
                'GET', 
                //'https://api.cin7.com/api/v1/SalesOrders/?page=' . $i,
                'https://api.cin7.com/api/v1/SalesOrders/', 
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
            foreach($api_orders as $api_order) {
                //dd($api_order->lineItems);
                $this->info($api_order->id);
                $this->info('---------------------------------------');
                $this->info('Processing orders ' . $api_order->firstName);
                $this->info('---------------------------------------');
                $order_data = [
                    'order_id' => $api_order->id,
                    'createdDate' => '2022-07-30T16:41:09',
                    'modifiedDate' => '2022-07-30T16:41:09',
                    'createdBy' => $api_order->createdBy,
                    'processedBy' => $api_order->processedBy,
                    'isApproved' => $api_order->isApproved,
                    'reference'  => $api_order->reference,
                    'memberId' => $api_order->memberId,
                    'branchId' => $api_order->branchId,
                    'branchEmail' => $api_order->branchEmail,
                    'productTotal' => $api_order->productTotal,
                    'total' => $api_order->total,
                    'currencyCode' => $api_order->currencyCode,
                    'currencyRate' => $api_order->currencyRate,
                    'currencySymbol' => $api_order->currencySymbol,
                    'status' => $api_order->status,
                    'stage' => $api_order->stage,
                    'paymentTerms' => $api_order->paymentTerms
                ];


                foreach($api_order->lineItems as $lineItem) {
                    $this->info($lineItem->code);
                    $item_data = [
                        'order_id' => $api_order->id,
                        'product_id' => $lineItem->productId,
                        'quantity' => $lineItem->qty,
                        'price' => $lineItem->unitPrice,
                    
                    ];
                    $items = ApiOrderItem::firstOrCreate(
                        [
                            'id' => $lineItem->id,
                        ],
                    $item_data  
                    );
                }
                //exit;
                $order = ApiOrder::firstOrCreate(
                    [
                        'id' => $api_order->id,
                    ],
                    $order_data 
                );
            }
        }
    }
}
