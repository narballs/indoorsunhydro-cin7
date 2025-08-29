<?php 

namespace App\Helpers;

use App\Models\OrderStatus;
use Carbon\Carbon;
use App\Models\ApiOrder;
use App\Jobs\SalesOrders;
use App\Models\ApiErrorLog;
use App\Models\ApiKeys;
use App\Models\ApiOrderItem;
use App\Models\PaymentInformationLog;
use App\Models\SpecificAdminNotification;
use Illuminate\Support\Facades\Log;

class OrderHelper {


	public static function get_order_data_to_process($order) {
		$userSwitchUser = [];
        if (!empty($order->user['contact'])) {
            foreach ($order->user['contact'] as $contact) {
                $userSubmiter  =   $contact->email . ',' . $contact->firstName . ',' . $contact->lastName;
            }
        }
        if (!empty($order->user_switch)) {
            $userSwitchUser = $order->user_switch;
        } else {
            $userSwitchUser = "";
        }

        $orderSubmiterDetail = $userSubmiter . ',' . $userSwitchUser;

        $userSwitchUser = $order->user_switch;
        $memberId = $order->memberId;
        $order_items = ApiOrderItem::with('product.options')->where('order_id', $order->id)->get();
     
        $lineItems = [];
        foreach ($order_items as $order_item) {
            $lineItems[] = [
                "id" => $order_item->product->product_id,
                "createdDate" => '2022-07-31T23:43:38Z',
                "transaction" => '12',
                "parentId" => 1,
                "productId" => $order_item->product->product_id,
                "productOptionId" => null,
                "integrationRef" => $orderSubmiterDetail,
                "sort" => 16,
                "code" => $order_item->product->code,
                "name" => $order_item->product->name,
                "option1" => $order_item->product->option1,
                "option2" => $order_item->product->option2,
                "option3" => $order_item->product->option,
                "qty" => $order_item->quantity,
                "styleCode" => "sample string 1",
                "barcode" => "sample string 2",
                "sizeCodes" => "sample string 4",
                "lineComments" => null,
                "unitCost" => null,
                "unitPrice" => $order_item->price,
                "discount" => null,
                "qtyShipped" => 7,
                "holdingQty" => 8,
                "accountCode" => null,
                "stockControl" => "Undefined",
                "stockMovements" => [
                    [
                        "batch" => "sample string 1",
                        "quantity" => 2.0,
                        "serial" => "sample string 3"
                    ],
                    [
                        "batch" => "sample string 1",
                        "quantity" => 2.0,
                        "serial" => "sample string 3"
                    ],
                ],
                "sizes" => [
                    [
                        "name" => "sample string 1",
                        "code" => "sample string 2",
                        "barcode" => "sample string 3",
                        "qty" => 4.0
                    ]
                ],
            ];
        }
        
        $order_data = [];

        unset($order['primaryId']);
        unset($order['memberId']);
        
        // $dateCreated = $order->createdDate;

        // $dateCreated = Carbon::now();

        // if (!empty($order->date)) {
        
        //     $delivery_date = (!empty($order->date) && Carbon::parse($order->date)->lt($dateCreated))
        //         ? $dateCreated
        //     : Carbon::parse($order->date);
        // } else {
        //     $delivery_date = null;
        // }


        $dateCreated = Carbon::now();

        if (!empty($order->date)) {
            $delivery_date = (!empty($order->date) && Carbon::parse($order->date)->lt($dateCreated))
                ? Carbon::parse($dateCreated)->addHours(24)
                : Carbon::parse($order->date);
        } else {
            $delivery_date = Carbon::parse($dateCreated)->addHours(24);
        }

        $card_number = $order->card_number; 


        $pick_disclaimer = 'Customer Must present exact physical credit card used during purchase ' . (!empty($card_number) ? $card_number : '') . ' must show a physical ID card of the owner of the credit card to pick up the order. No exceptions.
        As an extra layer of fraud prevention, call the customer to see if they actually placed an order for the item being picked up just to see how they respond to the inquiry or even if they pick up the call.';

        $buy_list_discount = !empty($order->buylist_id) ? $order->buylist_discount : null;

        
        $order_data = [
            [
                $order,
                "createdDate" => $dateCreated,
                "modifiedDate" => "",
                "createdBy" => $order->createdBy,
                "processedBy" => $order->processedBy,
                "isApproved" => true,
                "reference" => $order->reference,
                "memberId" => $memberId,
                "branchId" => 3,
                "branchEmail" => "wqszeeshan@gmail.com",
                "projectName" => "",
                "trackingCode" => "",
                // "internalComments" => $orderSubmiterDetail,
                // "internalComments" => !empty($order->logisticsCarrier) && strtolower($order->logisticsCarrier) === 'pickup order' ? $order->internal_comments . ' '. $pick_disclaimer : $order->internal_comments,
                "internalComments" => !empty($order->logisticsCarrier) && $order->is_stripe == 1  && strtolower($order->logisticsCarrier) === 'pickup order' 
                ? $order->internal_comments . ' ' . $pick_disclaimer 
                : $order->internal_comments,

                "productTotal" => null,
                "freightTotal" => !empty($order->shipment_price) ? $order->shipment_price : 0.00,
                "freightDescription" => !empty($order->logisticsCarrier) && strtolower($order->logisticsCarrier) === 'pickup order' ? 'Pickup Order' : null,
                "surcharge" => null,
                "surchargeDescription" => null,
                "discountTotal" => null,
                "discountDescription" => !empty($order->buylist_id) && !empty($order->buylist_discount) ? 'Buylist Discount : ' . $buy_list_discount : null,
                "total" => null,
                "currencyCode" => "USD",
                "currencyRate" => 1.0,
                "currencySymbol" => "$",
                
                "taxStatus" => 2, //(1 = Tax inclusive, 2 = Tax exclusive, 3 = Tax exempt)
                "taxRate" => isset($order->custom_tax_rate_percent) ? $order->custom_tax_rate_percent : $order->texClasses->rate,

                "source" => null,
                "accountingAttributes" =>
                [
                    "importDate" => null,
                    "accountingImportStatus" => "NotImported"
                ],
                "memberEmail" => "wqszeeshan@gmail.com",
                "memberCostCenter" => null,
                "memberAlternativeTaxRate" => $order->texClasses->name,
                "costCenter" => !empty($order->paymentTerms) && $order->paymentTerms === 'Pay in Advanced' ? 'Online Sales' : Null,
                "alternativeTaxRate" => !empty($order->custom_tax_rate_percent) ? $order->custom_tax_rate_percent : $order->texClasses->name,
                "estimatedDeliveryDate" => $delivery_date,
                "salesPersonId" => 10,
                "salesPersonEmail" => "wqszeeshan@gmail.com",
                "paymentTerms" => $order->paymentTerms,
                "customerOrderNo" => $order->po_number,
                "voucherCode" => null,
                "deliveryInstructions" => $order->memo,
                "status" => "VOID",
                "invoiceDate" => !empty($order->paymentTerms) && $order->paymentTerms === 'Pay in Advanced'  ? $dateCreated : null,
                "invoiceNumber" => 4232,
                "dispatchedDate" => null,
                "logisticsCarrier" => $order->logisticsCarrier,
                "logisticsStatus" => 1,
                "distributionBranchId" => 0,
                'deliveryFirstName' => $order->DeliveryFirstName,
                'deliveryLastName' => $order->DeliveryLastName,
                'deliveryCompany' => $order->DeliveryCompany,
                'deliveryAddress1'  => $order->DeliveryAddress1,
                'deliveryAddress2' => $order->DeliveryAddress2,
                'deliveryCity' => $order->DeliveryCity,
                'deliveryState' => $order->DeliveryState,
                'deliveryPostalCode' => $order->DeliveryZip,
                'deliveryCountry' => $order->DeliveryCountry,
                'phone' => $order->DeliveryPhone,
                'billingFirstName' => $order->BillingFirstName,
                'billingLastName' => $order->BillingLastName,
                'billingCompany' => $order->BillingCompany,
                'billingAddress1' => $order->BillingAddress1,
                'billingAddress2' => $order->BillingAddress2,
                'billingCity' => $order->BillingCity,
                'billingState' => $order->BillingState,
                'billingPostalCode' => $order->BillingZip,
                'billingCountry' => $order->BillingCountry,
                // 'billingPhone' => $order->BillingPhone,
                "lineItems" => $lineItems

            ],
        ];

        return $order_data;
	}


