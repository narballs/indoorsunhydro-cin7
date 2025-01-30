<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use App\Models\ApiOrder;
use App\Models\AdminSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\UserHelper;
use App\Helpers\SettingHelper;
use App\Helpers\MailHelper;
use App\Models\ShipstationApiLogs;
use Illuminate\Support\Facades\Log;


class LabelHelper {

    


    public static function processControllerOrder($order, $client, $currentDate, $shipstation_order_id) {
        try {
            $order_id = $order->id;
            $order = ApiOrder::where('id', $order_id)->first();
            $shipstation_order_id = $shipstation_order_id;
            $default_ship_from_address = self::getDefaultShipFromAddress();
            
            if (!$default_ship_from_address) {
                return Log::info('Default Ship From Address not found.');
            }
    
            $orderData = self::getOrderDataFromShipstation($client, "https://ssapi.shipstation.com/orders/{$shipstation_order_id}", self::getShipstationHeaders());
            if (!$orderData) {
                return Log::info('Order not found in ShipStation.');
            }


            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order_id}",
                'action' => 'get order ' . $order_id,
                'request' => 'get order data from shipstation',
                'response' => json_encode($orderData),
                'order_id' => $order_id,
                'status' => 200,
            ]);
    
            $order_items_array = self::processOrderItems($orderData['items']);
            if (empty($order_items_array)) {
                return Log::info('Order items not found in ShipStation.');
            }
    
            $prepare_data_for_creating_label = UserHelper::prepare_data_for_creating_label($orderData, $default_ship_from_address);
            $currentDate = self::adjustShipDate($orderData, $currentDate);
            
            $check_mode = AdminSetting::where('option_name', 'shipment_mode')->first();
            $is_sandbox = strtolower($check_mode->option_value) == 'sandbox';

            self::handleLabelCreation($client, $is_sandbox, $order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array, $currentDate);

            return true;
        } catch (\Exception $e) {
            Log::error("Error processing ShipStation order: {$e->getMessage()}");
            return false;
        }
    }

    public static function processOrder($order, $client, $currentDate, $data) {
        try {
            $order_id = $order->id;
            $order = ApiOrder::where('id', $order_id)->first();
            $shipstation_order_id = $order->shipstation_orderId;
            $default_ship_from_address = self::getDefaultShipFromAddress();
            
            if (!$default_ship_from_address) {
                return Log::info('Default Ship From Address not found.');
            }
    
            $orderData = self::getOrderDataFromShipstation($client, "https://ssapi.shipstation.com/orders/{$shipstation_order_id}", self::getShipstationHeaders());
            if (!$orderData) {
                return Log::info('Order not found in ShipStation.');
            }

            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order_id}",
                'action' => 'get_order  ' . $order_id,
                'request' => 'get order data from shipstation',
                'response' => json_encode($orderData),
                'order_id' => $order_id,
                'status' => 200,
            ]);
    
            $order_items_array = self::processOrderItems($orderData['items']);
            if (empty($order_items_array)) {
                return Log::info('Order items not found in ShipStation.');
            }
    
            $prepare_data_for_creating_label = UserHelper::prepare_data_for_creating_label($orderData, $default_ship_from_address);
            $currentDate = self::adjustShipDate($orderData, $currentDate);
            
            $check_mode = AdminSetting::where('option_name', 'shipment_mode')->first();
            $is_sandbox = strtolower($check_mode->option_value) == 'sandbox';

            self::handleLabelCreation($client, $is_sandbox, $order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array, $currentDate);
        } catch (\Exception $e) {
            Log::error("Error processing ShipStation order: {$e->getMessage()}");
        }
    }
    
    public static function getDefaultShipFromAddress() {
        return UserHelper::get_default_shipstation_warehouse()['default_ship_from_address'] ?? null;
    }
    
    public static function getShipstationHeaders() {
        return [
            "Content-Type: application/json",
            'Authorization' => 'Basic ' . base64_encode(config('services.shipstation.key') . ':' . config('services.shipstation.secret')),
        ];
    }
    
    public static function getOrderDataFromShipstation($client, $url, $headers) {
        $response = $client->request('GET', $url, ['headers' => $headers]);
        return json_decode($response->getBody()->getContents(), true);
    }
    
    public static function processOrderItems($order_items) {
        return array_map(function ($item) {
            return ['sku' => $item['sku'], 'name' => $item['name'], 'quantity' => $item['quantity']];
        }, $order_items);
    }
    
    public static function adjustShipDate($orderData, $currentDate) {
        return isset($orderData['shipDate']) && $orderData['shipDate'] < $currentDate ? $currentDate : $currentDate;
    }
    
    public static function handleLabelCreation($client, $is_sandbox, $order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array, $currentDate) {
        
        if ($is_sandbox) {
            self::handleSandboxMode($order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array);
        } else {
            self::handleProductionMode($client, $prepare_data_for_creating_label, $order, $order_id, $orderData, $order_items_array);
        }
    }
    
    public static function handleSandboxMode($order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array) {
        self::createAndSendLabel($order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array, true , $label_sanbox_response = null);
    }
    
    public static function handleProductionMode($client, $prepare_data_for_creating_label, $order, $order_id, $orderData, $order_items_array) {
        $response = $client->post(config('services.shipstation.shipment_label_url'), [
            'headers' => self::getShipstationHeaders(),
            'json' => $prepare_data_for_creating_label,
        ]);
    
        $responseBody = json_decode($response->getBody()->getContents());
        self::createAndSendLabel($order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array, false, $responseBody);
    }
    
    public static function createAndSendLabel($order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array, $is_sandbox = false, $label_api_response) {
        $packingSlipPdf = self::creating_packing_slip_for_label($orderData, $prepare_data_for_creating_label, $orderData['items'], $orderData['customerEmail']);
        $packingSlipFileName = 'packing-slip-' . $order_id . '-' . now()->format('YmdHis') . '.pdf';
        file_put_contents(public_path('packing_slips/' . $packingSlipFileName), $packingSlipPdf->output());
    
        $label_data = $is_sandbox ? base64_decode(UserHelper::shipment_label()) : base64_decode($label_api_response->labelData);
        $file_name = 'label-' . $order_id . '-' . now()->format('YmdHis') . '.pdf';
        file_put_contents(public_path('labels/' . $file_name), $label_data);
    
        ShipstationApiLogs::create([
            'api_url' => config('services.shipstation.shipment_label_url') . " {$order_id}",
            'action' => 'create_label ' . $order_id,
            'request' => json_encode($prepare_data_for_creating_label),
            'response' => $is_sandbox ? 'label created from sandbox' : json_encode($label_api_response),
            'order_id' => $order_id,
            'status' => 200,
        ]);

        $label_email_data = self::prepareLabelEmailData($order_id, $orderData['customerEmail'], $prepare_data_for_creating_label, $packingSlipFileName, $file_name, $order_items_array);
    
        self::update_and_sendLabelEmail($label_email_data, $order, $file_name);
    }


    public static function prepareLabelEmailData($order_id, $user_email, $prepare_data_for_creating_label, $packingSlipFileName, $file_name , $order_items_array) {
        
        return [
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
    }
    
    public static function update_and_sendLabelEmail($label_email_data, $order, $file_name) {
        
        $order_update = $order->update(
            [
                'is_shipped' => 1,
                'label_created' => 1, 
                'label_link' => $file_name
            ]
        );

        if (!$order_update) {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'update_label ' . $order->id,
                'request' => json_encode($label_email_data),
                'response' => 'error updating order',
                'order_id' => $order->id,
                'status' => 500,
            ]);
        } else {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'update_label ' . $order->id,
                'request' => json_encode($label_email_data),
                'response' => 'order updated successfully',
                'order_id' => $order->id,
                'status' => 200,
            ]);
        }

        $mail_send = MailHelper::sendShipstationLabelMail('emails.shipment_label', $label_email_data);
        
        if ($mail_send) {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'send_email_to_user  ' . $order->id,
                'request' => json_encode($label_email_data),
                'response' => 'email sent to user',
                'order_id' => $order->id,
                'status' => 200,
            ]);

            self::sendAdminEmails($label_email_data, $order, $file_name, $mail_send);
            Log::info('Shipment label created and email sent successfully.');
        } else {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'error_sending_email_to_user ' . $order->id,
                'request' => json_encode($label_email_data),
                'response' => 'error sending email',
                'order_id' => $order->id,
                'status' => 500,
            ]);
        }
    }
    
    public static function sendAdminEmails($label_email_data, $order, $file_name, $mail_send) {
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
                'action' => 'send_email_to_admin ' . $order->id,
                'request' => json_encode($label_email_data),
                'response' => $mail_send ? 'email sent to admins' : 'error sending email',
                'order_id' => $order->id,
                'status' => $mail_send ? 200 : 500,
            ]);
        } else {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'error_sending_email_to_admin ' . $order->id,
                'request' => json_encode($label_email_data),
                'response' => 'no valid emails found',
                'order_id' => $order->id,
                'status' => 500,
            ]);
        }
        
    }
    
    public static function creating_packing_slip_for_label($orderData , $prepare_data_for_creating_label , $order_items , $user_email) {
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