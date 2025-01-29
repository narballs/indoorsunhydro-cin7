<?php

namespace App\Console\Commands;

use App\Models\AdminSetting;
use App\Models\ApiOrder;
use App\Models\ShipstationApiLogs;
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
    protected $description = 'This command will mark the order as shipped in our db if it is marked as shipped in Shipstation';

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

        // Exit early if auto_mark_order_shipped is set to 'no'
        if ($auto_mark_order_shipped && strtolower($auto_mark_order_shipped->option_value) === 'no') {
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
            ->get();

        if ($all_orders->isEmpty()) {
            Log::info('No orders found to mark as shipped');
            return;
        }

        foreach ($all_orders as $order) {
            try {

                if (empty($order->shipstation_orderId)) {
                    Log::info('Order ' . $order->id . ' does not have a Shipstation order ID');
                    continue;
                }
                // Skip if order is already marked as shipped
                if ($order->is_shipped) {
                    Log::info('Order ' . $order->id . ' is already marked as shipped');
                    continue;
                }

                $url = 'https://ssapi.shipstation.com/orders/' . $order->shipstation_orderId;

                $response = $client->request('GET', $url, [
                    'headers' => [
                        'Authorization' => 'Basic ' . base64_encode(config('services.shipstation.key') . ':' . config('services.shipstation.secret')),
                        'Content-Type' => 'application/json',
                    ],
                ]);

                $response_data = json_decode($response->getBody()->getContents());

                ShipstationApiLogs::create([
                    'api_url' => $url,
                    'action' => 'get_order_for' . $order->id,
                    'request' => 'get order details',
                    'response' => json_encode($response_data),
                    'status' => 200,
                ]);

                // Check if order is marked as shipped in ShipStation
                if (isset($response_data->orderStatus) && $response_data->orderStatus === 'shipped') {
                    $order->is_shipped = 1;
                    $order->label_created = 1;
                    $order->save();

                    ShipstationApiLogs::create([
                        'api_url' => $url,
                        'action' => 'update_order_for' . $order->id,
                        'request' => 'mark order as shipped in db',
                        'response' => json_encode($response_data),
                        'status' => 200,
                    ]);

                    Log::info('Order ' . $order->id . ' marked as shipped in Shipstation');
                } else {
                    Log::info('Order ' . $order->id . ' is not marked as shipped in Shipstation');

                    ShipstationApiLogs::create([
                        'api_url' => $url,
                        'action' => 'error_update_order_for' . $order->id,
                        'request' => 'order not marked as shipped in Shipstation',
                        'response' => json_encode($response_data),
                        'status' => 200,
                    ]);
                }
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                // Handle Guzzle-specific exceptions
                Log::error('Request failed for order ' . $order->id . ': ' . $e->getMessage());
                ShipstationApiLogs::create([
                    'api_url' => $url,
                    'action' => 'error_getting_order_for' . $order->id,
                    'request' => 'order not marked as shipped in Shipstation',
                    'response' => $e->getMessage(),
                    'status' => $e->getCode(),
                ]);
            } catch (\Exception $e) {
                // Catch other exceptions
                Log::error('Unexpected error for order ' . $order->id . ': ' . $e->getMessage());
                ShipstationApiLogs::create([
                    'api_url' => $url,
                    'action' => 'error_getting_order_for' . $order->id,
                    'request' => 'order not marked as shipped in Shipstation',
                    'response' => $e->getMessage(),
                    'status' => $e->getCode(),
                ]);
            }
        }
    }


}
