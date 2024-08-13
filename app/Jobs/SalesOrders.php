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
use DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;
use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;



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

    public function __construct($method, $body, $pathParam = null)
    {
        $this->_method = $method;
        $this->_body = $body;
        $this->_pathParam = $pathParam;
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
        $created_date = $response[0]->createdDate;
        echo $order_id . '-----' . $reference;
        $admin_users =  DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');
        $admin_users = $admin_users->toArray();
        $users_with_role_admin = User::select("email")
            ->whereIn('id', $admin_users)
            ->get();
        $order_status = OrderStatus::where('status', 'FullFilled')->first();
        if (!empty($order_id) && !empty($reference)) {
            // $get_id = ApiOrder::where('order_id', $order_id)->where('reference', $reference)->first();
            $data = [
                'order_id' => $order_id,
                'name' =>  'Admin',
                'email' => '',
                'contact_email' => '',
                'reference' => $reference,
                // 'primary_order_id' => $get_id->id,
                'subject' => 'Order ' .' '. 'fulfilled',
                'from' => SettingHelper::getSetting('noreply_email_address'),
                'content' => 'Order fulfilled has been fulfilled.'
            ];
            foreach ($users_with_role_admin as $role_admin) {
                $data['email'] = $role_admin->email;
                $adminTemplate = 'emails.approval-notifications';
                MailHelper::sendMailNotification('emails.admin-order-fullfillment', $data);
            }


            $api_order = ApiOrder::where('reference', $reference)->first();
            $api_order->order_id = $order_id;
            $api_order->isApproved = 1;
            $api_order->order_status_id = $order_status->id;
            $api_order->save();

            $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
            $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');

            $client = new \GuzzleHttp\Client();
            $get_order_payment_url = 'https://api.cin7.com/api/v1/Payments?orderId=' . $order_id;
            $res = $client->request(
                'GET', 
                $get_order_payment_url,
                [
                    'auth' => [
                        $cin7_auth_username,
                        $cin7_auth_password
                    ]                    
                ]
            );

            $get_api_order = $res->getBody()->getContents();
            $get_order = json_decode($get_api_order);

            if (empty($get_order)) {
                $update_order_payment_url = 'https://api.cin7.com/api/v1/Payments';
                $authHeaders = [
                    'headers' => ['Content-Type' => 'application/json'],
                    'auth' => [
                        $cin7_auth_username,
                        $cin7_auth_password,
                    ],
                ];

                // Define the update array with the correct structure
                $update_payment_array = [
                    'orderId' => $order_id,
                    'method' => 'On Account',
                    'paymentDate' => $created_date,
                ];

                // Ensure the JSON data is correctly added to the request
                $res = $client->post($update_order_payment_url, [
                    'headers' => $authHeaders['headers'],
                    'auth' => $authHeaders['auth'],
                    'json' => $update_payment_array
                ]);

                $response = $res->getBody()->getContents();
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
        Log::error($exception->getMessage());
    }
}