    public static function update_order_payment_in_cin7($order_id) {
        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password_3');

        $cin7api_key_for_other_jobs =  ApiKeys::where('password', $cin7_auth_password)
        ->where('is_active', 1)
        ->where('is_stop', 0)
        ->first();

        $api_key_id = null;
        
        if (!empty($cin7api_key_for_other_jobs)) {
            $cin7_auth_username = $cin7api_key_for_other_jobs->username;
            $cin7_auth_password = $cin7api_key_for_other_jobs->password;
            $threshold = $cin7api_key_for_other_jobs->threshold;
            $request_count = !empty($cin7api_key_for_other_jobs->request_count) ? $cin7api_key_for_other_jobs->request_count : 0;
            $api_key_id = $cin7api_key_for_other_jobs->id;
        } else {
            // Log::info('No active api key found');
            return false;
        }

        if ($request_count >= $threshold) {
            // Log::info('Request count exceeded');
            return false;
        }

        try {
            $url = 'https://api.cin7.com/api/v1/Payments';
            $client = new \GuzzleHttp\Client();
            $get_order_payment_url = 'https://api.cin7.com/api/v1/Payments?where=orderId=' . $order_id;
            $get_response = $client->request(
                'GET', 
                $get_order_payment_url,
                [
                    'auth' => [
                        $cin7_auth_username,
                        $cin7_auth_password
                    ]                    
                ]
            );


            UtilHelper::saveEndpointRequestLog('Sync Payments' , $url , $api_key_id);

            $get_api_order = $get_response->getBody()->getContents();
            $get_order = json_decode($get_api_order);

            $order_created_date_raw = Carbon::now();
            $order_created_date = $order_created_date_raw->format('Y-m-d');
            $order_created_time = $order_created_date_raw->format('H:i:s');
            $api_order_sync_date = $order_created_date . 'T' . $order_created_time . 'Z';



            if (empty($get_order)) {
                
                $url = 'https://api.cin7.com/api/v1/Payments';
                $authHeaders = [
                    'headers' => ['Content-Type' => 'application/json'],
                    'auth' => [
                        $cin7_auth_username,
                        $cin7_auth_password,
                    ],
                ];

                $update_array = [
                    [
                        'orderId' => $order_id,
                        'method' => 'On Account',
                        'paymentDate' => $api_order_sync_date,
                    ]
                ];

                $authHeaders['json'] = $update_array;

                $Payment_response = $client->post($url, $authHeaders);

                UtilHelper::saveEndpointRequestLog('Sync Payments' , $url , $api_key_id);

                $response = json_decode($Payment_response->getBody()->getContents() , true);



                // update logs of payment information

                if (!empty($response) && isset($response[0]['success']) && $response[0]['success'] === true) {
                    $payment_id = $response[0]['id'] ?? null;

                    $create_payment_information_log = new PaymentInformationLog();
                    $create_payment_information_log->payment_id = $payment_id;
                    $create_payment_information_log->order_id = $order_id;
                    $create_payment_information_log->created_date = $order_created_date . ' ' . $order_created_time;
                    $create_payment_information_log->payment_date = $order_created_date . ' ' . $order_created_time;
                    $create_payment_information_log->status = 'success';
                    $create_payment_information_log->save();

                    try {
                        // Fetch the created payment from Cin7
                        $client = new \GuzzleHttp\Client();
                        $get_order_payment_url = 'https://api.cin7.com/api/v1/Payments/' . $payment_id;

                        $get_cin7_payment_response = $client->request('GET', $get_order_payment_url, [
                            'auth' => [$cin7_auth_username, $cin7_auth_password]
                        ]);

                        UtilHelper::saveEndpointRequestLog('Sync Payments', $get_order_payment_url, $api_key_id);

                        $get_cin7_payment = $get_cin7_payment_response->getBody()->getContents();
                        $get_payment = json_decode($get_cin7_payment, true);

                        

                        if (!empty($get_payment['id'])) {
                            $update_payment_information_log = PaymentInformationLog::where('order_id', $order_id)
                                ->where('payment_id', $get_payment['id'])
                                ->first();
                        
                            if ($update_payment_information_log) {
                                $update_payment_information_log->order_reference = $get_payment['orderRef'] ?? null;
                                $update_payment_information_log->method = $get_payment['method'] ?? $update_payment_information_log->method;
                                $update_payment_information_log->amount = $get_payment['amount'] ?? null;
                                $update_payment_information_log->order_type = $get_payment['orderType'] ?? null;
                                $update_payment_information_log->branch_id = $get_payment['branchId'] ?? null;
                                $update_payment_information_log->save();
                            }
                        }

                    } 
                    catch (\Exception $e) {
                       
                        
                        ApiErrorLog::create([
                            'payload' => $e->getMessage(),
                            'exception' => $e->getCode()
                        ]);

                    }

                } else {
                    // Handle failure
                    $create_payment_information_log = new PaymentInformationLog();
                    $create_payment_information_log->order_id = $order_id;
                    $create_payment_information_log->method = null;
                    $create_payment_information_log->order_reference = null;
                    $create_payment_information_log->created_date = $order_created_date . ' ' . $order_created_time;
                    $create_payment_information_log->payment_date = $order_created_date . ' ' . $order_created_time;
                    $create_payment_information_log->status = 'failed';
                    $create_payment_information_log->save();

                    Log::error('Payment creation failed for order_id: ' . $order_id . ' | Response: ' . json_encode($response));
                }

            }
            
        } catch (\Exception $e) {
            // Log::error('Exception occurred while creating payment');
            
            ApiErrorLog::create([
                'payload' => $e->getMessage(),
                'exception' => $e->getCode()
            ]);
        }       
    }


