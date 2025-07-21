<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\UtilHelper;
use App\Models\ApiOrder;
use App\Models\ApiOrderItem;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;
use App\Helpers\MailHelper;
use App\Helpers\OrderHelper;
use App\Helpers\SettingHelper;
use App\Models\AdminSetting;
use App\Models\Order;
use App\Models\OrderJobLog;
use App\Models\SpecificAdminNotification;
use Carbon\Carbon;
use Google\Service\MyBusinessAccountManagement\Admin;

class SalesOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $_method;
    protected $_body;
    protected $_apiBaseURL = 'https://api.cin7.com/api/';
    protected $_pathParam;
    protected $_global_primary_id;

    public function __construct($method, $body, $pathParam = null)
    {
        $this->_method = $method;
        $this->_body = $body;
        $this->_pathParam = $pathParam;

        
        $orderModel = $body[0][0] ?? null;

        if ($orderModel && $orderModel instanceof \App\Models\ApiOrder) {
            $this->_global_primary_id = $orderModel->id;
        } else {
            $this->_global_primary_id = null;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        switch ($this->_method) {
            
            case 'create_order':
                $res = UtilHelper::sendRequest('POST', $this->_apiBaseURL . 'v1/SalesOrders', $this->_body, ['api_end_point' => 'create_order']);
            break;
            case 'update_order':
                $res = UtilHelper::sendRequest('PUT', $this->_apiBaseURL . 'v1/SalesOrders', $this->_body, ['api_end_point' => 'update_order']);
            break;
            case 'list_order':
                $res = UtilHelper::sendRequest('GET', $this->_apiBaseURL . 'v1/SalesOrders', $this->_body, ['api_end_point' => 'list_order']);
            break;
            case 'retrieve_order':
                $res = UtilHelper::sendRequest('GET', $this->_apiBaseURL . 'v1/SalesOrders/' . $this->_pathParam, $this->_body, ['api_end_point' => 'retrieve_order']);
            break;

            default:
                $res = UtilHelper::sendRequest('GET', $this->_apiBaseURL . 'v1/SalesOrders', $this->_body, []);
            break;
        }

        

        $response = json_decode($res);
        $order_id = $response[0]->id;
        $reference = $response[0]->code;
        echo $order_id . '-----' . $reference;


        $status = $response[0]->success ?? null; // will be true, false, or null
        $status_text = is_bool($status) ? ($status ? 'success' : 'failed') : 'unknown';

        $errors = $response[0]->errors ?? ['no error'];
        $error_message = is_array($errors) ? implode(', ', $errors) : (string) $errors;

        $admin_users =  DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');
        $admin_users = $admin_users->toArray();
        $users_with_role_admin = User::select("email")
            ->whereIn('id', $admin_users)
            ->get();
        $specific_admin_notifications = SpecificAdminNotification::all();
        $order_status = OrderStatus::where('status', 'FullFilled')->first();
        if (!empty($order_id) && !empty($reference)) {
            $data = [
                'order_id' => $order_id,
                'name' =>  'Admin',
                'email' => '',
                'contact_email' => '',
                'reference' => $reference,
                'subject' => 'Order ' .' '. 'fulfilled',
                'from' => SettingHelper::getSetting('noreply_email_address'),
                'content' => 'Order fulfilled has been fulfilled.'
            ];
            
            if (count($specific_admin_notifications) > 0) {
                foreach ($specific_admin_notifications as $role_admin) {
                    $data['email'] = $role_admin->email;
                    $adminTemplate = 'emails.approval-notifications';
                    MailHelper::sendMailNotification('emails.admin-order-fullfillment', $data);
                }
            }


            $api_order = ApiOrder::where('reference', $reference)->first();
            $api_order->order_id = $order_id;
            $api_order->isApproved = 1;
            $api_order->order_status_id = $order_status->id;
            $api_order->save();

            $record_order_job_logs = AdminSetting::where('option_name', 'record_order_job_logs')->first();
            $record_order_job_logs = !empty($record_order_job_logs) ? strtolower($record_order_job_logs->option_value) : 'no';
            if ($record_order_job_logs == 'yes') {
                $attempt = $this->attempts();
                $primary_id = $this->_global_primary_id;
                OrderJobLog::create([
                    'api_order_id'   => $api_order->id,
                    'reference'      => $reference,
                    'attempt_number' => $this->attempts(),
                    'message'        => "[" . now() . "] Job {$status_text} after " . $this->attempts() . " attempts. Response: " . json_encode($response),
                    'logged_at'      => now(),
                ]);
            }



            if (
                (!empty($api_order->DeliveryAddress1) || !empty($api_order->DeliveryAddress2)) &&
                (SettingHelper::startsWithPOBox($api_order->DeliveryAddress1) || SettingHelper::startsWithPOBox($api_order->DeliveryAddress2))
            ) {
                $orderID = $api_order->id;

                $email_addresses = array_filter([
                    SettingHelper::getSetting('naris_indoor_email'),
                    SettingHelper::getSetting('engrdanish_shipstation_email'),
                ]);

                if (!empty($email_addresses)) {
                    Mail::send([], [], function ($message) use ($email_addresses, $orderID) {
                        $message->from(SettingHelper::getSetting('noreply_email_address'));
                        $message->to($email_addresses);
                        $message->subject('Manual Processing Required (PO Box) – Order ID: ' . $orderID);
                        $message->setBody(
                            'Order ID: ' . $orderID . ' requires manual processing due to a PO Box specified in the delivery address. Please review and address this order at your earliest convenience.',
                            'text/html'
                        );
                    });
                }

            }


            $add_payment_in_cin7_for_order = AdminSetting::where('option_name', 'add_payment_in_cin7_for_order')->first();
            if (!empty($add_payment_in_cin7_for_order) && strtolower($add_payment_in_cin7_for_order->option_value) == 'yes') {
                if (!empty($api_order) && !empty($api_order->order_id)) {
                    OrderHelper::update_order_payment_in_cin7($api_order->order_id);
                }
            }
            
        } else {

            $record_order_job_logs = AdminSetting::where('option_name', 'record_order_job_logs')->first();
            $record_order_job_logs = !empty($record_order_job_logs) ? strtolower($record_order_job_logs->option_value) : 'no';
            if ($record_order_job_logs == 'yes') {
                $attempt = $this->attempts();
                $primary_id = $this->_global_primary_id;
                $apiOrder = $primary_id ? ApiOrder::where('id', $primary_id)->first() : null;

                if ($apiOrder && $primary_id) {
                    OrderJobLog::create([
                        'api_order_id'   => $apiOrder->id,
                        'reference'      => $apiOrder->reference,
                        'attempt_number' => $attempt,
                        'message'        => "[" . now() . "] Job failed after {$attempt} attempts. Error: " . $error_message,
                        'logged_at'      => now(),
                    ]);
                }
            }
        }


        exit;
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        
        
        $record_order_job_logs = AdminSetting::where('option_name', 'record_order_job_logs')->first();
        $record_order_job_logs = !empty($record_order_job_logs) ? strtolower($record_order_job_logs->option_value) : 'no';
        if ($record_order_job_logs == 'yes') {
            $attempt = $this->attempts();
            $primary_id = $this->_global_primary_id;
            $apiOrder = $primary_id ? ApiOrder::where('id', $primary_id)->first() : null;

            $message = "[" . now() . "] FINAL FAILURE at attempt #{$attempt}: " . $exception->getMessage();
            if ($apiOrder && $primary_id) {
                try {
                    OrderJobLog::create([
                        'api_order_id'   => $apiOrder->id,
                        'reference'      => $apiOrder->reference,
                        'attempt_number' => $attempt,
                        'message'        => $message,
                        'logged_at'      => now(),
                    ]);
                    Log::info('OrderJobLog created successfully.');
                } catch (\Exception $e) {
                    Log::error('Failed to create OrderJobLog', ['error' => $e->getMessage()]);
                }
            }
        }

        Log::error($exception->getMessage());
    }
}