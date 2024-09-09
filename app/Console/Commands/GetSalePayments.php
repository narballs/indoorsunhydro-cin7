<?php

namespace App\Console\Commands;

use App\Helpers\SettingHelper;
use App\Helpers\UtilHelper;
use App\Models\AdminSetting;
use App\Models\ApiErrorLog;
use App\Models\ApiSyncLog;
use App\Models\SalePaymentOrderItem;
use App\Models\SalePayments;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetSalePayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:sale_payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will get all sale payments which have method on account from the cin7 and save in our db';

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
        $admin_setting = AdminSetting::where('option_name', 'enable_cin7_sale_payments')->first();
        if (empty($admin_setting) || $admin_setting->option_value !== 'Yes') {
            $this->error('API Sale Payment setting is off');
            return false;
        }

        $current_date = now()->setTimezone('UTC')->format('Y-m-d H:i:s');
        $payment_sync_log = ApiSyncLog::firstOrCreate(
            ['end_point' => 'https://api.cin7.com/api/v1/Payments'],
            [
                'description' => 'Payments Sync',
                'record_count' => 0,
                'last_synced' => $current_date
            ]
        );


        $total_record_count = 0;

        $last_payment_synced_date = Carbon::parse($payment_sync_log->last_synced)->format('Y-m-d\TH:i:s\Z');
        $client = new \GuzzleHttp\Client();
        $admin_setting_master_key_attempt = AdminSetting::where('option_name', 'master_key_attempt')->first();
        $use_first_credentials = $admin_setting_master_key_attempt->option_value;
        $payment_api_url = "https://api.cin7.com/api/v1/Payments?where=modifieddate>=$last_payment_synced_date&orderType='SalesOrder'&rows=250";
        $orderIds = [];

        $this->processPayments($client, $payment_api_url, $orderIds, $use_first_credentials, $total_record_count);
        sleep(5);
        $this->processOrders($client, $orderIds, $use_first_credentials);

        $payment_sync_log->update([
            'last_synced' => $current_date,
            'record_count' => $total_record_count
        ]);

        $this->info('Total Record Count: ' . $total_record_count);
        $this->info('Finished syncing payments.');
    }

    // private function processPayments($client, $payment_api_url, &$orderIds, &$use_first_credentials, $total_record_count)
    // {
    //     $total_payments_pages = 191;
    //     $requests_per_day = 0;

    //     for ($i = 1; $i <= $total_payments_pages; $i++) {
    //         $credentials = $this->getCin7Credentials($use_first_credentials);
    //         $this->info('Processing payment page #' . $i);

    //         try {
    //             $response = $client->request('GET', $payment_api_url . '&page=' . $i, ['auth' => $credentials]);
    //             if ($response->getStatusCode() !== 200) {
    //                 $this->error('Failed to fetch data from Cin7 API. Status Code: ' . $response->getStatusCode());
    //                 continue;
    //             }

    //             $api_payments = json_decode($response->getBody()->getContents(), true);
    //             $record_count = count($api_payments);
    //             if ($record_count < 1) {
    //                 $this->info('No more records, breaking out.');
    //                 break;
    //             }

    //             foreach ($api_payments as $api_payment) {
    //                 $this->saveOrUpdatePayment($api_payment);
    //                 $orderIds[] = $api_payment['orderId'];
    //                 $total_record_count++;
    //             }
                

    //             $this->updateMasterKeyAttempt(1);
    //             if (++$requests_per_day >= 5000) {
    //                 $this->error('Reached daily limit of 5000 requests. Stopping execution.');
    //                 break;
    //             }

    //             sleep(1);
    //         } catch (\Exception $e) {
    //             $this->handleException($e, $use_first_credentials);
    //             $i--;
    //         }
    //     }
    // }

    // private function processOrders($client, $orderIds, &$use_first_credentials)
    // {
    //     if (empty($orderIds)) {
    //         return;
    //     }

    //     $chunkSize = 20; // Define the chunk size
    //     $orderIdChunks = array_chunk($orderIds, $chunkSize); // Split order IDs into chunks

    //     foreach ($orderIdChunks as $chunkIndex => $chunk) {
    //         $chunkOrderIds = implode(',', $chunk);
    //         $chunkUrl = 'https://api.cin7.com/api/v1/SalesOrders?where=id IN(' . $chunkOrderIds . ')';

    //         $this->info('Processing order chunk #' . ($chunkIndex + 1));

    //         while (true) {
    //             try {
    //                 $response = $client->request('GET', $chunkUrl, ['auth' => $this->getCin7Credentials($use_first_credentials)]);
    //                 if ($response->getStatusCode() !== 200) {
    //                     $this->error('Failed to fetch data from Cin7 API. Status Code: ' . $response->getStatusCode());
    //                     $this->info('Retrying order chunk #' . ($chunkIndex + 1));
    //                     sleep(5); // Wait before retrying
    //                     continue; // Retry the same chunk
    //                 }

    //                 $order_array = json_decode($response->getBody()->getContents(), true);
    //                 if (empty($order_array)) {
    //                     $this->info('No more records in chunk, breaking out.');
    //                     break;
    //                 }

    //                 foreach ($order_array as $order) {
    //                     $this->updateOrderDetails($order);
    //                 }

    //                 $this->updateMasterKeyAttempt(1);
    //                 break; // Break out of the retry loop if successful
    //             } catch (\Exception $e) {
    //                 $this->handleException($e, $use_first_credentials);
    //                 $this->info('Retrying order chunk #' . ($chunkIndex + 1));
    //                 sleep(5); // Wait before retrying
    //             }
    //         }
    //     }
    // }

    private function processPayments($client, $payment_api_url, &$orderIds, &$use_first_credentials, &$total_record_count)
    {
        $total_payments_pages = 191;
        $requests_per_day = 0;

        for ($i = 1; $i <= $total_payments_pages; $i++) {
            $credentials = $this->getCin7Credentials($use_first_credentials);
            $this->info('Processing payment page #' . $i);

            while (true) {
                try {
                    $response = $client->request('GET', $payment_api_url . '&page=' . $i, ['auth' => $credentials]);

                    if ($response->getStatusCode() === 200) {
                        $api_payments = json_decode($response->getBody()->getContents(), true);
                        $record_count = count($api_payments);
                        if ($record_count < 1) {
                            $this->info('No more records, breaking out.');
                            break;
                        }

                        foreach ($api_payments as $api_payment) {
                            $this->saveOrUpdatePayment($api_payment);
                            $orderIds[] = $api_payment['orderId'];
                            $total_record_count++;
                        }

                        $this->updateMasterKeyAttempt(1);
                        if (++$requests_per_day >= 5000) {
                            $this->error('Reached daily limit of 5000 requests. Stopping execution.');
                            break;
                        }

                        sleep(1);
                        break; // Break out of the retry loop if successful
                    } else {
                        if ($use_first_credentials) {
                            $this->info('Non-200 response with first credentials. Switching to second credentials.');
                            $this->updateMasterKeyAttempt(1);
                            $use_first_credentials = false;
                        } else {
                            $this->info('Non-200 response with second credentials. Retrying with first credentials.');
                            $this->updateMasterKeyAttempt(0);
                            $use_first_credentials = true;
                        }

                        sleep(5); // Wait before retrying
                    }
                } catch (\Exception $e) {
                    $this->handleException($e, $use_first_credentials);
                    sleep(5); // Wait before retrying
                }
            }
        }
    }

    private function processOrders($client, $orderIds, &$use_first_credentials)
    {
        if (empty($orderIds)) {
            return;
        }

        $chunkSize = 20; // Define the chunk size
        $orderIdChunks = array_chunk($orderIds, $chunkSize); // Split order IDs into chunks

        foreach ($orderIdChunks as $chunkIndex => $chunk) {
            $chunkOrderIds = implode(',', $chunk);
            $chunkUrl = 'https://api.cin7.com/api/v1/SalesOrders?where=id IN(' . $chunkOrderIds . ')';

            $this->info('Processing order chunk #' . ($chunkIndex + 1));

            while (true) {
                try {
                    $response = $client->request('GET', $chunkUrl, ['auth' => $this->getCin7Credentials($use_first_credentials)]);
                    
                    if ($response->getStatusCode() === 200) {
                        $order_array = json_decode($response->getBody()->getContents(), true);
                        if (empty($order_array)) {
                            $this->info('No more records in chunk, breaking out.');
                            break;
                        }

                        foreach ($order_array as $order) {
                            $this->updateOrderDetails($order);
                        }

                        $this->updateMasterKeyAttempt(1);
                        break; // Break out of the retry loop if successful
                    } else {
                        if ($use_first_credentials) {
                            $this->info('Non-200 response with first credentials. Switching to second credentials.');
                            $this->updateMasterKeyAttempt(1);
                            $use_first_credentials = false;
                        } else {
                            $this->info('Non-200 response with second credentials. Retrying with first credentials.');
                            $this->updateMasterKeyAttempt(0);
                            $use_first_credentials = true;
                        }

                        sleep(5); // Wait before retrying
                    }
                } catch (\Exception $e) {
                    $this->handleException($e, $use_first_credentials);
                    sleep(5); // Wait before retrying
                }
            }
        }
    }



    private function getCin7Credentials($use_first_credentials)
    {
        return $use_first_credentials
            ? [SettingHelper::getSetting('cin7_auth_username'), SettingHelper::getSetting('cin7_auth_password')]
            : [SettingHelper::getSetting('cin7_auth_username'), SettingHelper::getSetting('cin7_auth_password_2')];
    }

    private function saveOrUpdatePayment($api_payment)
    {
        $payment = SalePayments::updateOrCreate(
            [
                'sale_payment_id' => $api_payment['id'],
                'createdDate' => $api_payment['createdDate'],
                'modifiedDate' => $api_payment['modifiedDate'],
                'paymentDate' => $api_payment['paymentDate'],
                'amount' => $api_payment['amount'],
                'method' => strtolower($api_payment['method']),
                'isAuthorized' => $api_payment['isAuthorized'],
                'transactionRef' => $api_payment['transactionRef'],
                'comments' => $api_payment['comments'],
                'orderRef' => $api_payment['orderRef'],
                'paymentImportedRef' => $api_payment['paymentImportedRef'],
                'batchReference' => $api_payment['batchReference'],
                'reconcileDate' => $api_payment['reconcileDate'],
                'branchId' => $api_payment['branchId'],
                'orderType' => $api_payment['orderType']
            ],
            ['orderId' => $api_payment['orderId']],
        );
    }

    private function updateOrderDetails($order)
    {
        $existingPayment = SalePayments::where('orderId', $order['id'])->first();
        if (!$existingPayment) {
            return;
        }

        $existingPayment->update([
            'customer_first_name' => $order['firstName'],
            'customer_last_name' => $order['lastName'],
            'invoice_number' => $order['invoiceNumber'],
            'po_number' => $order['customerOrderNo'],
            'company' => $order['company'],
            'email' => $order['email'],
            "createdBy" => $order["createdBy"],
            "processedBy" => $order["processedBy"],
            "isApproved" => $order["isApproved"],
            "memberId" => $order["memberId"],
            "phone" => $order["phone"],
            "mobile" => $order["mobile"],
            "fax" => $order["fax"],
            "deliveryFirstName" => $order["deliveryFirstName"],
            "deliveryLastName" => $order["deliveryLastName"],
            "deliveryCompany" => $order["deliveryCompany"],
            "deliveryAddress1" => $order["deliveryAddress1"],
            "deliveryAddress2" => $order["deliveryAddress2"],
            "deliveryCity" => $order["deliveryCity"],
            "deliveryState" => $order["deliveryState"],
            "deliveryPostalCode" => $order["deliveryPostalCode"],
            "deliveryCountry" => $order["deliveryCountry"],
            "billingFirstName" => $order["billingFirstName"],
            "billingLastName" => $order["billingLastName"],
            "billingCompany" => $order["billingCompany"],
            "billingAddress1" => $order["billingAddress1"],
            "billingAddress2" => $order["billingAddress2"],
            "billingCity" => $order["billingCity"],
            "billingPostalCode" => $order["billingPostalCode"],
            "billingState" => $order["billingState"],
            "billingCountry" => $order["billingCountry"],
            "branchEmail"   => $order["branchEmail"],
            "projectName" => $order["projectName"],
            "trackingCode" => $order["trackingCode"],
            "internalComments" => $order["internalComments"],
            "productTotal" => $order["productTotal"],
            "freightTotal" => $order["freightTotal"],
            "freightDescription" => $order["freightDescription"],
            "surcharge" => $order["surcharge"],
            "surchargeDescription" => $order["surchargeDescription"],
            "discountTotal" => $order["discountTotal"],
            "discountDescription" ,
            "total" => $order["total"],
            "currencyCode" => $order["currencyCode"],
            "currencyRate" ,
            "currencySymbol" => $order["currencySymbol"],
            "taxStatus" => $order["taxStatus"],
            "taxRate" => $order["taxRate"],
            "source" => $order["source"],
            "customFields" => $order["customFields"],
            "isVoid" => $order["isVoid"],
            "memberEmail" => $order["memberEmail"],
            "memberCostCenter" => $order["memberCostCenter"],
            "memberAlternativeTaxRate" => $order["memberAlternativeTaxRate"],
            "costCenter" => $order["costCenter"],
            "alternativeTaxRate" => $order["alternativeTaxRate"],
            "estimatedDeliveryDate" => $order["estimatedDeliveryDate"],
            "salesPersonId" => $order["salesPersonId"],
            "salesPersonEmail" => $order["salesPersonEmail"],
            "paymentTerms" => $order["paymentTerms"],
            "voucherCode" => $order["voucherCode"],
            "deliveryInstructions" => $order["deliveryInstructions"],
            "cancellationDate" => $order["cancellationDate"],
            "modifiedCOGSDate" => $order["modifiedCOGSDate"],
            "status" => $order["status"],
            "stage" => $order["stage"],
            "invoiceDate" => $order["invoiceDate"],
            "dispatchedDate" => $order["dispatchedDate"],
            "logisticsCarrier" => $order["logisticsCarrier"],
            "logisticsStatus" => $order["logisticsStatus"],
            "ediStatus" => $order["ediStatus"],
            "distributionBranchId" => $order["distributionBranchId"],
            "departmentNumber" => $order["departmentNumber"],
            "storeLocationNumber" => $order["storeLocationNumber"],
            "distributionCenter" => $order["distributionCenter"],
            "order_created_date" => $order["createdDate"],

        ]);

        foreach ($order['lineItems'] as $lineItem) {
            SalePaymentOrderItem::updateOrCreate(

                [
                    'sale_payment_id' => $existingPayment->sale_payment_id,
                    'createdDate' => $lineItem['createdDate'],
                    'transactionId' => $lineItem['transactionId'],
                    'parentId' => $lineItem['parentId'],
                    'productOptionId' => $lineItem['productOptionId'],
                    'integrationRef' => $lineItem['integrationRef'],
                    'sort' => $lineItem['sort'],
                    'code' => $lineItem['code'],
                    'name' => $lineItem['name'],
                    'option1' => $lineItem['option1'],
                    'option2' => $lineItem['option2'],
                    'option3' => $lineItem['option3'],
                    'qty' => $lineItem['qty'],
                    'styleCode' => $lineItem['styleCode'],
                    'barcode' => $lineItem['barcode'],
                    'sizeCodes' => $lineItem['sizeCodes'],
                    'lineComments' => $lineItem['lineComments'],
                    'unitCost' => $lineItem['unitCost'],
                    'unitPrice' => $lineItem['unitPrice'],
                    'uomPrice' => $lineItem['uomPrice'],
                    'discount' => $lineItem['discount'],
                    'uomQtyOrdered' => $lineItem['uomQtyOrdered'],
                    'uomQtyShipped' => $lineItem['uomQtyShipped'],
                    'uomSize' => $lineItem['uomSize'],
                    'qtyShipped' => $lineItem['qtyShipped'],
                    'holdingQty' => $lineItem['holdingQty'],
                    'accountCode' => $lineItem['accountCode']
                ],
                ['orderId' =>  $order['id']],
            );
        }
    }

    // private function updateMasterKeyAttempt($value)
    // {
    //     AdminSetting::where('option_name', 'master_key_attempt')->update(['option_value' => $value]);
    // }

    // private function handleException($exception, &$use_first_credentials)
    // {
    //     $this->error('Error: ' . $exception->getMessage());

    //     ApiErrorLog::create([
    //         'payload' => $exception->getMessage(),
    //         'exception' => $exception->getCode()
    //     ]);

    //     // Swap credentials and retry
    //     $use_first_credentials = !$use_first_credentials;
    //     $this->updateMasterKeyAttempt(0);
    // }

    private function updateMasterKeyAttempt($attempt)
    {
        AdminSetting::updateOrCreate(
            ['option_name' => 'master_key_attempt'],
            ['option_value' => $attempt]
        );
    }

    private function handleRateLimitExceeded(&$use_first_credentials)
    {
        $this->updateMasterKeyAttempt(0);
        $this->info('Rate limit exceeded. Switching credentials and retrying.');
        sleep(10); // Wait before retrying with new credentials
        $use_first_credentials = !$use_first_credentials;
    }

    private function handleException(\Exception $e, &$use_first_credentials)
    {
        if ($e->getCode() === 429) {
            $this->error('Rate limit exceeded. Switching credentials and retrying.');
            $this->handleRateLimitExceeded($use_first_credentials);
        } else {
            $this->error('Exception encountered: ' . $e->getMessage());
        }
    }
}
