<?php

namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderComment;
use App\Models\OrderStatus;
use GuzzleHttp\Client;
use App\Models\ApiOrder;
use App\Models\ApiOrderItem;
use App\Models\AdminSetting;
use App\Models\Contact;
use App\Jobs\SalesOrders;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;
use App\Helpers\MailHelper;
use App\Helpers\OrderHelper;
use App\Models\BuyList;
use App\Models\ProductBuyList;
use App\Models\TaxClass;
use App\Models\User;
use App\Models\Product;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Auth;
use App\Helpers\UserHelper;
use DateTime;

use App\Helpers\SettingHelper;
use App\Helpers\UtilHelper;
use App\Models\SalePaymentOrderItem;
use App\Models\SalePayments;
use App\Models\ShippingMethod;
use App\Models\ShippingQuoteSetting;
use App\Models\SpecificAdminNotification;
use Illuminate\Support\Facades\DB;

class OrderManagementController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:Admin'])->except('order_full_fill', 'send_invitation_email');
    }

    public function index(Request $request)
    {
        $auto_full_fill = AdminSetting::where('option_name', 'auto_full_fill')->first();
        $auto_full_fill_value = $auto_full_fill->option_value;
        if ($auto_full_fill_value == 1) {
            $auto_fullfill = true;
        } else {
            $auto_fullfill = false;
        }


        $auto_create_label = AdminSetting::where('option_name', 'auto_create_label')->first();
        $auto_create_label_value = strtolower($auto_create_label->option_value);
        if ($auto_create_label_value == 'yes') {
            $auto_createLabel = true;
        } else {
            $auto_createLabel = false;
        }

        $sort_by_desc = $request->get('sort_by_desc');
        $sort_by_asc = $request->get('sort_by_asc');
        $sort_by_created_at = $request->get('sort_by_date');

        $search = $request->get('search');
        $orders_query = ApiOrder::with(['createdby', 'processedby', 'contact'])
        ->with([
            'secondary_contact' => function ($query) {
                $query->withTrashed();
            },
            'primary_contact' => function ($query) {
                $query->withTrashed();
            }
        ]);
        $option = AdminSetting::where('option_name', 'auto_full_fill')->first();
        $auto_fulfill = $option->option_value;

        $autoCreateLabel = AdminSetting::where('option_name', 'auto_create_label')->first();
        $auto_createlabel = $autoCreateLabel->option_value;

        if (!empty($search)) {
            $orders_query = $orders_query->where('order_id', 'LIKE', '%' . $search . '%')
                ->orWhere('createdDate', 'like', '%' . $search . '%')
                ->orWhere('modifiedDate', 'like', '%' . $search . '%')
                ->orWhere('reference', 'like', '%' . $search . '%')
                ->orWhere('total', 'like', '%' . $search . '%')
                ->orWhere('stage', 'like', '%' . $search . '%')
                ->orWhereHas('contact', function ($orders_query) use ($search) {
                    $orders_query->where('firstName', 'like', '%' . $search . '%')
                        ->orWhere('lastName', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('company', 'like', '%' . $search . '%');
                });
        }
        
        if (!empty($request->sort_by_desc)) {
            $orders_query = $orders_query->orderBy('id' , 'Desc');
        }
        if (!empty($request->sort_by_asc)) {
            $orders_query = $orders_query->orderBy('id' , 'Asc');
        }


        if (!empty($request->sort_by_created_at)) {
            if ($sort_by_created_at == 'Asc') {
                $orders_query = $orders_query->orderBy('created_at' , 'Asc');
            }
            if ($sort_by_created_at == 'Desc') {
                $orders_query = $orders_query->orderBy('created_at' , 'Desc');
            }
        }
        $show_alert = false;
        $show_processing_alert = false;
        $show_unfulled_orders = $request->get('show_unfulled_orders');
        $show_processing_orders = $request->get('show_processing_orders');
        if (!empty($show_unfulled_orders)) {
            $orders = $orders_query->where('order_id', null)->where('isApproved', 0)
            ->where('created_at', '<', now()->subHours(3))
            ->whereNotIn('payment_status', ['unpaid', 'pending'])
            ->orderBy('id' , 'Desc')
            ->whereNotIn('payment_status', ['unpaid', 'pending'])
            ->paginate(10)
            ->withQueryString();
        }
        if (!empty($show_processing_orders)) {
            $orders = $orders_query->where('order_id', null)->where('isApproved', 5)
            ->where('created_at', '<', now()->subHours(3))
            ->where('payment_status', 'pending')
            ->orderBy('id' , 'Desc')
            ->paginate(10)
            ->withQueryString();
        }
        
        $orders =  $orders_query->orderBy('id' , 'Desc')->paginate(10)->withQueryString();
        $pending_orders = ApiOrder::with(['createdby', 'processedby', 'contact'])
            ->whereNull('order_id')
            ->where('isApproved', 0)
            ->whereNotIn('payment_status', ['unpaid', 'pending'])
            ->where('created_at', '<', now()->subHours(3))
            ->orderBy('id', 'desc')
            ->get();
        $processing_orders = ApiOrder::with(['createdby', 'processedby', 'contact'])
            ->whereNull('order_id')
            ->where('isApproved', 5)
            ->where('payment_status', 'pending')
            ->where('created_at', '<', now()->subHours(3))
            ->orderBy('id', 'desc')
            ->get();
        $processing_order_ids = [];
        $get_order_ids = [];
        if (count($pending_orders) > 0) {
            
            foreach ($pending_orders as $order) {
                $contact = Contact::where('contact_id', $order->memberId)->first();
                if (!empty($contact) && $contact->is_test_user == 0) {
                    $get_order_ids[] = $order->id;
                } else{
                    continue;
                }
            }
            $show_alert = true;
        }

        if (count($processing_orders) > 0) {
            
            foreach ($processing_orders as $order) {
                $contact = Contact::where('contact_id', $order->memberId)->first();
                if (!empty($contact) && $contact->is_test_user == 0) {
                    $processing_order_ids[] = $order->id;
                } else{
                    continue;
                }
            }
            $show_processing_alert = true;
        }

        $order_ids = implode(',', $get_order_ids);
        $processingOrderIds = implode(',', $processing_order_ids);

        $shipping_quotes = ShippingQuoteSetting::where('status' , 1)->get();
        $po_box_carrier_code = AdminSetting::where('option_name', 'po_box_shipping_carrier_code')->first();
        $po_box_service_code  = AdminSetting::where('option_name', 'po_box_shipping_service_code')->first();
        $po_box_order_shipping_text  = AdminSetting::where('option_name', 'po_box_order_shipping_text')->first();

        $unshipped_orders_ids = ApiOrder::with('shipstation_api_logs')
            ->where('is_shipped', 0)
            ->where('label_created', 0)
            ->where('is_stripe', 1)
            ->where('shipment_price', '>', 0)
            ->whereNotNull('shipstation_orderId')
            ->where('payment_status', 'paid')
            ->where('isApproved', 1)
            ->whereNull('buylist_id')
            ->where('created_at', '>=', '2025-01-09 12:23:51')
            ->where('shipping_carrier_code', 'ups_walleted')
            ->whereHas('shipstation_api_logs' , function($q) {
                $q->where('action' , 'create_label');
            })
            ->pluck('id')->toArray();

        return view('admin/orders', compact(
            'orders','order_ids','processingOrderIds','po_box_carrier_code',
            'po_box_service_code','po_box_order_shipping_text',
            'shipping_quotes','search',
            'pending_orders','show_alert',
            'processing_orders','show_processing_alert',
            'auto_createlabel','auto_createLabel',
            'auto_fulfill', 'auto_fullfill',
            'sort_by_desc', 'sort_by_asc' ,
            'sort_by_created_at',
            'unshipped_orders_ids'
        ));
    }

    public function show($id)
    {
        $auto_full_fill = AdminSetting::where('option_name', 'auto_full_fill')->first();
        $auto_full_fill_value = $auto_full_fill->option_value;
        if ($auto_full_fill_value == 1) {
            $auto_fullfill = true;
        } else {
            $auto_fullfill = false;
        }
        $statuses = OrderStatus::all();
        $order = ApiOrder::where('id', $id)->with('texClasses' , 'orderJobLog','drop_shipped')
        ->with([
            'secondary_contact' => function ($query) {
                $query->withTrashed();
            },
            'primary_contact' => function ($query) {
                $query->withTrashed();
            }
        ])
        ->first();
        // $tax_class = TaxClass::where('is_default', 1)->first();
        $createdDate = $order->created_at;
        $formatedDate = $createdDate->format('jS \of F Y h:i:s A');
        $orderCreatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $createdDate, 'America/Los_Angeles');
        $currentTime = Carbon::now();



        $time_diff = $orderCreatedDate->diffInMinutes($currentTime);
        $time_difference_seconds = date('s');


        $user = User::withTrashed()->where('id', $order->user_id)->first();
        $all_ids = UserHelper::getAllMemberIds($user);
            $contact_ids = Contact::whereIn('id', $all_ids)
                ->pluck('contact_id')
                ->toArray();
        $customer = ApiOrder::with(['createdby'])->whereIn('memberId', $contact_ids)
        ->with('contact' , function($query) {
            $query->orderBy('company');
        })
        ->with([
            'secondary_contact' => function ($query) {
                $query->withTrashed();
            },
            'primary_contact' => function ($query) {
                $query->withTrashed();
            }
        ])
        ->with('apiOrderItem.product')
        ->where('id' , $id)
        ->first();
        
        $option_ids = ApiOrderItem::where('order_id', $id)->pluck('option_id')->toArray();
        $orderitems = $this->option_ids = $option_ids;
        $orderitems = ApiOrderItem::with(['product.options' => function ($q) {
            $q->whereIn('option_id', $this->option_ids);
        }])->where('order_id', $id)->get();
        $tax_class = TaxClass::where('name', $customer->contact->tax_class)->first();
        $orderComment = OrderComment::where('order_id', $id)->with('comment')->get();
        // $products  = Product::with('options', 'brand', 'categories')->where('status' , '!=' , 'Inactive')->get();
        
        $job = DB::table('jobs')->where('payload', 'like', '%' . $order->reference . '%')->first();
        if (!empty($job)) {
            $is_processing = true;
        }
        else {
            $is_processing = false;
        }

        $order_statuses = OrderStatus::orderby('id', 'desc')->get();
        $discount_variation_value = 0;
        $discount_variation = null;
        $enable_discount_setting = AdminSetting::where('option_name', 'enable_discount')->first();
        if (!empty($enable_discount_setting) && strtolower($enable_discount_setting->option_value) == 'yes') {
            if (!empty($order->discount)) {
                $discount_variation_value = $order->discount->discount_variation_value;
                $discount_variation = $order->discount->discount_variation;
            }
        }

        $tax=0;
        $tax_rate = 0;
        $subtotal = 0;
        $tax_without_discount = 0;
        $subtotal = $order->total;
        $discount_amount = $order->discount_amount;
        if (isset($discount_variation_value) && !empty($discount_variation_value) && $discount_amount > 0) {
            $discount_variation_value = $discount_variation_value;
            if (!empty($tax_class)) {
                $tax_rate = $tax_class->rate;
                $tax_without_discount = $subtotal * ($tax_rate / 100);
                if (!empty($discount_variation) && $discount_variation == 'percentage') {
                    $tax = $tax_without_discount - ($tax_without_discount * ($discount_variation_value / 100));
                } else {
                    $tax = $tax_without_discount - $discount_variation_value;
                }
            }

        } else {
            if (!empty($tax_class)) {
                $tax_rate = $tax_class->rate;
                $tax = $subtotal * ($tax_rate / 100);
            }
        }
        
        $po_box_carrier_code = AdminSetting::where('option_name', 'po_box_shipping_carrier_code')->first();
        $po_box_service_code  = AdminSetting::where('option_name', 'po_box_shipping_service_code')->first();
        $po_box_order_shipping_text  = AdminSetting::where('option_name', 'po_box_order_shipping_text')->first();
        $shipping_quotes = ShippingQuoteSetting::where('status' , 1)->get();
        
        return view('admin/order-details', compact(
            'order',
            'tax_class',
            'orderitems',
            'orderComment',
            'statuses',
            'customer',
            'formatedDate',
            'time_diff',
            'time_difference_seconds',
            'auto_fullfill',
            'is_processing',
            'order_statuses',
            'tax',
            'po_box_order_shipping_text',
            'po_box_carrier_code',
            'po_box_service_code',
            'shipping_quotes'

        ));
    }

    public function addComments(Request $request)
    {
        $comment = $request->input('comment');
        $order_id = $request->input('order_id');
        //dd($request->input('order_id'));
        $order_comment = new OrderComment;
        $order_comment->order_id = $order_id;
        $order_comment->comment = $comment;
        $order_comment->save();
    }

    public function updateStatus(Request $request)
    {

        $order_id = $request->order_id_status;
        $status = $request->status;
        $order = ApiOrder::where('id', $order_id)->first();
        $order->status = $status;
        $order->save();
        $order_comment = new OrderComment;
        $comment = 'Status changed to ' . $status;
        $order_comment->order_id = $order_id;
        $order_comment->comment = $comment;
        $order_comment->save();
        if ($status == 2) {
            $isVoid = true;
        } else {
            $isVoid = false;
        }
        $json_body =
            [
                [
                    "id" => $order->order_id,
                    "status" => $status,
                    "isVoid" =>  $isVoid
                ]
            ];
        SalesOrders::dispatch('update_order', $json_body)->onQueue(env('QUEUE_NAME'));
        return redirect()->back()->with('success', 'Order Status changed successfully !');
    }

    // public function create()
    // {
    //     $order = [];

    //     $order = [
    //         [
    //             "createdDate" => "2022-07-13T15:21:16.1946848+12:00",
    //             "modifiedDate" => "2022-07-13T15:21:16.1946848+12:00",
    //             "createdBy" => 17,
    //             "processedBy" => 18,
    //             "isApproved" => true,
    //             "reference" => "",
    //             "memberId" => 7,
    //             "firstName" => "sample string 22",
    //             "lastName" => "sample string 23",
    //             "company" => "sample string 24",
    //             "email" => "wqszeeshan@gmail.com",
    //             "phone" => "sample string 26",
    //             "mobile" => "sample string 27",
    //             "fax" => "sample string 28",
    //             "deliveryFirstName" => "sample string 29",
    //             "deliveryLastName" => "sample string 30",
    //             "deliveryCompany" => "sample string 31",
    //             "deliveryAddress1" => "sample string 32",
    //             "deliveryAddress2" => "sample string 33",
    //             "deliveryCity" => "sample string 34",
    //             "deliveryState" => "sample string 35",
    //             "deliveryPostalCode" => "sample string 36",
    //             "deliveryCountry" => "sample string 37",
    //             "billingFirstName" => "sample string 38",
    //             "billingLastName" => "sample string 39",
    //             "billingCompany" => "sample string 40",
    //             "billingAddress1" => "sample string 41",
    //             "billingAddress2" => "sample string 42",
    //             "billingCity" => "sample string 43",
    //             "billingPostalCode" => "sample string 44",
    //             "billingState" => "sample string 45",
    //             "billingCountry" => "sample string 46",
    //             "branchId" => 47,
    //             "branchEmail" => "wqszeeshan@gmail.com",
    //             "projectName" => "sample string 49",
    //             "trackingCode" => "sample string 50",
    //             "internalComments" => "sample string 51",
    //             "productTotal" => 52.0,
    //             "freightTotal" => null,
    //             "freightDescription" => null,
    //             "surcharge" => null,
    //             "surchargeDescription" => null,
    //             "discountTotal" => null,
    //             "discountDescription" => null,
    //             "total" => 56.0,
    //             "currencyCode" => "USD",
    //             "currencyRate" => 59.0,
    //             "currencySymbol" => "sample string 60",
    //             "taxStatus" => "Undefined",
    //             "taxRate" => 61.0,
    //             "source" => "sample string 62",
    //             "isVoid" => true,
    //             "accountingAttributes" =>
    //             [
    //                 "importDate" => "2022-07-13T15:21:16.1946848+12:00",
    //                 "accountingImportStatus" => "NotImported"
    //             ],
    //             "memberEmail" => "wqszeeshan@gmail.com",
    //             "memberCostCenter" => "sample string 6",
    //             "memberAlternativeTaxRate" => "sample string 7",
    //             "costCenter" => "sample string 8",
    //             "alternativeTaxRate" => null,
    //             "estimatedDeliveryDate" => "2022-07-13T15:21:16.1946848+12:00",
    //             "salesPersonId" => 10,
    //             "salesPersonEmail" => "wqszeeshan@gmail.com",
    //             "paymentTerms" => "sample string 12",
    //             "customerOrderNo" => "sample string 13",
    //             "voucherCode" => "sample string 14",
    //             "deliveryInstructions" => "sample string 15",
    //             "status" => "VOID",
    //             "stage" => "sample string 4",
    //             "invoiceDate" => null,
    //             "invoiceNumber" => null,
    //             "dispatchedDate" => "",
    //             "logisticsCarrier" => "sample string 2",
    //             "logisticsStatus" => 1,
    //             "distributionBranchId" => 0,
    //             "lineItems" =>
    //             [
    //                 [
    //                     "id" => 10,
    //                     "createdDate" => "2022-07-13T15:21:16.1946848+12:00",
    //                     "transactionId" => 12,
    //                     "parentId" => 1,
    //                     "productId" => 13,
    //                     "productOptionId" => 14,
    //                     "integrationRef" => "sample string 15",
    //                     "sort" => 16,
    //                     "code" => "sample string 17",
    //                     "name" => "sample string 18",
    //                     "option1" => "sample string 19",
    //                     "option2" => "sample string 20",
    //                     "option3" => "sample string 21",
    //                     "qty" => 1.0,
    //                     "styleCode" => "sample string 1",
    //                     "barcode" => "sample string 2",
    //                     "sizeCodes" => "sample string 4",
    //                     "lineComments" => null,
    //                     "unitCost" => 1.0,
    //                     "unitPrice" => 1.0,
    //                     "discount" => null,
    //                     "qtyShipped" => 7.0,
    //                     "holdingQty" => 8.0,
    //                     "accountCode" => null,
    //                     "stockControl" => "Undefined",
    //                     "stockMovements" => [
    //                         [
    //                             "batch" => "sample string 1",
    //                             "quantity" => 2.0,
    //                             "serial" => "sample string 3"

    //                         ],
    //                         [
    //                             "batch" => "sample string 1",
    //                             "quantity" => 2.0,
    //                             "serial" => "sample string 3"
    //                         ],
    //                     ],
    //                     "sizes" => [
    //                         [
    //                             "name" => "sample string 1",
    //                             "code" => "sample string 2",
    //                             "barcode" => "sample string 3",
    //                             "qty" => 4.0
    //                         ]
    //                     ]
    //                 ],

    //             ]
    //         ],
    //     ];
    //     SalesOrders::dispatch('create_order', [
    //         'json' =>
    //         $order
    //     ])->onQueue(env('QUEUE_NAME'));
    //     exit;

    //     $client = new \GuzzleHttp\Client();
    //     $url = "https://api.cin7.com/api/v1/SalesOrders/";
    //     $response = $client->post($url, [
    //         'headers' => ['Content-type' => 'application/json'],
    //         'auth' => [
    //             SettingHelper::getSetting('cin7_auth_username'),
    //             SettingHelper::getSetting('cin7_auth_password')
    //         ],
    //         'json' =>
    //         $order,
    //     ]);

    //     echo $response->getBody();
    // }

    public function show_api_order($id)
    {
        $order = ApiOrder::with(['createdby', 'processedby'])->where('id', $id)->first();
        $statuses = OrderStatus::all();
        $customer = Contact::where('contact_id', $order->memberId)->first();
        $orderitems = ApiOrderItem::where('order_id', $id)->with('product')->get();
        return view('admin/api-order-details', compact('order', 'statuses', 'orderitems', 'customer'));
    }


    public function order_full_fill(Request $request)
    {
        $order_id = $request->order_id;
        $order = ApiOrder::where('id', $order_id)
            ->with('user.contact')
            ->with('texClasses')
            ->first();
        $job = DB::table('jobs')->where('payload', 'like', '%' . $order->reference . '%')->first();
        
        if (empty($job)) {

            $contact = Contact::where('contact_id', $order->memberId)->first();

            if (!empty($contact) && $contact->is_test_user == 0) {

                if (!empty($order->is_stripe) && $order->is_stripe == 1 ) {
                    if (strtolower($order->payment_status) === 'paid') {
                        $order_data = OrderHelper::get_order_data_to_process($order);
                        SalesOrders::dispatch('create_order', $order_data)->onQueue(env('QUEUE_NAME'));

                        return response()->json([
                            'status' => 'success',
                        ]);
                    }
                } 
                else {
                    $order_data = OrderHelper::get_order_data_to_process($order);
                    SalesOrders::dispatch('create_order', $order_data)->onQueue(env('QUEUE_NAME'));

                    return response()->json([
                        'status' => 'success',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                ]);
            }
            
        }

        return response()->json([
            'status' => 'failed',
        ]);
    }

    public function cancelOrder(Request $request)
    {
        $order_id = $request->input('order_id');
        $user_id = Auth::user()->id;
        $order_status = OrderStatus::where('status', 'Cancelled')->first();
        

        $quotes_id = BuyList::insertGetId([
            'title' => 'cancel order order',
            'status' => 'Public',
            'description' => 'description',
            'user_id' => $user_id,
            'type' => 'quote',
            'created_at' => SupportCarbon::now(),
        ]);


        $currentOrder = ApiOrder::where('id', $order_id)->with(
            'contact',
            'user.contact',
            'apiOrderItem.product.options',
            'texClasses',
            'order_refund'
        )->first();
        $order_items = ApiOrderItem::with('product.options')->where('order_id', $order_id)->get();
        foreach ($order_items as $order_item) {
            $buy_list_product =  ProductBuyList::create([
                'list_id' => $quotes_id,
                'product_id' => $order_item->product_id,
                'option_id' => $order_item->option_id,
                'quantity' => $order_item->quantity,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        $previous_order_status = OrderStatus::where('id', $currentOrder->order_status_id)->first();
       
        $user_cancel_order = ApiOrder::where(['id' =>  $order_id])->update([
            'isApproved' => 2,
            'order_status_id' => $order_status->id,
            'updated_at' => Carbon::now(),
        ]);

        $best_products = Product::where('status', '!=', 'Inactive')
                ->orderBy('views', 'DESC')
                ->limit(4)
                ->get();

        $get_order = ApiOrder::with('contact','user.contact','apiOrderItem','apiOrderItem.product.options','texClasses','apiOrderItem')
        ->where('id', $order_id)->first();

        $current_order_status = OrderStatus::where('id', $get_order->order_status_id)->first();

        if (!empty($get_order) && (!empty($current_order_status) && $current_order_status->status == 'Cancelled') && $get_order->isApproved == 2) {
            UtilHelper::update_product_stock_on_cancellation($get_order);
        }

        $order_items = ApiOrderItem::with('order.texClasses','product', 'product.options')
            ->where('order_id', $get_order->id)
            ->get();

        $count = $order_items->count();

        $customer = ApiOrder::with(['createdby'])
        ->where('memberId', $get_order->memberId)
        ->with('contact', function ($query) {
            $query->orderBy('company');
        })
        ->with('apiOrderItem.product')
        ->where('id', $order_id)->first();

        $addresses = [
            'billing_address' => [
                'firstName' => $customer->contact->firstName,
                'lastName' => $customer->contact->lastName,
                'address1' => $customer->contact->address1,
                'address2' => $customer->contact->address2,
                'city' => $customer->contact->city,
                'state' => $customer->contact->state,
                'zip' => $customer->contact->postCode,
                'mobile' => $customer->contact->mobile,
                'phone' => $customer->contact->phone,
            ],
            'shipping_address' => [
                'postalAddress1' => $customer->contact->postalAddress1,
                'postalAddress2' => $customer->contact->postalAddress2,
                'phone' => $customer->contact->phone,
                'postalCity' => $customer->contact->postalCity,
                'postalState' => $customer->contact->postalState,
                'postalPostCode' => $customer->contact->postalPostCode,
            ],
            'payment_terms' => !empty($customer->contact->paymentTerms) ? $customer->contact->paymentTerms : '30 Days from Invoice',
            'best_product' => $best_products,
            'user_email' => $customer->contact->email,
            'currentOrder' => $currentOrder,
            'count' => $count,
            'order_id' => $order_id,
            'company' => $currentOrder->contact->company,
            'order_status' => 'updated',
            'delievery_method' => $currentOrder->logisticsCarrier,
            'previous_order_status' => !empty($previous_order_status->status) ? $previous_order_status->status : '',
            'new_order_status' => !empty($current_order_status->status) ? $current_order_status->status : '',
            'reference' => $currentOrder->reference,
        ];

        $name = $customer->contact->firstName;
        $email = $customer->contact->email;
        $reference = $currentOrder->reference;

        $data = [
            'name' => $name,
            'email' => $email,
            'subject' => 'Order Cancelled',
            'reference' => $reference,
            'order_items' => $order_items,
            'dateCreated' => now(),
            'addresses' => $addresses,
            'best_product' => $best_products,
            'user_email' => $email,
            'currentOrder' => $currentOrder,
            'count' => $count,
            'from' => SettingHelper::getSetting('noreply_email_address'),
        ];

        if (!empty($email)) {
            $data['subject'] = 'Your Indoorsun Hydro order #' . $currentOrder->id . ' has been Cancelled';
            $data['email'] = $email;
            MailHelper::sendMailNotification('emails.cancel_order_email_template', $data);
        }

        // $specific_admin_notifications = SpecificAdminNotification::all();
        // if (count($specific_admin_notifications) > 0) {
        //     foreach ($specific_admin_notifications as $specific_admin_notification) {
        //         $subject = 'Indoorsun Hydro order #' . $currentOrder->id . ' has been Cancelled';
        //         $adminTemplate = 'emails.admin-order-received';
        //         $data['subject'] = $subject;
        //         $data['email'] = $specific_admin_notification->email;
        //         MailHelper::sendMailNotification('emails.cancel_order_email_template', $data);
        //     }
        // }

        return response()->json([
            'buy_list_product' =>  $buy_list_product,
            'status' => 'success',
            'message' => 'Order Cancel successfully ! ',

        ]);
    }

    public function check_order_status(Request $request)
    {
        sleep(60);
        $order = ApiOrder::where('id', $request->order_id)->first();
        if ($order->order_id != null) {
            $msg = 'Order fullfilled successfully';
        } else {
            $msg = 'Order fullfilled failed please try later';
        }
        return response()->json([
            'status' => $msg
        ]);
    }

    public function mutli_check_order_status(Request $request)
    {
        sleep(20);
        $ids = $request->ids;
        $orders = ApiOrder::where('id', $ids)->get();
        foreach ($orders as $order) {
            if ($order->order_id != null) {
                $msg = 'Order fullfilled successfully';
            } else {
                $msg = 'Order fullfilled failed please try later';
            }
            return response()->json([
                'status' => $msg
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $order_id = $request->id;
        $api_order_id = ApiOrder::where('id', $order_id)->first();
        $api_order_item = ApiOrderItem::where('order_id', $api_order_id->id)->get();
        foreach ($api_order_item as $item) {
            $item->delete();
        }
        ApiOrder::find($order_id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Order deleted successfully ! ',
        ]);
    }

    public function deleteAllOrders(Request $request)
    {
        $ids = $request->ids;
        $orders = ApiOrder::whereIn('id', explode(",", $ids))->get();
        foreach ($orders as $order) {
            $order_items = ApiOrderItem::where('order_id', $order->id)->get();
            foreach ($order_items as $item) {
                $item->delete();
            }
            $order->delete();
        }
        return response()->json([
            'success' => 'Order deleted successfully ! ',
        ]);
    }

    public function multiOrderFullFill(Request $request)
    {
        $order_ids = $request->ids;
        if (!empty($order_ids)) {
            $orders = ApiOrder::whereIn('id', explode(",", $order_ids))
                ->where('order_id', null)
                ->where('isApproved', 0)
                ->with('user.contact')
                ->get();
            if (count($orders) > 0) {
                foreach ($orders as $order) {
                    if (!empty($order->is_stripe) && $order->is_stripe == 1 ) {
                        if (strtolower($order->payment_status) === 'paid') {
                            $order_data = OrderHelper::get_order_data_to_process($order);
                            SalesOrders::dispatch('create_order', $order_data)
                                ->onQueue(env('QUEUE_NAME'));
                        } else {
                            return response()->json([
                                'message' => 'Your order payment is not paid !',
                                'status' => 401
                            ]);
                        }
                    } else {
                        $order_data = OrderHelper::get_order_data_to_process($order);
                        SalesOrders::dispatch('create_order', $order_data)
                            ->onQueue(env('QUEUE_NAME'));
                    }
                }
            } else {
                return response()->json([
                    'message' => 'Your order is already fullfill !',
                    'status' => 401
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Your order request is null !',
                'status' => 401
            ]);
        }
    }

    public function multiple_cancle_orders(Request $request)
    {
        $order_id = $request->ids;
        $order_status = OrderStatus::where('status', 'Cancelled')->first();
        if (!empty($order_id)) {
            $orders = ApiOrder::whereIn('id', explode(",", $order_id))
                ->with('user.contact')
                ->get();
            if (count($orders) > 0) {
                foreach ($orders as $order) {
                    if ($order->isApproved != 0) {
                        return response()->json([
                            'status' => 400,
                            'message' => 'Your order is already cancel!'
                        ]);
                    } else {
                        $order->isApproved = 2;
                        $order->order_status_id = $order_status->id;
                        $order->save();
                    }
                }
                return response()->json([
                    'status' => 200,
                    'message' => 'Order canceled successfully ! ',
                ]);
            } else {
                return response()->json([
                    'message' => 'Your order is null !',
                    'status' => 401
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Your order request is null !',
                'status' => 402
            ]);
        }
    }
    

    // send order to shipstation

    public function send_order_to_shipstation(Request $request) {

        $order_id = $request->order_id;
        $currentOrder = ApiOrder::where('id', $order_id)->first();
        if (!empty($currentOrder)) {
            if ($currentOrder->is_stripe == 1 && $currentOrder->shipstation_orderId == null && $currentOrder->payment_status == 'paid') {
                if ( (!empty($currentOrder->DeliveryAddress1) || !empty($currentOrder->DeliveryAddress2)) && (SettingHelper::startsWithPOBox($currentOrder->DeliveryAddress1) || SettingHelper::startsWithPOBox($currentOrder->DeliveryAddress2))) {
                    $this->send_po_box_wholesale_order_to_shipstation($request);
                } 
                else {
                    $check_shipstation_create_order_status = AdminSetting::where('option_name', 'create_order_in_shipstation')->first();
                    if (!empty($check_shipstation_create_order_status) && strtolower($check_shipstation_create_order_status->option_value) == 'yes') {
                        $order_contact = Contact::where('contact_id', $currentOrder->memberId)->orWhere('parent_id' , $currentOrder->memberId)->first();
                        if (!empty($order_contact)) {
                            $shipstation_order_status = 'create_order';
                            $shiping_order = UserHelper::shipping_order($order_id , $currentOrder , $order_contact, $shipstation_order_status);
                            if ($shiping_order['statusCode'] == 200) {
                                $orderUpdate = ApiOrder::where('id', $order_id)->update([
                                    'shipstation_orderId' => $shiping_order['responseBody']->orderId,
                                    'shipstation_orderKey' => $shiping_order['responseBody']->orderKey,
                                    'shipstation_orderNumber' => $shiping_order['responseBody']->orderNumber,
                                ]);

                                return redirect()->back()->with('success', 'Order send to shipstation successfully !');
                            }
                            else {
                                return redirect()->back()->with('error', 'Invalid Order! Your order is invalid to process' );
                            }
                        } else {
                            return redirect()->back()->with('error', 'Invalid Order! Your order is invalid to process' );
                        }
                    } else {
                        return redirect()->back()->with('error', 'Please check your admin settings for create order in shipstation' );
                    }
                }
            } else {
                return redirect()->back()->with('error', 'Invalid Order! Your order is invalid to process' );
            }
        }
    }


    public function send_wholesale_order_to_shipstation(Request $request) {


        $orderId = $request->order_id;
        $carrierCode = $request->carrier_code;
        $serviceCode = $request->service_code;
        $currentOrder = ApiOrder::with('apiOrderItem')->where('id', $orderId)->first();
        if (!empty($currentOrder)) {
            if ($currentOrder->is_stripe == 0 && $currentOrder->shipstation_orderId == null) {
                $check_shipstation_create_order_status = AdminSetting::where('option_name', 'create_order_in_shipstation')->first();
                if (!empty($check_shipstation_create_order_status) && strtolower($check_shipstation_create_order_status->option_value) == 'yes') {
                    $order_contact = Contact::where('contact_id', $currentOrder->memberId)->orWhere('parent_id' , $currentOrder->memberId)->first();
                    if (!empty($order_contact)) {
                        $shipstation_order_status = 'create_order';
                        $shiping_order = UserHelper::wholesale_shipping_order($orderId , $currentOrder , $order_contact, $shipstation_order_status , $carrierCode, $serviceCode);
                        if ($shiping_order['statusCode'] == 200) {
                            $orderUpdate = ApiOrder::where('id', $orderId)->update([
                                'shipstation_orderId' => $shiping_order['responseBody']->orderId,
                                'shipstation_orderKey' => $shiping_order['responseBody']->orderKey,
                                'shipstation_orderNumber' => $shiping_order['responseBody']->orderNumber,
                                'shipping_carrier_code' => $carrierCode,
                                'shipping_service_code' => $serviceCode,
                            ]);

                            return redirect()->back()->with('success', 'Order send to shipstation successfully !');
                        } else {
                            return redirect()->back()->with('error', 'Invalid Order! Your order is invalid to process' );
                        }
                    } else {
                        return redirect()->back()->with('error', 'Invalid Order! Your order is invalid to process' );
                    }
                } else {
                    return redirect()->back()->with('error', 'Please check your admin settings for create order in shipstation' );
                }
            } else {
                return redirect()->back()->with('error', 'Invalid Order! Your order is invalid to process' );
            }
        }
    }

    public function send_buy_list_order_to_shipstation(Request $request) {

        $orderId = $request->order_id;
        $carrierCode = $request->carrier_code;
        $serviceCode = $request->service_code;
        $currentOrder = ApiOrder::with('apiOrderItem')->where('id', $orderId)->first();
        if (!empty($currentOrder)) {
            if ($currentOrder->shipstation_orderId == null) {
                $check_shipstation_create_order_status = AdminSetting::where('option_name', 'create_order_in_shipstation')->first();
                if (!empty($check_shipstation_create_order_status) && strtolower($check_shipstation_create_order_status->option_value) == 'yes') {
                    $order_contact = Contact::where('contact_id', $currentOrder->memberId)->orWhere('parent_id' , $currentOrder->memberId)->first();
                    if (!empty($order_contact)) {
                        $shipstation_order_status = 'create_order';
                        $shiping_order = UserHelper::wholesale_shipping_order($orderId , $currentOrder , $order_contact, $shipstation_order_status , $carrierCode, $serviceCode);
                        if ($shiping_order['statusCode'] == 200) {
                            $orderUpdate = ApiOrder::where('id', $orderId)->update([
                                'shipstation_orderId' => $shiping_order['responseBody']->orderId,
                                'shipstation_orderKey' => $shiping_order['responseBody']->orderKey,
                                'shipstation_orderNumber' => $shiping_order['responseBody']->orderNumber,
                                'shipping_carrier_code' => $carrierCode,
                                'shipping_service_code' => $serviceCode,
                            ]);

                            return redirect()->back()->with('success', 'Order send to shipstation successfully !');
                        } else {
                            return redirect()->back()->with('error', 'Invalid Order! Your order is invalid to process' );
                        }
                    } else {
                        return redirect()->back()->with('error', 'Invalid Order! Your order is invalid to process' );
                    }
                } else {
                    return redirect()->back()->with('error', 'Please check your admin settings for create order in shipstation' );
                }
            } else {
                return redirect()->back()->with('error', 'Invalid Order! Your order is invalid to process' );
            }
        }
    }

    public function send_po_box_wholesale_order_to_shipstation(Request $request) {


        // dd($request->all());

        $po_box_carrier_code = AdminSetting::where('option_name', 'po_box_shipping_carrier_code')->first();
        $po_box_service_code  = AdminSetting::where('option_name', 'po_box_shipping_service_code')->first();
        
        
        $orderId = $request->order_id;
        $carrierCode = !empty($po_box_carrier_code) ? $po_box_carrier_code->option_value : $request->carrier_code;
        $serviceCode = !empty($po_box_service_code) ? $po_box_service_code->option_value : $request->service_code;
        $currentOrder = ApiOrder::with('apiOrderItem')->where('id', $orderId)->first();
        if (!empty($currentOrder)) {
            if ($currentOrder->shipstation_orderId == null) {
                $check_shipstation_create_order_status = AdminSetting::where('option_name', 'create_order_in_shipstation')->first();
                if (!empty($check_shipstation_create_order_status) && strtolower($check_shipstation_create_order_status->option_value) == 'yes') {
                    $order_contact = Contact::where('contact_id', $currentOrder->memberId)->orWhere('parent_id' , $currentOrder->memberId)->first();
                    if (!empty($order_contact)) {
                        $shipstation_order_status = 'create_order';
                        $shiping_order = UserHelper::wholesale_po_box_shipping_order($orderId , $currentOrder , $order_contact, $shipstation_order_status , $carrierCode, $serviceCode);
                        if ($shiping_order['statusCode'] == 200) {
                            $orderUpdate = ApiOrder::where('id', $orderId)->update([
                                'shipstation_orderId' => $shiping_order['responseBody']['orderId'],
                                'shipstation_orderKey' => $shiping_order['responseBody']['orderKey'],
                                'shipstation_orderNumber' => $shiping_order['responseBody']['orderNumber'],
                                'shipping_carrier_code' => $carrierCode,
                                'shipping_service_code' => $serviceCode,
                            ]);

                            return redirect()->back()->with('success', 'Order send to shipstation successfully !');
                        } else {
                            $specific_error_message = !empty($shiping_order['responseBody'] ) ? $shiping_order['responseBody'] : 'Invalid Order! Your order is invalid to process';
                            return redirect()->back()->with('error', $specific_error_message );
                        }
                    } else {
                        return redirect()->back()->with('error', 'Invalid Order! Your order is invalid to process' );
                    }
                } else {
                    return redirect()->back()->with('error', 'Please check your admin settings for create order in shipstation' );
                }
            } else {
                return redirect()->back()->with('error', 'Invalid Order! Your order is invalid to process' );
            }
        }
    }

    public function send_confirmation_email(Request $request) {
        $order_id = $request->order_id;
        $currentOrder = ApiOrder::where('id', $order_id)->with(
            'contact',
            'user.contact',
            'apiOrderItem.product.options',
            'texClasses'
        )->first();

        if (empty($currentOrder)) {
            return redirect()->back()->with('error', 'Order not found');
        }

        if ($currentOrder->confirmation_email_flag == 1) {
            return redirect()->back()->with('error', 'Confirmation email already sent');
        }

        try {
            $customer_email = Contact::where('contact_id', $currentOrder->memberId)->first();
            if (!empty($customer_email)) {
                $contact = Contact::where('email', $customer_email->email)->first();
            }
            $order_items = ApiOrderItem::with('order.texClasses', 'product.options')
            ->where('order_id', $currentOrder->id)
            ->get();

            $user_email = Auth::user();
            $count = $order_items->count();
            $best_products = Product::where('status', '!=', 'Inactive')->orderBy('views', 'DESC')->limit(4)->get();
            
            if (($currentOrder->is_stripe == 1)) {
                $pay_terms = 'Stripe';
            } else {
                $pay_terms = !empty($currentOrder->contact->paymentTerms) ? $currentOrder->contact->paymentTerms : '30 Days from Invoice';
            }

            $addresses = [
                'billing_address' => [
                    'firstName' => $contact->firstName,
                    'lastName' => $contact->lastName,
                    'address1' => $contact->address1,
                    'address2' => $contact->address2,
                    'city' => $contact->city,
                    'state' => $contact->state,
                    'zip' => $contact->postCode,
                    'mobile' => $contact->mobile,
                    'phone' => $contact->phone,
                ],
                'shipping_address' => [
                    'postalAddress1' =>$contact->postalAddress1,
                    'postalAddress2' =>$contact->postalAddress2,
                    'postalCity' =>$contact->postalCity,
                    'postalState' =>$contact->postalState,
                    'postalPostCode' =>$contact->postalPostCode,
                ],
                'payment_terms' =>  $pay_terms,
                'shipping_fee' => !empty($currentOrder->shipment_price) ? $currentOrder->shipment_price : '',
                'best_product' => $best_products,
                'user_email' =>   $user_email,
                'currentOrder' => $currentOrder,
                'count' => $count,
                'order_id' => $order_id,
                'company' => !empty($currentOrder->user->contact) ?  $currentOrder->user->contact[0]->company : '',
                'order_status' => '',
                'delievery_method' => $currentOrder->logisticsCarrier,
            ];
            $name = $contact->firstName;
            $email =  $contact->email;
            $reference  =  $currentOrder->reference;
            $data = [
                'name' =>  $name,
                'email' => $email,
                'subject' => 'Resending email in Case You Missed It for order #' . $currentOrder->id,
                'reference' => $reference,
                'order_items' => $order_items,
                'dateCreated' => $currentOrder->created_at,
                'addresses' => $addresses,
                'best_product' => $best_products,
                'currentOrder' => $currentOrder,
                'user_email' => $user_email,
                'count' => $count,
                'from' => SettingHelper::getSetting('noreply_email_address')
            ];

            $specific_admin_notifications = SpecificAdminNotification::all();
            if (count($specific_admin_notifications) > 0) {
                foreach ($specific_admin_notifications as $specific_admin_notification) {
                    $subject = 'Resending email in Case You Missed It for order #' . $currentOrder->id;
                    $adminTemplate = 'emails.admin-order-received';
                    $data['email'] = $specific_admin_notification->email;
                    $mail_sent = MailHelper::sendConfirmationMailNotification('emails.confirmation-order-received', $data);
                    
                }
            }

            if (!empty($customer_email->email)) {
                $data['email'] = $customer_email->email;
                $data['subject'] = 'Resending email in Case You Missed It for order #' . $currentOrder->id;
                $mail_sent = MailHelper::sendConfirmationMailNotification('emails.confirmation-order-received', $data);
                if ($mail_sent) {
                    $currentOrder->confirmation_email_flag = 1;
                    $currentOrder->save();
                }
            }

            

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send confirmation email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Confirmation email sent successfully!');
    }


    
}
