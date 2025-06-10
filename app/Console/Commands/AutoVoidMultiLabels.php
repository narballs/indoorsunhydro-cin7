<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApiOrder;
use App\Models\ShipstationApiLogs;
use App\Helpers\SettingHelper;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class AutoVoidMultiLabels extends Command
{
    protected $signature = 'shipstation:auto-void-labels';
    protected $description = 'Auto-void multiple labels for ShipStation orders and notify admins.';

    public function handle()
    {
        $orders = ApiOrder::where('label_created', 1)
            ->where('is_shipped', 1)
            ->whereNotNull('shipstation_orderId')
            ->whereNotNull('tracking_number')
            ->where('created_at' ,'>=' ,'2025-06-07 16:49:33')
            ->get();

        if ($orders->isEmpty()) {
            Log::info('No orders found for auto voiding labels.');
            return;
        }

        $client = new Client();
        $headers = self::getShipstationHeaders();
        $shipmentsUrl = config('services.shipstation.shipstation_shipments_list');

        foreach ($orders as $order) {
            try {
                if (empty($order->shipstation_orderId) || empty($order->shipmentId)) {
                    ShipstationApiLogs::create([
                        'api_url' => $shipmentsUrl,
                        'action' => 'check_shipmentId',
                        'request' => 'shipmentId not found for order: ' . $order->id,
                        'response' => 'shipmentId not found',
                        'order_id' => $order->id,
                        'status' => 500,
                    ]);
                    continue;
                }

                $response = $client->get("{$shipmentsUrl}/{$order->shipstation_orderId}", [
                    'headers' => $headers,
                ]);

                $responseBody = json_decode($response->getBody()->getContents(), true);
                $shipments = $responseBody['shipments'] ?? [];

                if (count($shipments) > 1) {
                    foreach ($shipments as $shipment) {
                        if (
                            !isset($shipment['shipmentId']) ||
                            $shipment['trackingNumber'] === $order->tracking_number
                        ) {
                            continue;
                        }

                        $voided = self::voidLabel($order, $shipment['shipmentId']);

                        if ($voided) {
                            Log::info("Voided extra label for order ID {$order->id}");

                            $emails = array_filter([
                                SettingHelper::getSetting('naris_indoor_email'),
                                SettingHelper::getSetting('engrdanish_shipstation_email'),
                            ]);

                            if (!empty($emails)) {
                                Mail::send([], [], function ($message) use ($emails, $order, $shipment) {
                                    $message->from(SettingHelper::getSetting('noreply_email_address'));
                                    $message->to($emails);
                                    $message->subject("Multiple labels found for Order #{$order->id}");
                                    $message->setBody(
                                        "Order ID <strong>{$order->id}</strong> had multiple labels. One label has been voided automatically.<br><br>
                                        ShipStation Order ID: {$order->shipstation_orderId}<br>
                                        DB Tracking Number: {$order->tracking_number}<br>
                                        Voided Tracking Number: {$shipment['trackingNumber']}",
                                        'text/html'
                                    );
                                });
                            } else {
                                Log::warning("No admin emails configured to notify about voided label for order #{$order->id}");
                            }

                            break; // Only void one extra
                        }
                    }
                }

            } catch (\Exception $e) {
                ShipstationApiLogs::create([
                    'api_url' => $shipmentsUrl,
                    'action' => 'void_label_error',
                    'request' => json_encode(['shipmentId' => $order->shipmentId]),
                    'response' => $e->getMessage(),
                    'order_id' => $order->id,
                    'status' => 500,
                ]);

                Log::error("Error voiding label for order ID {$order->id}: " . $e->getMessage());
            }
        }

        Log::info('Auto-void process completed.');
    }

    private static function getShipstationHeaders(): array
    {
        return [
            'Authorization' => 'Basic ' . base64_encode(
                config('services.shipstation.api_key') . ':' . config('services.shipstation.api_secret')
            ),
            'Content-Type' => 'application/json',
        ];
    }

    private static function voidLabel($order, $shipmentId): bool
    {
        if (empty($order) || empty($shipmentId)) {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipstation_void_label'),
                'action' => 'void_label_missing_input',
                'request' => 'Order or shipmentId not found',
                'response' => 'Missing input',
                'order_id' => $order->id ?? null,
                'status' => 500,
            ]);
            return false;
        }

        try {
            $client = new Client();
            $headers = self::getShipstationHeaders();
            $url = config('services.shipstation.shipstation_void_label');

            $response = $client->post($url, [
                'headers' => $headers,
                'json' => ['shipmentId' => $shipmentId],
            ]);

            $responseBody = json_decode($response->getBody()->getContents());

            if (isset($responseBody->message) && $responseBody->message === 'Shipment not found') {
                ShipstationApiLogs::create([
                    'api_url' => $url,
                    'action' => 'auto_void_label_not_found',
                    'request' => json_encode(['shipmentId' => $shipmentId]),
                    'response' => json_encode($responseBody),
                    'order_id' => $order->id,
                    'status' => 404,
                ]);
                return false;
            }

            ShipstationApiLogs::create([
                'api_url' => $url,
                'action' => 'auto_void_label_success',
                'request' => json_encode(['shipmentId' => $shipmentId]),
                'response' => json_encode($responseBody),
                'order_id' => $order->id,
                'status' => 200,
            ]);

            return true;
        } catch (\Exception $e) {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipstation_void_label'),
                'action' => 'void_label_exception',
                'request' => json_encode(['shipmentId' => $shipmentId]),
                'response' => $e->getMessage(),
                'order_id' => $order->id ?? null,
                'status' => 500,
            ]);

            Log::error("Exception during void label for order ID {$order->id}: " . $e->getMessage());
            return false;
        }
    }
}
