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
use App\Models\Contact;
use App\Jobs\SalesOrders;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;
use App\Helpers\MailHelper;
use App\Models\BuyList;
use App\Models\ProductBuyList;
use App\Models\TaxClass;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Illuminate\Support\Facades\Redis;

class OrderManagementController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:Admin'])->except('order_full_fill', 'send_invitation_email');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $orders_query = ApiOrder::with(['createdby', 'processedby', 'contact'])->orderBy('id', 'DESC');

        if (!empty($search)) {
            $orders_query = $orders_query->where('order_id', 'LIKE', '%' . $search . '%')
                ->orWhere('createdDate', 'like', '%' . $search . '%')
                ->orWhere('modifiedDate', 'like', '%' . $search . '%')
                ->orWhere('reference', 'like', '%' . $search . '%')
                ->orWhere('total', 'like', '%' . $search . '%')
                ->orWhere('stage', 'like', '%' . $search . '%');
        }
        $orders =  $orders_query->paginate(10);
        return view('admin/orders', compact('orders', 'search'));
    }

    public function show($id)
    {
        $statuses = OrderStatus::all();
        $order = ApiOrder::where('id', $id)->with('texClasses')->first();
        $tax_class = TaxClass::where('is_default', 1)->first();
        $createdDate = $order->created_at;
        $formatedDate = $createdDate->format('jS \of F Y h:i:s A');
        $orderCreatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $createdDate, 'America/Los_Angeles');
        $currentTime = Carbon::now();
        $time_diff = $orderCreatedDate->diffInMinutes($currentTime);
        // dd($time_diff);


        $customer = Contact::where('user_id', $order->user_id)->first();
        $option_ids = ApiOrderItem::where('order_id', $id)->pluck('option_id')->toArray();
        $orderitems = $this->option_ids = $option_ids;
        $orderitems = ApiOrderItem::with(['product.options' => function ($q) {
            $q->whereIn('option_id', $this->option_ids);
        }])->where('order_id', $id)->get();


        $orderComment = OrderComment::where('order_id', $id)->with('comment')->get();
        return view('admin/order-details', compact(
            'order',
            'tax_class',
            'orderitems',
            'orderComment',
            'statuses',
            'customer',
            'formatedDate',
            'time_diff'
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

    public function create()
    {
        $order = [];

        $order = [
            [
                "createdDate" => "2022-07-13T15:21:16.1946848+12:00",
                "modifiedDate" => "2022-07-13T15:21:16.1946848+12:00",
                "createdBy" => 17,
                "processedBy" => 18,
                "isApproved" => true,
                "reference" => "",
                "memberId" => 7,
                "firstName" => "sample string 22",
                "lastName" => "sample string 23",
                "company" => "sample string 24",
                "email" => "wqszeeshan@gmail.com",
                "phone" => "sample string 26",
                "mobile" => "sample string 27",
                "fax" => "sample string 28",
                "deliveryFirstName" => "sample string 29",
                "deliveryLastName" => "sample string 30",
                "deliveryCompany" => "sample string 31",
                "deliveryAddress1" => "sample string 32",
                "deliveryAddress2" => "sample string 33",
                "deliveryCity" => "sample string 34",
                "deliveryState" => "sample string 35",
                "deliveryPostalCode" => "sample string 36",
                "deliveryCountry" => "sample string 37",
                "billingFirstName" => "sample string 38",
                "billingLastName" => "sample string 39",
                "billingCompany" => "sample string 40",
                "billingAddress1" => "sample string 41",
                "billingAddress2" => "sample string 42",
                "billingCity" => "sample string 43",
                "billingPostalCode" => "sample string 44",
                "billingState" => "sample string 45",
                "billingCountry" => "sample string 46",
                "branchId" => 47,
                "branchEmail" => "wqszeeshan@gmail.com",
                "projectName" => "sample string 49",
                "trackingCode" => "sample string 50",
                "internalComments" => "sample string 51",
                "productTotal" => 52.0,
                "freightTotal" => null,
                "freightDescription" => null,
                "surcharge" => null,
                "surchargeDescription" => null,
                "discountTotal" => null,
                "discountDescription" => null,
                "total" => 56.0,
                "currencyCode" => "USD",
                "currencyRate" => 59.0,
                "currencySymbol" => "sample string 60",
                "taxStatus" => "Undefined",
                "taxRate" => 61.0,
                "source" => "sample string 62",
                "isVoid" => true,
                "accountingAttributes" =>
                [
                    "importDate" => "2022-07-13T15:21:16.1946848+12:00",
                    "accountingImportStatus" => "NotImported"
                ],
                "memberEmail" => "wqszeeshan@gmail.com",
                "memberCostCenter" => "sample string 6",
                "memberAlternativeTaxRate" => "sample string 7",
                "costCenter" => "sample string 8",
                "alternativeTaxRate" => null,
                "estimatedDeliveryDate" => "2022-07-13T15:21:16.1946848+12:00",
                "salesPersonId" => 10,
                "salesPersonEmail" => "wqszeeshan@gmail.com",
                "paymentTerms" => "sample string 12",
                "customerOrderNo" => "sample string 13",
                "voucherCode" => "sample string 14",
                "deliveryInstructions" => "sample string 15",
                "status" => "VOID",
                "stage" => "sample string 4",
                "invoiceDate" => null,
                "invoiceNumber" => null,
                "dispatchedDate" => "",
                "logisticsCarrier" => "sample string 2",
                "logisticsStatus" => 1,
                "distributionBranchId" => 0,
                "lineItems" =>
                [
                    [
                        "id" => 10,
                        "createdDate" => "2022-07-13T15:21:16.1946848+12:00",
                        "transactionId" => 12,
                        "parentId" => 1,
                        "productId" => 13,
                        "productOptionId" => 14,
                        "integrationRef" => "sample string 15",
                        "sort" => 16,
                        "code" => "sample string 17",
                        "name" => "sample string 18",
                        "option1" => "sample string 19",
                        "option2" => "sample string 20",
                        "option3" => "sample string 21",
                        "qty" => 1.0,
                        "styleCode" => "sample string 1",
                        "barcode" => "sample string 2",
                        "sizeCodes" => "sample string 4",
                        "lineComments" => null,
                        "unitCost" => 1.0,
                        "unitPrice" => 1.0,
                        "discount" => null,
                        "qtyShipped" => 7.0,
                        "holdingQty" => 8.0,
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
                        ]
                    ],

                ]
            ],
        ];
        SalesOrders::dispatch('create_order', [
            'json' =>
            $order
        ])->onQueue(env('QUEUE_NAME'));
        exit;

        $client = new \GuzzleHttp\Client();
        $url = "https://api.cin7.com/api/v1/SalesOrders/";
        $response = $client->post($url, [
            'headers' => ['Content-type' => 'application/json'],
            'auth' => [
                env('API_USER'),
                env('API_PASSWORD')
            ],
            'json' =>
            $order,
        ]);

        echo $response->getBody();
    }

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
        $order_id = $request->input('order_id');
        $currentOrder = ApiOrder::where('id', $order_id)->with('user.contact')->first();

        if (!empty($currentOrder->user['contact'])) {
            foreach ($currentOrder->user['contact'] as $contact) {
                $userSubmiter  =   $contact->email . ',' . $contact->firstName . ',' . $contact->lastName;
            }
        }

        $userSwitchUser = [];
        if (!empty($currentOrder->user_switch)) {
            $userSwitchUser = $currentOrder->user_switch;
        } else {
            $userSwitchUser = "";
        }

        $orderSubmiterDetail = $userSubmiter . ',' . $userSwitchUser;

        $userSwitchUser = $currentOrder->user_switch;
        $memberId = $currentOrder->memberId;
        $order_items = ApiOrderItem::with('product.options')->where('order_id', $order_id)->get();
        $dateCreated = Carbon::now();
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
        $order = [];
        unset($currentOrder['primaryId']);
        unset($currentOrder['memberId']);
        $order = [
            [
                $currentOrder,
                "createdDate" => $dateCreated,
                "modifiedDate" => "",
                "createdBy" => 79914,
                "processedBy" => 79914,
                "isApproved" => true,
                "reference" => $currentOrder->reference,
                "memberId" => $memberId,
                "branchId" => 3,
                "branchEmail" => "wqszeeshan@gmail.com",
                "projectName" => "",
                "trackingCode" => "",
                "internalComments" => $orderSubmiterDetail,
                "productTotal" => 100,
                "freightTotal" => null,
                "freightDescription" => null,
                "surcharge" => null,
                "surchargeDescription" => null,
                "discountTotal" => null,
                "discountDescription" => null,
                "total" => 100,
                "currencyCode" => "USD",
                "currencyRate" => 59.0,
                "currencySymbol" => "$",
                "taxStatus" => "Excl",
                "taxRate" => 8.75,
                "source" => "sample string 62",
                "accountingAttributes" =>
                [
                    "importDate" => "2022-07-13T15:21:16.1946848+12:00",
                    "accountingImportStatus" => "NotImported"
                ],
                "memberEmail" => "wqszeeshan@gmail.com",
                "memberCostCenter" => "sample string 6",
                "memberAlternativeTaxRate" => "",
                "costCenter" => null,
                "alternativeTaxRate" => "8.75%",
                // "estimatedDeliveryDate" => "2022-07-13T15:21:16.1946848+12:00",
                "estimatedDeliveryDate" => $currentOrder->date,
                "salesPersonId" => 10,
                "salesPersonEmail" => "wqszeeshan@gmail.com",
                "paymentTerms" => $currentOrder->paymentTerms,
                "customerOrderNo" => $currentOrder->po_number,
                "voucherCode" => "sample string 14",
                "deliveryInstructions" =>  $currentOrder->memo,
                "status" => "VOID",
                "invoiceDate" => null,
                "invoiceNumber" => 4232,
                "dispatchedDate" => null,
                "logisticsCarrier" => "",
                "logisticsStatus" => 1,
                "distributionBranchId" => 0,
                "lineItems" => $lineItems

            ],
        ];
        SalesOrders::dispatch('create_order', $order)->onQueue(env('QUEUE_NAME'));
    }


    public function cancelOrder(Request $request)
    {
        $order_id = $request->input('order_id');
        $user_id = Auth::user()->id;

        $quotes_id = BuyList::insertGetId([
            'title' => 'cancel order order',
            'status' => 'Public',
            'description' => 'description',
            'user_id' => $user_id,
            'type' => 'quote',
            'created_at' => SupportCarbon::now(),
        ]);


        $currentOrder = ApiOrder::where('id', $order_id)->first();
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
        $user_cancel_order = ApiOrder::where(['id' =>  $order_id])->update([
            'isApproved' => 2,
            'updated_at' => Carbon::now(),
        ]);

        return response()->json([
            'buy_list_product' =>  $buy_list_product,
            'status' => 'success',
            'message' => 'Order Cancel successfully ! ',

        ]);
    }

    public function check_order_status(Request $request)
    {
        sleep(10);
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
    // destroy order 

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
}
