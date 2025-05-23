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
use Illuminate\Support\Facades\Mail;

class LabelHelper {

    


    public static function processControllerOrder($order, $client, $currentDate, $shipstation_order_id) {
        try {
            $order_id = $order->id;
            $order = ApiOrder::where('id', $order_id)->first();
            $shipstation_order_id = $shipstation_order_id;
            $default_ship_from_address = self::getDefaultShipFromAddress();
            
            if (!$default_ship_from_address) {
                Log::info('Default Ship From Address not found.');
                return false;
            }
    
            $orderData = self::getOrderDataFromShipstation($client, "https://ssapi.shipstation.com/orders/{$shipstation_order_id}", self::getShipstationHeaders(), $order_id);
            if (!$orderData) {
                Log::info('Order not found in ShipStation.');
                return false;
            }


            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order_id}",
                'action' => 'get_order',
                'request' => 'get order data from shipstation',
                'response' => json_encode($orderData),
                'order_id' => $order_id,
                'status' => 200,
            ]);
    
            $order_items_array = self::processOrderItems($orderData['items']);
            if (empty($order_items_array)) {
                Log::info('Order items not found in ShipStation.');
                return false;

            }
    
            $prepare_data_for_creating_label = UserHelper::prepare_data_for_creating_label($orderData, $default_ship_from_address);

            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order_id}",
                'action' => 'prepare_data_for_creating_label',
                'request' => json_encode($prepare_data_for_creating_label),
                'response' => 'prepared data for creating label',
                'order_id' => $order_id,
                'status' => 200,
            ]);


            $currentDate = self::adjustShipDate($orderData, $currentDate);


            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order_id}",
                'action' => 'adjust_ship_date',
                'request' => json_encode($currentDate),
                'response' => 'adjusted ship date',
                'order_id' => $order_id,
                'status' => 200,
            ]);

            
            $check_mode = AdminSetting::where('option_name', 'shipment_mode')->first();
            $is_sandbox = strtolower($check_mode->option_value) == 'sandbox';

            $ship_station_log = ShipstationApiLogs::where('order_id', $order_id)
            ->where('action', 'create_label')
            ->first();

            if ($ship_station_log) {
                return false;
            }

            self::handleLabelCreation($client, $is_sandbox, $order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array, $currentDate);

            return true;

        } catch (\Exception $e) {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order_id}",
                'action' => 'error_processing_order ' . $order_id,
                'request' => 'error processing order',
                'response' => $e->getMessage(),
                'order_id' => $order_id,
                'status' => 500,
            ]);

            $email_addresses = array_filter([
                SettingHelper::getSetting('naris_indoor_email'),
                SettingHelper::getSetting('engrdanish_shipstation_email'),
            ]);

            if (!empty($email_addresses)) {
                Mail::send([], [], function ($message) use ($email_addresses, $order_id, $e) {
                    $message->from(SettingHelper::getSetting('noreply_email_address'));
                    $message->to($email_addresses);
                    $message->subject('Error processing order during label creation');
                    $message->setBody(
                        'Error processing order: ' . $order_id . ' - ' . $e->getMessage(),
                        'text/html'
                    );
                });
            }


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
    
            $orderData = self::getOrderDataFromShipstation($client, "https://ssapi.shipstation.com/orders/{$shipstation_order_id}", self::getShipstationHeaders(), $order_id);
            if (!$orderData) {
                return Log::info('Order not found in ShipStation.');
            }

            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order_id}",
                'action' => 'get_order',
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
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order_id}",
                'action' => 'error_processing_order through command ' . $order_id,
                'request' => 'error processing order',
                'response' => $e->getMessage(),
                'order_id' => $order_id,
                'status' => 500,
            ]);

            // Send email to all valid admin addresses
            $email_addresses = array_filter([
                SettingHelper::getSetting('naris_indoor_email'),
                SettingHelper::getSetting('engrdanish_shipstation_email'),
            ]);

            if (!empty($email_addresses)) {
                Mail::send([], [], function ($message) use ($email_addresses, $order_id, $e) {
                    $message->from(SettingHelper::getSetting('noreply_email_address'));
                    $message->to($email_addresses);
                    $message->subject('Error processing order during label creation');
                    $message->setBody(
                        'Error processing order: ' . $order_id . ' - ' . $e->getMessage(),
                        'text/html'
                    );
                });
            }

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
    
    public static function getOrderDataFromShipstation($client, $url, $headers, $order_id) {
        try {
            $response = $client->request('GET', $url, ['headers' => $headers]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {

            ShipstationApiLogs::create([
                'api_url' => $url,
                'action' => 'get_order',
                'request' => 'get order data from shipstation',
                'response' => $e->getMessage(),
                'order_id' => $order_id,
                'status' => 500,
            ]);

            return null;
        }


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
        try {

            $ship_station_log = ShipstationApiLogs::where('order_id', $order_id)
            ->where('action', 'create_label')
            ->first();
            
            if ($ship_station_log) {
                return Log::info('Label already created for order: ' . $order_id);
            }

            $response = $client->post(config('services.shipstation.shipment_label_url'), [
                'headers' => self::getShipstationHeaders(),
                'json' => $prepare_data_for_creating_label,
            ]);
        
            $responseBody = json_decode($response->getBody()->getContents());
            self::createAndSendLabel($order, $order_id, $orderData, $prepare_data_for_creating_label, $order_items_array, false, $responseBody);
        } 
        catch (\Exception $e) {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order_id}",
                'action' => 'create_label',
                'request' => json_encode($prepare_data_for_creating_label),
                'response' => $e->getMessage(),
                'order_id' => $order_id,
                'status' => 500,
            ]);

            $email_addresses = array_filter([
                SettingHelper::getSetting('naris_indoor_email'),
                SettingHelper::getSetting('engrdanish_shipstation_email'),
            ]);

            if (!empty($email_addresses)) {
                Mail::send([], [], function ($message) use ($email_addresses, $order_id, $e) {
                    $message->from(SettingHelper::getSetting('noreply_email_address'));
                    $message->to($email_addresses);
                    $message->subject('Error processing order during label creation');
                    $message->setBody(
                        'Error processing order: ' . $order_id . ' - ' . $e->getMessage(),
                        'text/html'
                    );
                });
            }

            return false;

        }

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
            'action' => 'create_label',
            'request' => json_encode($prepare_data_for_creating_label),
            'response' => $is_sandbox ? 'label created from sandbox' : json_encode($label_api_response),
            'order_id' => $order_id,
            'status' => 200,
        ]);

        $label_email_data = self::prepareLabelEmailData($order_id, $orderData['customerEmail'], $prepare_data_for_creating_label, $packingSlipFileName, $file_name, $order_items_array);
    
        self::update_and_sendLabelEmail($label_email_data, $order, $file_name , $label_api_response);
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
    
    public static function update_and_sendLabelEmail($label_email_data, $order, $file_name , $label_api_response) {

        $tracking_number = $label_api_response ? $label_api_response->trackingNumber : null;
        $shipmentId = $label_api_response ? $label_api_response->shipmentId : null;
        
        $order_update = $order->update(
            [
                'is_shipped' => 1,
                'label_created' => 1,
                'tracking_number' => $tracking_number, 
                'shipmentId' => $shipmentId, 
                'label_link' => $file_name
            ]
        );

        if (!$order_update) {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'update_label',
                'request' => json_encode($label_email_data),
                'response' => 'error updating order',
                'order_id' => $order->id,
                'status' => 500,
            ]);
        } else {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'update_label',
                'request' => json_encode($label_email_data),
                'response' => json_encode($order),
                'order_id' => $order->id,
                'status' => 200,
            ]);
        }


        $email_sent_to_user = ShipstationApiLogs::where('order_id', $order->id)
            ->where('action', 'send_email_to_user')
            ->first();
            
        if ($email_sent_to_user) {
            return Log::info('Email Sent to User: ' . $order->id);
        }

        $mail_send = MailHelper::sendShipstationLabelMail('emails.shipment_label', $label_email_data);
        
        if ($mail_send) {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'send_email_to_user',
                'request' => json_encode($label_email_data),
                'response' => 'email sent to user',
                'order_id' => $order->id,
                'status' => 200,
            ]);

            self::sendAdminEmails($label_email_data, $order, $file_name, $mail_send);
            // Log::info('Shipment label created and email sent successfully.');
        } else {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'send_email_to_user',
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
            SettingHelper::getSetting('engrdanish_shipstation_email'),
            SettingHelper::getSetting('kevin_shipstation_email')
        ]);

        $email_sent_to_admin = ShipstationApiLogs::where('order_id', $order->id)
            ->where('action', 'send_email_to_admin')
            ->first();
            
        if ($email_sent_to_admin) {
            return Log::info('Email Sent to User: ' . $order->id);
        }

    
        if ($email_addresses) {
            $label_email_data['email'] = $email_addresses;
            $mail_send = MailHelper::sendShipstationLabelMail('emails.shipment_label', $label_email_data);
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'send_email_to_admin',
                'request' => json_encode($label_email_data),
                'response' => $mail_send ? 'email sent to admins' : 'error sending email',
                'order_id' => $order->id,
                'status' => $mail_send ? 200 : 500,
            ]);
        } else {
            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipment_label_url') . " {$order->id}",
                'action' => 'send_email_to_admin',
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


    public static function void_label($order) {
        $order = ApiOrder::where('id', $order->id)->first();

        if (empty($order) && !$order->shipmentId) {

            ShipstationApiLogs::create([
                'api_url' => config('services.shipstation.shipstation_void_label'),
                'action' => 'void_label',
                'request' => 'order not found or shipmentId not found',
                'response' => 'order not found or shipmentId not found',
                'order_id' => $order->id,
                'status' => 500,
            ]);
            return false;
        }

        $client = new Client();
        $headers = self::getShipstationHeaders();
        $url = config('services.shipstation.shipstation_void_label');

        $response = $client->post($url, [
            'headers' => $headers,
            'json' => ['shipmentId' => $order->shipmentId],
        ]);

        $responseBody = json_decode($response->getBody()->getContents());

        // update order

        $order->update([
            'void_label' => 1,
        ]);

        ShipstationApiLogs::create([
            'api_url' => $url,
            'action' => 'void_label',
            'request' => json_encode(['shipmentId' => $order->shipmentId]),
            'response' => json_encode($responseBody),
            'order_id' => $order->id,
            'status' => 200,
        ]);

        return true;

    }
    
}