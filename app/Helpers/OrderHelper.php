<?php 

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\ApiOrder;
use App\Jobs\SalesOrders;
use App\Models\ApiKeys;
use App\Models\ApiOrderItem;
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
                "unitCost" => $order_item->price,
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
                "internalComments" => $order->internal_comments,
                "productTotal" => 100,
                "freightTotal" => !empty($order->shipment_price) ? $order->shipment_price : 0.00,
                "freightDescription" => !empty($order->logisticsCarrier) && strtolower($order->logisticsCarrier) === 'pickup order' ? 'Pickup Order' : null,
                "surcharge" => null,
                "surchargeDescription" => null,
                "discountTotal" => null,
                "discountDescription" => null,
                "total" => 100,
                "currencyCode" => "USD",
                "currencyRate" => 59.0,
                "currencySymbol" => "$",
                
                "taxStatus" => 2, //(1 = Tax inclusive, 2 = Tax exclusive, 3 = Tax exempt)
                "taxRate" => $order->texClasses->rate,

                "source" => "sample string 62",
                "accountingAttributes" =>
                [
                    "importDate" => "2022-07-13T15:21:16.1946848+12:00",
                    "accountingImportStatus" => "NotImported"
                ],
                "memberEmail" => "wqszeeshan@gmail.com",
                "memberCostCenter" => "sample string 6",
                "memberAlternativeTaxRate" => $order->texClasses->name,
                "costCenter" => !empty($order->paymentTerms) && $order->paymentTerms === 'Pay in Advanced' ? 'Online Sales' : Null,
                "alternativeTaxRate" => $order->texClasses->name,
                "estimatedDeliveryDate" => $delivery_date,
                "salesPersonId" => 10,
                "salesPersonEmail" => "wqszeeshan@gmail.com",
                "paymentTerms" => $order->paymentTerms,
                "customerOrderNo" => $order->po_number,
                "voucherCode" => "sample string 14",
                "deliveryInstructions" => $order->memo,
                "status" => "VOID",
                "invoiceDate" => null,
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
            Log::info('No active api key found');
            return false;
        }

        if ($request_count >= $threshold) {
            Log::info('Request count exceeded');
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



            if (empty($get_order)) {
                $order_created_date_raw = Carbon::now();
                $order_created_date = $order_created_date_raw->format('Y-m-d');
                $order_created_time = $order_created_date_raw->format('H:i:s');
                $api_order_sync_date = $order_created_date . 'T' . $order_created_time . 'Z';
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

                $response = json_decode($Payment_response->getBody()->getContents());
            }
            
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }       
    }
}