    // public static function check_order_in_cin7($order_id)
    // {
    //     Log::info('Checking order in CIN7 for order ID: ' . $order_id);
        
    //     $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
    //     $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password_3');

    //     $cin7api_key_for_other_jobs = ApiKeys::where('password', $cin7_auth_password)
    //         ->where('is_active', 1)
    //         ->where('is_stop', 0)
    //         ->first();

    //     $api_key_id = null;
    //     $threshold = 0;
    //     $request_count = 0;

    //     if ($cin7api_key_for_other_jobs) {
    //         $cin7_auth_username = $cin7api_key_for_other_jobs->username;
    //         $cin7_auth_password = $cin7api_key_for_other_jobs->password;
    //         $threshold = $cin7api_key_for_other_jobs->threshold;
    //         $request_count = $cin7api_key_for_other_jobs->request_count ?? 0;
    //         $api_key_id = $cin7api_key_for_other_jobs->id;
    //     } else {
    //         return false;
    //     }

    //     if ($request_count >= $threshold) {
    //         return false;
    //     }

    //     $api_order = ApiOrder::where('id', $order_id)->first();
    //     Log::info('CIN7 Order Check: ' . $api_order->reference . ' | API Order ID: ' . $api_order->id);
    //     if (empty($api_order)) {
    //         return false;
    //     }

