<?php

namespace App\Console\Commands;

use App\Helpers\SettingHelper;
use App\Helpers\UtilHelper;
use App\Models\AdminSetting;
use App\Models\ApiErrorLog;
use App\Models\ApiSyncLog;
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
    // public function handle()
    // {
    //     $admin_setting = AdminSetting::where('option_name', 'enable_cin7_sale_payments')->first();
    //     if (empty($admin_setting) || $admin_setting->option_value != 'Yes') {
    //         $this->error('Api Sale Payment setting is off');
    //         return false;
    //     }

    //     $current_date = Carbon::now()->setTimezone('UTC')->format('Y-m-d H:i:s');
    //     $payment_sync_log = ApiSyncLog::where('end_point', 'https://api.cin7.com/api/v1/Payments')->first();
    //     if (empty($payment_sync_log)) {
    //         $payment_sync_log = new ApiSyncLog();
    //         $payment_sync_log->end_point = 'https://api.cin7.com/api/v1/Payments';
    //         $payment_sync_log->description = 'Payments Sync';
    //         $payment_sync_log->record_count = 0;
    //         $payment_sync_log->last_synced = $current_date;
    //         $payment_sync_log->save();
    //     }

    //     $last_payment_synced_date = $payment_sync_log->last_synced;
    //     $payment_sync_raw_date = Carbon::parse($last_payment_synced_date);
    //     $payment_sync_date = $payment_sync_raw_date->format('Y-m-d');
    //     $payment_sync_time = $payment_sync_raw_date->format('H:i:s');
    //     $api_formatted_payment_sync_date = $payment_sync_date . 'T' . $payment_sync_time . 'Z';


    //     $total_payments_pages = 191;
    //     $total_record_count = 0;

    //     $client = new \GuzzleHttp\Client();

    //     $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
    //     $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');

    //     // $payment_api_url = 'https://api.cin7.com/api/v1/Payments?where=method=On Account&where=orderType=SalesOrder&where=modifieddate>='. $api_formatted_payment_sync_date . '&rows=250';
    //     $payment_api_url = 'https://api.cin7.com/api/v1/Payments?where=' . urlencode('method=On Account AND orderType=SalesOrder AND modifieddate>=') . $api_formatted_payment_sync_date . '&rows=250';

    //     for ($i = 1; $i <= $total_payments_pages; $i++) {
    //         // $this->info('Processing page#' . $i);
    //         try {

    //             $res = $client->request(
    //                 'GET', 
    //                 $payment_api_url . '&page=' . $i, 
    //                 [
    //                     'auth' => [
    //                         $cin7_auth_username,
    //                         $cin7_auth_password
    //                     ]
                     
    //                 ]
    //             );

    //             dd($res->getBody()->getContents());

    //             UtilHelper::saveDailyApiLog('sync_payments');


    //             $api_payments = $res->getBody()->getContents();
          
    //             $api_payments = json_decode($api_payments);
    //             $record_count = count($api_payments);
    //             $total_record_count += $record_count; 
    //             $this->info('Record Count per page #--------------------------' .$record_count);


    //             $this->info('Record Count => ' . $record_count);
                
    //             if ($record_count < 1 || empty($record_count)) {
    //                 $this->info('----------------break-----------------');
    //                 break;
    //             }

    //             foreach ($api_payments as $api_payment) {
    //                 $this->info($api_payment->id);
    //                 $this->info('---------------------------------------');
    //                 $this->info('Processing Products '. $api_payment->orderId . ' '. $api_payment->orderRef . ' => ' . $api_payment->modifiedDate);
    //                 $this->info('---------------------------------------');


    //                 if (!empty($api_payment)) {
    //                     $sale_payment = new SalePayments();
    //                     $sale_payment->createdDate = $api_payment->createdDate;
    //                     $sale_payment->modifiedDate = $api_payment->modifiedDate;
    //                     $sale_payment->paymentDate = $api_payment->paymentDate;
    //                     $sale_payment->amount = $api_payment->amount;
    //                     $sale_payment->method = $api_payment->method;
    //                     $sale_payment->isAuthorized = $api_payment->isAuthorized;
    //                     $sale_payment->transactionRef = $api_payment->transactionRef;
    //                     $sale_payment->comments = $api_payment->comments;
    //                     $sale_payment->orderId = $api_payment->orderId;
    //                     $sale_payment->orderRef = $api_payment->orderRef;
    //                     $sale_payment->paymentImportedRef = $api_payment->paymentImportedRef;
    //                     $sale_payment->batchReference = $api_payment->batchReference;
    //                     $sale_payment->reconcileDate = $api_payment->reconcileDate;
    //                     $sale_payment->branchId = $api_payment->branchId;
    //                     $sale_payment->orderType = $api_payment->orderType;
    //                     $sale_payment->save();
    //                 } else {
    //                     continue;
    //                 }
    //             }
    //         }
    //         catch (\Exception $e) {
    //             $msg = $e->getMessage();
    //             $errorlog = new ApiErrorLog();
    //             $errorlog->payload = $e->getMessage();
    //             $errorlog->exception = $e->getCode();
    //             $errorlog->save();
    //         }
    //     }

    //     $payment_sync_log->last_synced = $current_date;
    //     $payment_sync_log->record_count = $total_record_count;
    //     $payment_sync_log->save();

    //     $this->info('Total Record Count#' . $total_record_count);
    //     $this->info('------------------------------');
    //     $this->info('-------------Finished------------------');
    // }
    public function handle()
    {
        $admin_setting = AdminSetting::where('option_name', 'enable_cin7_sale_payments')->first();
        if (empty($admin_setting) || $admin_setting->option_value != 'Yes') {
            $this->error('Api Sale Payment setting is off');
            return false;
        }

        $current_date = Carbon::now()->setTimezone('UTC')->format('Y-m-d H:i:s');
        $payment_sync_log = ApiSyncLog::where('end_point', 'https://api.cin7.com/api/v1/Payments')->first();
        if (empty($payment_sync_log)) {
            $payment_sync_log = new ApiSyncLog();
            $payment_sync_log->end_point = 'https://api.cin7.com/api/v1/Payments';
            $payment_sync_log->description = 'Payments Sync';
            $payment_sync_log->record_count = 0;
            $payment_sync_log->last_synced = $current_date;
            $payment_sync_log->save();
        }

        $last_payment_synced_date = $payment_sync_log->last_synced;
        $api_formatted_payment_sync_date = Carbon::parse($last_payment_synced_date)->format('Y-m-d\TH:i:s\Z');

        $total_payments_pages = 191;
        $total_record_count = 0;

        $client = new \GuzzleHttp\Client();

        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');

        $payment_api_url = 'https://api.cin7.com/api/v1/Payments?where=modifieddate>='. $api_formatted_payment_sync_date . "&method='On Account'&orderType='SalesOrder'&rows=250";
        // dd($payment_api_url);
        for ($i = 1; $i <= $total_payments_pages; $i++) {
            $this->info('Processing page #' . $i);

            try {
                $res = $client->request(
                    'GET',
                    $payment_api_url . '&page=' . $i,
                    [
                        'auth' => [
                            $cin7_auth_username,
                            $cin7_auth_password
                        ]
                    ]
                );

                if ($res->getStatusCode() == 200) {
                    $api_payments = json_decode($res->getBody()->getContents());

                    $record_count = count($api_payments);
                    $total_record_count += $record_count;

                    $this->info('Record Count per page #' . $record_count);

                    if ($record_count < 1 || empty($record_count)) {
                        $this->info('No more records, breaking out.');
                        break;
                    }

                    foreach ($api_payments as $api_payment) {
                        if (!empty($api_payment)) {
                            if (str_starts_with($api_payment->orderRef, 'Stripe-Paid-CC') || str_starts_with($api_payment->orderRef, 'DEV4-QCOM')) {
                                $existingPayment = SalePayments::where('orderId', $api_payment->orderId)->first();
                                if (!$existingPayment) {
                                    $sale_payment = new SalePayments();
                                    $sale_payment->sale_payment_id = $api_payment->id;
                                    $sale_payment->createdDate = $api_payment->createdDate;
                                    $sale_payment->modifiedDate = $api_payment->modifiedDate;
                                    $sale_payment->paymentDate = $api_payment->paymentDate;
                                    $sale_payment->amount = $api_payment->amount;
                                    $sale_payment->method = $api_payment->method;
                                    $sale_payment->isAuthorized = $api_payment->isAuthorized;
                                    $sale_payment->transactionRef = $api_payment->transactionRef;
                                    $sale_payment->comments = $api_payment->comments;
                                    $sale_payment->orderId = $api_payment->orderId;
                                    $sale_payment->orderRef = $api_payment->orderRef;
                                    $sale_payment->paymentImportedRef = $api_payment->paymentImportedRef;
                                    $sale_payment->batchReference = $api_payment->batchReference;
                                    $sale_payment->reconcileDate = $api_payment->reconcileDate;
                                    $sale_payment->branchId = $api_payment->branchId;
                                    $sale_payment->orderType = $api_payment->orderType;
                                    $sale_payment->save();
                                }
                            }
                        }
                    }
                } else {
                    $this->error('Failed to fetch data from Cin7 API. Status Code: ' . $res->getStatusCode());
                    continue;
                }
            } catch (\Exception $e) {
                $errorlog = new ApiErrorLog();
                $errorlog->payload = $e->getMessage();
                $errorlog->exception = $e->getCode();
                $errorlog->save();
                $this->error('Error processing page #' . $i . ': ' . $e->getMessage());
            }
        }

        $payment_sync_log->last_synced = $current_date;
        $payment_sync_log->record_count = $total_record_count;
        $payment_sync_log->save();

        $this->info('Total Record Count: ' . $total_record_count);
        $this->info('Finished syncing payments.');
    }

}
