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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;
use App\Helpers\MailHelper;

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
                $res = UtilHelper::sendRequest('POST', $this->_apiBaseURL.'v1/SalesOrders', $this->_body, []);
                break;
            case 'update_order':
                $res = UtilHelper::sendRequest('PUT', $this->_apiBaseURL.'v1/SalesOrders', $this->_body, []);
                break;
            case 'list_order':
                $res = UtilHelper::sendRequest('GET', $this->_apiBaseURL.'v1/SalesOrders', $this->_body, []);
                break;
            case 'retrive_order':
                $res = UtilHelper::sendRequest('GET', $this->_apiBaseURL.'v1/SalesOrders/'.$this->_pathParam, $this->_body, []);
                break;
            
            default:
               $res = UtilHelper::sendRequest('GET', $this->_apiBaseURL.'v1/SalesOrders', $this->_body, []);
                break;

        }
        //var_dump($res);exit;
        $response = json_decode($res);
        // dd($response);
        $order_id = $response[0]->id;
        $reference = $response[0]->code;
        echo $order_id.'-----'.$reference;
        if (!empty($order_id) && !empty($reference)) {
            $data = [
                'order_id' => $order_id,
                'name' =>  'Admin',
                'email' => 'wqszeeshan@gmail.com',
                'contact_email' => 'wqszeeshan@gmail.com',
                'reference' => $reference,
                'subject' => 'Order fullfilled',
                'from' => 'wqszeeshan@gmail.com', 
                'content' => 'Order fullfilled has been fullfilled. from job.'
            ];
            
            $data['email'] = 'wqszeeshan@gmail.com';
            MailHelper::sendMailNotification('emails.admin-order-fullfillment', $data);
        }
        $contact = Contact::where('user_id', $user_id)->first();
        $order = ApiOrder::where('reference', $reference)->update(
            [
                'memberId' => $contact->contact_id,
                'order_id' => $order_id
            ]
        );
        exit;
        // $reference = 'QCOM-70';
        // $apiOrder = ApiOrder::where('reference', $reference)->first();
        // $user_id = $apiOrder['user_id'];
        // echo $user_id;exit;
        // $contact = Contact::where('user_id', $user_id)->first();
        // //echo $contact->contact_id;exit;
        // $order = ApiOrder::where('reference', $reference)->update(
        //     [
        //         'memberId' => $contact->contact_id
        //     ]
        // );
        //dd($contact['user_id']);exit;
        //dd($apiOrder);
        
      
        // $reference = $response[0]->code;
        // //dd($reference);
        // $last_row = DB::table('api_orders')->latest('id')->first();
        // $id = $last_row->id;
        // //echo $last_row->id;exit;
        // // $apiOrder = ApiOrder::where('id', $id)->update(
        // //     [
        // //         'order_id' => $order_id, 
        // //         'reference' => $reference
        // //     ]
        // // );
        // $orders = ApiOrder::where('apiApproval', 'pending')->get();
        // //dd($orders);
        // foreach ($orders as $key => $order) {
        //     $order_items = ApiOrderItem::where('order_id', $order->order_id)->with('product')->first();
        // dd($order_items);
        // }

        // $apiOrder = ApiOrder::where('reference', $reference)->update(
        //     [
        //         'order_id' => $order_id, 
        //         'reference' => $reference
        //     ]
        // );
        //  dd($res);
        // // echo 'here';exit;
        // //dd($apiOrder);

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