    //     try {
    //         $client = new \GuzzleHttp\Client();
    //         $reference = $api_order->reference;
    //         $where = urlencode("reference='$reference'");
    //         $url = "https://api.cin7.com/api/v1/SalesOrders?where={$where}&limit=1";

    //         $res = $client->request('GET', $url, [
    //             'auth' => [
    //                 $cin7_auth_username,
    //                 $cin7_auth_password,
    //             ],
    //             'timeout' => 60,
    //         ]);

    //         UtilHelper::saveEndpointRequestLog('Get Sales Order', $url, $api_key_id);

    //         $cin7_order = $res->getBody()->getContents();
    //         $get_order = json_decode($cin7_order);

    //         Log::info('CIN7 Order Check: ' . $reference . ' | Response: ' . json_encode($get_order));

    //         $order_status = OrderStatus::where('status', 'FullFilled')->first();
    //         $order_status_id = $order_status ? $order_status->id : null;

    //         if (!empty($get_order) && isset($get_order[0]) && isset($get_order[0]->id)) {
    //             $api_order->update([
    //                 'order_id' => $get_order[0]->id,
    //                 'isApproved' => 1,
    //                 'order_status_id' => $order_status_id,
    //                 'updated_at' => now()
    //             ]);

    //             $data = [
    //                 'order_id' => $get_order[0]->id,
    //                 'name' => 'Admin',
    //                 'email' => '',
    //                 'contact_email' => '',
    //                 'reference' => $reference,
    //                 'subject' => 'Order fulfilled',
    //                 'from' => SettingHelper::getSetting('noreply_email_address'),
    //                 'content' => 'Order has been fulfilled.'
    //             ];

