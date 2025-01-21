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
        $check_label_settinga = AdminSetting::where('option_name', 'auto_create_label')->first();

        if (!empty($check_label_settinga) && strtolower($check_label_settinga->option_value) == 'no') {
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
        ->whereNotNull('shipstation_orderId') // Correct way to check for NOT NULL
        ->where('payment_status', 'paid')
        ->where('isApproved', 1)
        ->where('created_at', '>=', '2025-01-09 12:23:51') // Added date condition
        ->where('shipping_carrier_code',  'ups_walleted')
        ->get();


        if ($autoLabelSetting) {
            $daysOfWeek = json_decode($autoLabelSetting->days_of_week, true);
    
            foreach ($autoLabelSetting->timeRanges as $timeRange) {
                if (!empty($autoLabelSetting->timeRanges)) {
                    $startTime = Carbon::parse($timeRange->start_time)->format('H:i');
                    $endTime = Carbon::parse($timeRange->end_time)->format('H:i');

                    $currentDay = now()->format('D');
        
                    $dayMap = [
                        'Mon' => 'M',
                        'Tue' => 'T',
                        'Wed' => 'W',
                        'Thu' => 'TH',
                        'Fri' => 'F',
                        'Sat' => 'S',
                        'Sun' => 'S'
                    ];
        
                    if (in_array($dayMap[$currentDay], $daysOfWeek)) {
                        $currentTime = now()->format('H:i');
                        if ($currentTime >= $startTime && $currentTime <= $endTime) {
                            if ($all_orders->isEmpty()) {
                                $this->info('No orders found to create label');
                                return;
                            }

                            Log::info('Auto create label is enabled');
        
                            foreach ($all_orders as $order) {
                                $this->processOrder($order, $client, $currentDate, $data);
                                sleep($delay_duration * 60);  
                                Log::info('Label created for order: ' . $order->id);      
                            }
                        }
                    }
                }
            }
        } else {
            $this->info('Auto create label is disabled');
        }
         
    }

    private function processOrder($order, $client , $currentDate , $data) {
            $order_id = $order->id;
            $order = ApiOrder::where('id', $order->id)->first();
            $shipstation_order_id = $order->shipstation_orderId;
            $order_items_array = [];

            $order_contact = Contact::where('contact_id', $order->memberId)->first();
            
            $shipstation_api_key = config('services.shipstation.key');
            $shipstation_api_secret = config('services.shipstation.secret');
            $shipstation_label_url = config('services.shipstation.shipment_label_url');

            $get_default_ship_from_address =  UserHelper::get_default_shipstation_warehouse();
            if (empty($get_default_ship_from_address) || empty($get_default_ship_from_address['default_ship_from_address'])) {
                return Log::info('Default Ship From Address not found.');
            } else {
                $default_ship_from_address = $get_default_ship_from_address['default_ship_from_address'];
            }

            $getShipstationOrderUrl = 'https://ssapi.shipstation.com/orders/' . $shipstation_order_id;

            $headers = [
                "Content-Type: application/json",
                'Authorization' => 'Basic ' . base64_encode($shipstation_api_key . ':' . $shipstation_api_secret),
            ];

            try {
                $response = $client->request('GET', $getShipstationOrderUrl, [
                    'headers' => $headers
                ]);
            
                $orderData = json_decode($response->getBody()->getContents(), true);
                if (empty($orderData)) {
                    return Log::info('Order not found in ShipStation.');
                }

                $user_email = $orderData['customerEmail'];
                $order_items = $orderData['items'];
                if (empty($order_items)) {
                    return redirect('admin/orders')->with('error', 'Order items not found in ShipStation.');
                } else {
                    foreach ($order_items as $order_item) {
                        $order_items_array[] = [
                            'sku' => $order_item['sku'],
                            'name' => $order_item['name'],
                            'quantity' => $order_item['quantity'],
                        ];
                    }
                }


                $prepare_data_for_creating_label = UserHelper::prepare_data_for_creating_label($orderData, $default_ship_from_address);
                $template = 'emails.shipment_label';

                if (isset($orderData['shipDate']) && $orderData['shipDate'] < $currentDate) {
                    $orderData['shipDate'] = $currentDate; // Set to the current date
                }

                $check_mode = AdminSetting::where('option_name', 'shipment_mode')->first();
                if  (strtolower($check_mode->option_value) == strtolower('sandbox')) {
                    
                    $packingSlipPdf = Pdf::loadView('partials.packing_slip', [
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
                
                    $packingSlipFileName = 'packing-slip-' . $order_id . '-' . date('YmdHis') . '.pdf';
                    $packingSlipDir = 'public/packing_slips';

                    // Check if the directory exists
                    if (!file_exists($packingSlipDir)) {
                        // Create the directory if it doesn't exist
                        mkdir($packingSlipDir, 0777, true); // 0777 gives full permissions and 'true' ensures recursive directory creation
                    }

                    $packingSlipPath = public_path('packing_slips/' . $packingSlipFileName);

                    // Save the Packing Slip PDF directly to the public directory
                    file_put_contents($packingSlipPath, $packingSlipPdf->output());
                    
                    
                    $labelData = UserHelper::shipment_label();
                    $label_data = base64_decode($labelData);
                    $file_name = 'label-' . $order_id . '-' . date('YmdHis') . '.pdf';
                    $labelDir = 'public/labels';
                    // Check if the directory exists
                    if (!file_exists($labelDir)) {
                        // Create the directory if it doesn't exist
                        mkdir($labelDir, 0777, true); // 0777 gives full permissions and 'true' ensures recursive directory creation
                    }

                    $labelPath = public_path('labels/' . $file_name);
                    file_put_contents($labelPath, $label_data);
                    
                    $label_email_data = [
                        'email' => $user_email,
                        'subject' => 'Ship Web Order ' . $order_id,
                        'content' => [
                            'order_id' => $order_id,
                            'company' => $prepare_data_for_creating_label['shipTo']['company'],
                            'name' => $prepare_data_for_creating_label['shipTo']['name'],
                            'street1' => $prepare_data_for_creating_label['shipTo']['street1'],
                            'street2' => $prepare_data_for_creating_label['shipTo']['street2'],
                            'city' => $prepare_data_for_creating_label['shipTo']['city'],
                            'state' => $prepare_data_for_creating_label['shipTo']['state'],
                            'postalCode' => $prepare_data_for_creating_label['shipTo']['postalCode'],
                            'country' => $prepare_data_for_creating_label['shipTo']['country'],
                            'phone' => $prepare_data_for_creating_label['shipTo']['phone'],
                        ],
                        'order_items' => $order_items_array,
                        'from' => SettingHelper::getSetting('noreply_email_address'),
                        'packingSlipFileName' => $packingSlipFileName,  // Packing slip file name
                        'labelFileName' => $file_name,   
                    ];

                    $ship_station_api_logs  = new ShipstationApiLogs();      
                    $ship_station_api_logs->api_url = $shipstation_label_url;
                    $ship_station_api_logs->request = json_encode($prepare_data_for_creating_label);
                    $ship_station_api_logs->response = 'label created from sandbox';
                    $ship_station_api_logs->status = 200;
                    $ship_station_api_logs->save();

                    $mail_send = MailHelper::sendShipstationLabelMail($template ,$label_email_data);

                    $naris_indoor_email = SettingHelper::getSetting('naris_indoor_email');
                    $wally_shipstation_email = SettingHelper::getSetting('wally_shipstation_email');

                    // Check if both emails are not empty
                    if (!empty($naris_indoor_email) && !empty($wally_shipstation_email)) {
                        // Prepare email data with emails as an array
                        $label_email_data['email'] = [$naris_indoor_email, $wally_shipstation_email];
                        
                        // Send the email to both recipients
                        $mail_send = MailHelper::sendShipstationLabelMail($template, $label_email_data);
                    }
                    else {
                        $specific_admin_notifications = SpecificAdminNotification::all();
                        if (count($specific_admin_notifications) > 0) {
                            foreach ($specific_admin_notifications as $specific_admin_notification) {
                                $label_email_data['email'] = $specific_admin_notification->email;
                                $mail_send = MailHelper::sendShipstationLabelMail($template ,$label_email_data);
                            }
                        }
                    }
                    
                    if ($mail_send) {
                        $order->update([
                            'is_shipped' => 1,
                            'label_created' => 1,
                            'label_link' => $file_name,
                        ]);

                        
                        $this->info('Shipment label created and email sent successfully.');
                    } else {
                        $this->info('Error sending email.');
                    }
                } 
                else {
                    try {
                        $response = $client->post($shipstation_label_url, [
                            'headers' => $headers,
                            'json' => $prepare_data_for_creating_label,
                        ]);
                        $statusCode = $response->getStatusCode();
                        
                        $responseBody = $response->getBody()->getContents();
                        $label_api_response = json_decode($responseBody);
                        $label_data = base64_decode($label_api_response->labelData);
                        
                        $file_name = 'label-' . $order_id . '-' . date('YmdHis') . '.pdf';

                        $labelDir = 'public/labels';
                        // Check if the directory exists
                        if (!file_exists($labelDir)) {
                            // Create the directory if it doesn't exist
                            mkdir($labelDir, 0777, true); // 0777 gives full permissions and 'true' ensures recursive directory creation
                        }

                        

                        $labelPath = public_path('labels/' . $file_name);

                        // Save the Label PDF directly to the public directory
                        file_put_contents($labelPath, $label_data);


                        $packingSlipPdf = Pdf::loadView('partials.packing_slip', [
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
                    
                        $packingSlipFileName = 'packing-slip-' . $order_id . '-' . date('YmdHis') . '.pdf';
                        $packingSlipDir = 'public/packing_slips';

                        // Check if the directory exists
                        if (!file_exists($packingSlipDir)) {
                            // Create the directory if it doesn't exist
                            mkdir($packingSlipDir, 0777, true); // 0777 gives full permissions and 'true' ensures recursive directory creation
                        }

                        $packingSlipPath = public_path('packing_slips/' . $packingSlipFileName);

                        // Save the Packing Slip PDF directly to the public directory
                        file_put_contents($packingSlipPath, $packingSlipPdf->output());
                        
                        $order->update([
                            'is_shipped' => 1,
                            'label_created' => 1,
                            'label_link' => $file_name,
                        ]);
            
                        $label = [
                            'orderId' => $label_api_response->orderId,
                            'labelData' => $label_api_response->labelData,
                        ];


                        $label_email_data = [
                            'email' => $user_email,
                            'subject' => 'Ship Web Order ' . $order_id,
                            'content' => [
                                'subject' => 'Ship Web Order ' . $order_id,
                                'email' => $user_email,
                                'order_id' => $order_id,
                                'company' => $prepare_data_for_creating_label['shipTo']['company'],
                                'name' => $prepare_data_for_creating_label['shipTo']['name'],
                                'street1' => $prepare_data_for_creating_label['shipTo']['street1'],
                                'street2' => $prepare_data_for_creating_label['shipTo']['street2'],
                                'city' => $prepare_data_for_creating_label['shipTo']['city'],
                                'state' => $prepare_data_for_creating_label['shipTo']['state'],
                                'postalCode' => $prepare_data_for_creating_label['shipTo']['postalCode'],
                                'country' => $prepare_data_for_creating_label['shipTo']['country'],
                                'phone' => $prepare_data_for_creating_label['shipTo']['phone'],
                            ],
                            'order_items' => $order_items_array,
                            'from' => SettingHelper::getSetting('noreply_email_address'),
                            'packingSlipFileName' => $packingSlipFileName,  // Packing slip file name
                            'labelFileName' => $file_name, 
                        ];
            
            
                        $ship_station_api_logs  = new ShipstationApiLogs();      
                        $ship_station_api_logs->api_url = $shipstation_label_url;
                        $ship_station_api_logs->request = json_encode($data);
                        $ship_station_api_logs->response = $responseBody;
                        $ship_station_api_logs->status = $statusCode;
                        $ship_station_api_logs->save();

                        $mail_send = MailHelper::sendShipstationLabelMail($template ,$label_email_data);
                        $naris_indoor_email = SettingHelper::getSetting('naris_indoor_email');
                        $wally_shipstation_email = SettingHelper::getSetting('wally_shipstation_email');

                        // Check if both emails are not empty
                        if (!empty($naris_indoor_email) && !empty($wally_shipstation_email)) {
                            // Prepare email data with emails as an array
                            $label_email_data['email'] = [$naris_indoor_email, $wally_shipstation_email];
                            
                            // Send the email to both recipients
                            $mail_send = MailHelper::sendShipstationLabelMail($template, $label_email_data);
                        }
                        else {
                            $specific_admin_notifications = SpecificAdminNotification::all();
                            if (count($specific_admin_notifications) > 0) {
                                foreach ($specific_admin_notifications as $specific_admin_notification) {
                                    $label_email_data['email'] = $specific_admin_notification->email;
                                    $mail_send = MailHelper::sendShipstationLabelMail($template ,$label_email_data);
                                }
                            }
                        }

                        if ($mail_send) {
                            $order->update([
                                'is_shipped' => 1,
                                'label_created' => 1,
                                'label_link' => $file_name,
                            ]);
                            return Log::info('Shipment label created and email sent successfully.');
                        } else {
                            return Log::info('Error sending email.');
                        }
                        
                    } catch (\Exception $e) {
                        Log::error($e->getMessage());
            
                        $ship_station_api_logs  = new ShipstationApiLogs();      
                        $ship_station_api_logs->api_url = $shipstation_label_url;
                        $ship_station_api_logs->request = json_encode($data);
                        $ship_station_api_logs->response = $e->getMessage();
                        $ship_station_api_logs->status = $response->getStatusCode();
                        $ship_station_api_logs->save();
            
                        $this->info('Error creating label: ' . $e->getMessage());
                    }
                }

                Log::info('Shipment label created and email sent successfully.');

            } catch (\GuzzleHttp\Exception\RequestException $e) {
                Log::error($e->getMessage());
                $this->info('Error creating label: ' . $e->getMessage());
            }
    }
    
}
