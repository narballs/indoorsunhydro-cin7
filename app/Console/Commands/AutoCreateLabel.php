<?php

namespace App\Console\Commands;

use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use App\Helpers\UserHelper;
use App\Models\AdminSetting;
use App\Models\ApiOrder;
use App\Models\AutoLabelSetting;
use App\Models\Contact;
use App\Models\ShipstationApiLogs;
use App\Models\SpecificAdminNotification;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Google\Service\Calendar\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoCreateLabel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:label';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create a new label for the user';

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
        try {
            $check_label_settings = AdminSetting::where('option_name', 'auto_create_label')->first();

            if (!empty($check_label_settings) && strtolower($check_label_settings->option_value) == 'no') {
                $this->info('Auto create label is disabled');
                return;
            }

            Log::info('Auto create label is enabled');

            $autoLabelSetting = AutoLabelSetting::first();

            $delay_duration = !empty($autoLabelSetting->delay_duration) ? $autoLabelSetting->delay_duration : 0;

            $currentDate = date('Y-m-d');
            $data = [];
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

            

            if ($autoLabelSetting) {
                $daysOfWeek = json_decode($autoLabelSetting->days_of_week, true);
                $currentDay = now()->format('D');
            
                $dayMap = [
                    'Mon' => 'M',
                    'Tue' => 'T',
                    'Wed' => 'W',
                    'Thu' => 'TH',
                    'Fri' => 'F',
                    'Sat' => 'ST',
                    'Sun' => 'SU'
                ];
            
                Log::info("Configured days: " . implode(',', $daysOfWeek));
            
                foreach ($autoLabelSetting->timeRanges ?? [] as $timeRange) {
                    if (empty($timeRange) || !isset($timeRange->start_time, $timeRange->end_time)) {
                        Log::warning('Invalid time range found.');
                        continue;
                    }
            
                    $startTime = Carbon::parse($timeRange->start_time)->format('H:i');
                    $endTime = Carbon::parse($timeRange->end_time)->format('H:i');
                    $currentTime = now()->format('H:i');
            
                    Log::info("Checking schedule for day: " . $dayMap[$currentDay] . 
                            " | Start: {$startTime} | End: {$endTime} | Current: {$currentTime}");
            
                    if (in_array(strtoupper($dayMap[$currentDay]), array_map('strtoupper', $daysOfWeek))) {
                        if ($currentTime >= $startTime && $currentTime <= $endTime) {
                            if ($all_orders->isEmpty()) {
                                $this->info('No orders found to create label');
                                Log::info('No orders found to process.');
                                return;
                            }
            
                            Log::info('Auto create label is enabled');
            
                            foreach ($all_orders as $order) {
                                $this->processOrder($order, $client, $currentDate, $data);
                                sleep($delay_duration * 60);
                                if ($order->label_created || $order->label_created == 1) {
                                    Log::info('Label already created for order: ' . $order->id);
                                    continue;
                                }
                                Log::info('Label created for order: ' . $order->id);
                            }
                        }
                    }
                }
            } else {
                $this->info('Auto create label is disabled');
                Log::info('Auto label setting not found.');
            }

        } catch (\Exception $e) {
            Log::error('Error occurred during auto label creation: ' . $e->getMessage());
            $this->info('An error occurred while processing orders for label creation. Please check the logs for more details.');
        }
    }


    private function processOrder($order, $client, $currentDate, $data) {
        try {
            $order_id = $order->id;
            $order = ApiOrder::find($order_id);
            $shipstation_order_id = $order->shipstation_orderId;
            $default_ship_from_address = $this->getDefaultShipFromAddress();
            
            if (!$default_ship_from_address) {
                return Log::info('Default Ship From Address not found.');
            }
    
            $orderData = $this->getOrderDataFromShipstation($client, "https://ssapi.shipstation.com/orders/{$shipstation_order_id}", $this->getShipstationHeaders());
            if (!$orderData) {
                return Log::info('Order not found in ShipStation.');
            }
    
            $order_items_array = $this->processOrderItems($orderData['items']);
            if (empty($order_items_array)) {
                return Log::info('Order items not found in ShipStation.');
            }
    
            $prepare_data_for_creating_label = UserHelper::prepare_data_for_creating_label($orderData, $default_ship_from_address);
            $currentDate = $this->adjustShipDate($orderData, $currentDate);
            
            $check_mode = AdminSetting::where('option_name', 'shipment_mode')->first();
            $is_sandbox = strtolower($check_mode->option_value) == 'sandbox';

            $this->handleLabelCreation($client, $is_sandbox, $order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array, $currentDate);
        } catch (\Exception $e) {
            Log::error("Error processing ShipStation order: {$e->getMessage()}");
        }
    }
    
    private function getDefaultShipFromAddress() {
        return UserHelper::get_default_shipstation_warehouse()['default_ship_from_address'] ?? null;
    }
    
    private function getShipstationHeaders() {
        return [
            "Content-Type: application/json",
            'Authorization' => 'Basic ' . base64_encode(config('services.shipstation.key') . ':' . config('services.shipstation.secret')),
        ];
    }
    
    private function getOrderDataFromShipstation($client, $url, $headers) {
        $response = $client->request('GET', $url, ['headers' => $headers]);
        return json_decode($response->getBody()->getContents(), true);
    }
    
    private function processOrderItems($order_items) {
        return array_map(function ($item) {
            return ['sku' => $item['sku'], 'name' => $item['name'], 'quantity' => $item['quantity']];
        }, $order_items);
    }
    
    private function adjustShipDate($orderData, $currentDate) {
        return isset($orderData['shipDate']) && $orderData['shipDate'] < $currentDate ? $currentDate : $currentDate;
    }
    
    private function handleLabelCreation($client, $is_sandbox, $order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array, $currentDate) {
        
        if ($is_sandbox) {
            $this->handleSandboxMode($order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array);
        } else {
            $this->handleProductionMode($client, $prepare_data_for_creating_label, $order, $order_id, $orderData, $order_items_array);
        }
    }
    
    private function handleSandboxMode($order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array) {
        $this->createAndSendLabel($order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array, true);
    }
    
    private function handleProductionMode($client, $prepare_data_for_creating_label, $order, $order_id, $orderData, $order_items_array) {
        $response = $client->post(config('services.shipstation.shipment_label_url'), [
            'headers' => $this->getShipstationHeaders(),
            'json' => $prepare_data_for_creating_label,
        ]);
    
        $responseBody = json_decode($response->getBody()->getContents());
        $this->createAndSendLabel($order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array, false, $responseBody);
    }
    
    private function createAndSendLabel($order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array, $is_sandbox = false, $label_api_response = null) {
        $packingSlipPdf = $this->creating_packing_slip_for_label($orderData, $prepare_data_for_creating_label, $orderData['items'], $orderData['customerEmail']);
        $packingSlipFileName = 'packing-slip-' . $order_id . '-' . now()->format('YmdHis') . '.pdf';
        file_put_contents(public_path('packing_slips/' . $packingSlipFileName), $packingSlipPdf->output());
    
        $label_data = $is_sandbox ? base64_decode(UserHelper::shipment_label()) : base64_decode($label_api_response->labelData);
        $file_name = 'label-' . $order_id . '-' . now()->format('YmdHis') . '.pdf';
        file_put_contents(public_path('labels/' . $file_name), $label_data);
    
        $label_email_data = $this->prepareLabelEmailData($order_id, $orderData['customerEmail'], $orderData, $order_items_array, $packingSlipFileName, $file_name);
        ShipstationApiLogs::create([
            'api_url' => config('services.shipstation.shipment_label_url') . " {$order_id}",
            'action' => 'create_label ' . $order_id,
            'request' => json_encode($prepare_data_for_creating_label),
            'response' => $is_sandbox ? 'label created from sandbox' : $label_api_response,
            'status' => 200,
        ]);
    
        $this->sendLabelEmail($label_email_data, $order, $file_name);
    }
    
    private function sendLabelEmail($label_email_data, $order, $file_name) {
        $mail_send = MailHelper::sendShipstationLabelMail('emails.shipment_label', $label_email_data);
        if ($mail_send) {
            $this->sendAdminEmails($label_email_data, $order, $file_name, $mail_send);
            $order->update(['is_shipped' => 1, 'label_created' => 1, 'label_link' => $file_name]);
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'create_label',
                'request' => json_encode($label_email_data),
                'response' => 'label updated in database',
                'status' => 200,
            ]);
            $this->info('Shipment label created and email sent successfully.');
        } else {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'send_email_to_user',
                'request' => json_encode($label_email_data),
                'response' => 'error sending email',
                'status' => 500,
            ]);
        }
    }
    
    private function sendAdminEmails($label_email_data, $order, $file_name, $mail_send) {
        $email_addresses = array_filter([
            SettingHelper::getSetting('naris_indoor_email'),
            SettingHelper::getSetting('wally_shipstation_email'),
            SettingHelper::getSetting('engrdanish_shipstation_email')
        ]);
    
        if ($email_addresses) {
            $label_email_data['email'] = $email_addresses;
            $mail_send = MailHelper::sendShipstationLabelMail('emails.shipment_label', $label_email_data);
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'send_email_to_admin',
                'request' => json_encode($label_email_data),
                'response' => $mail_send ? 'email sent' : 'error sending email',
                'status' => $mail_send ? 200 : 500,
            ]);
        } else {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'send_email_to_admin',
                'request' => json_encode($label_email_data),
                'response' => 'no valid emails found',
                'status' => 500,
            ]);
        }
    }
    
    private function creating_packing_slip_for_label($orderData , $prepare_data_for_creating_label , $order_items , $user_email) {
        return Pdf::loadView('partials.packing_slip', [
            'order_id' => $orderData['orderNumber'],
            'reference' => $orderData['orderKey'],
            'company' => $prepare_data_for_creating_label['shipTo']['company'],
            'name' => $orderData['shipTo']['name'],
            'street1' => $orderData['shipTo']['street1'],
            'street2' => $orderData['shipTo']['street2'],
            'city' => $orderData['shipTo']['city'],
            'state' => $orderData['shipTo']['state'],
            'postalCode' => $orderData['shipTo']['postalCode'],
            'country' => $orderData['shipTo']['country'],
            'phone' => $orderData['shipTo']['phone'],
            'email' => $user_email,
            'shipDate' => $orderData['shipDate'],
            'orderDate' => $orderData['orderDate'],
            'order_items' => $order_items,
            'orderTotal' => $orderData['orderTotal'],
            'taxAmount' => $orderData['taxAmount'],
            'shippingAmount' => $orderData['shippingAmount'],
        ]);
    }
    
    
    
    
}