    //             $specific_admin_notifications = SpecificAdminNotification::all();
    //             if ($specific_admin_notifications->isNotEmpty()) {
    //                 foreach ($specific_admin_notifications as $specific_admin_notification) {
    //                     if (!$specific_admin_notification->receive_order_notifications) {
    //                         continue;
    //                     }
    //                     $data['email'] = $specific_admin_notification->email;
    //                     MailHelper::sendMailNotification('emails.admin-order-fullfillment', $data);
    //                 }
    //             }

    //             return true;
    //         } else {
    //             return false;
    //         }
    //     } catch (\Exception $e) {
    //         return false;
    //     }
    // }

    public static function check_order_in_cin7($order_id)
    {
        Log::info('Checking order in CIN7 for order ID: ' . $order_id);

        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password_3');

        $cin7api_key_for_other_jobs = ApiKeys::where('password', $cin7_auth_password)
            ->where('is_active', 1)
            ->where('is_stop', 0)
            ->first();

        $api_key_id = null;
        $threshold = 0;
        $request_count = 0;

        if ($cin7api_key_for_other_jobs) {
            $cin7_auth_username = $cin7api_key_for_other_jobs->username;
            $cin7_auth_password = $cin7api_key_for_other_jobs->password;
            $threshold = $cin7api_key_for_other_jobs->threshold;
            $request_count = $cin7api_key_for_other_jobs->request_count ?? 0;
            $api_key_id = $cin7api_key_for_other_jobs->id;
        } else {
            // Log::warning('CIN7 Order Check: No active API key found', ['order_id' => $order_id]);
            return false;
        }

        if ($request_count >= $threshold) {
            // Log::warning('CIN7 Order Check: Request count exceeded', ['order_id' => $order_id]);
            return false;
        }

        $api_order = ApiOrder::where('id', $order_id)->first();
        if (empty($api_order)) {
            // Log::warning('CIN7 Order Check: No ApiOrder found', ['order_id' => $order_id]);
            return false;
        }
        // Log::info('CIN7 Order Check: ' . $api_order->reference . ' | API Order ID: ' . $api_order->id);

        try {
            $reference = $api_order->reference;
            $where = urlencode("reference='$reference'");
            $url = "https://api.cin7.com/api/v1/SalesOrders?where={$where}&limit=1";

            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', $url, [
                'auth' => [
                    $cin7_auth_username,
                    $cin7_auth_password,
                ],
                'timeout' => 60,
            ]);

            UtilHelper::saveEndpointRequestLog('Get Sales Order', $url, $api_key_id);

            $cin7_order = $res->getBody()->getContents();
            $get_order = json_decode($cin7_order);

            // Log::info('CIN7 Order Check: ' . $reference . ' | Response: ' . json_encode($get_order));

            $order_status = OrderStatus::where('status', 'FullFilled')->first();
            $order_status_id = $order_status ? $order_status->id : null;

            if (!empty($get_order) && isset($get_order[0]) && isset($get_order[0]->id)) {
                $api_order->update([
                    'order_id' => $get_order[0]->id,
                    'isApproved' => 1,
                    'order_status_id' => $order_status_id,
                    'updated_at' => now()
                ]);
                // Log::info('CIN7 Order Check: ApiOrder updated', ['api_order_id' => $api_order->id]);

                $data = [
                    'order_id' => $get_order[0]->id,
                    'name' => 'Admin',
                    'email' => '',
                    'contact_email' => '',
                    'reference' => $reference,
                    'subject' => 'Order fulfilled',
                    'from' => SettingHelper::getSetting('noreply_email_address'),
                    'content' => 'Order has been fulfilled.'
                ];

                $specific_admin_notifications = SpecificAdminNotification::all();
                if ($specific_admin_notifications->isNotEmpty()) {
                    foreach ($specific_admin_notifications as $specific_admin_notification) {
                        if (!$specific_admin_notification->receive_order_notifications) {
                            continue;
                        }
                        $data['email'] = $specific_admin_notification->email;
                        MailHelper::sendMailNotification('emails.admin-order-fullfillment', $data);
                    }
                }

                return true;
            } else {
                // Log::warning('CIN7 Order Check: No matching order found in Cin7 response', ['reference' => $reference]);
                return false;
            }
        } catch (\Exception $e) {
            // Log::error('CIN7 Order Check: Exception', ['order_id' => $order_id, 'error' => $e->getMessage()]);
            return false;
        }
    }


}
