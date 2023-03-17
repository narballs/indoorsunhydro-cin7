<?php

namespace App\Http\Controllers;
//use Auth\Http\AuthControllers\Auth;

use App\Helpers\MailHelper;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ApiOrderItem;
use App\Models\ApiOrder;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'method_name' => 'required'
        ]);

        $paymentMethod = $request->input('method_name');
        $paymentMethodOption = $request->input('method_option');
        $paymentMethod = $paymentMethodOption;
        //check if user have already contact with cin7
        $existing_contact = Contact::where('user_id', Auth::id())->first();
        $session_contact_id = Session::get('contact_id');
        if (!empty($session_contact_id)) {
            $contact = Contact::where('contact_id', $session_contact_id)->first();

            if ($contact) {
                $active_contact_id = $contact->contact_id;
            } else {
                $contact = Contact::where('secondary_id', $session_contact_id)->first();
                $active_contact_id = $contact->parent_id;
            }


            if ($active_contact_id) {
                $order = new ApiOrder;
                $cart_items = session()->get('cart');
                $cart_total = 0;
                $cart_price = 0;
                if ($cart_items) {
                    foreach ($cart_items as $cart_item) {
                        $total_quatity =  $cart_item['quantity'];
                        $total_price = $cart_item['price'] * $total_quatity;
                        $cart_total  = $cart_total + $total_price;
                    }
                }
                //moving to Api order items
                $dateCreated = Carbon::now();
                $createdDate = Carbon::now();
                $order->createdDate = $createdDate;
                $order->modifiedDate = $createdDate;
                $order->createdBy =  79914;
                $order->processedBy  =  79914;
                $order->isApproved    =  false;
                $order->memberId    =  $active_contact_id;
                $order->branchId   =  "none";
                $order->distributionBranchId = 0;
                $order->branchEmail  =  'wqszeeshan@gmail.com';
                $order->productTotal      =  $cart_total;
                $order->total        =  $cart_total;
                $order->currencyCode      =  'USD';
                $order->currencyRate      =  59.0;
                $order->currencySymbol = '$';
                $order->user_id = Auth::id();
                $order->status = "DRAFT";
                $order->stage = null;
                $order->paymentTerms = $paymentMethod;
                $order->tax_class_id = $request->tax_class_id;
                $order->total_including_tax = $request->incl_tax;
                $order->po_number = $request->po_number;
                $order->memo = $request->memo;
                $order->date = $request->date;
                $order->save();

                $order_id =  $order->id;
                $currentOrder = ApiOrder::where('id', $order->id)->first();
                $apiApproval = $currentOrder->apiApproval;
                $currentOrder->reference = 'DEV4' . '-QCOM-' . $order_id;
                $currentOrder->save();
                $currentOrder = ApiOrder::where('id', $order->id)->first();
                $reference = $currentOrder->reference;

                foreach ($cart_items as $cart_item) {
                    $OrderItem = new ApiOrderItem;
                    $OrderItem->order_id = $order_id;
                    $OrderItem->product_id = $cart_item['product_id'];
                    $OrderItem->quantity =  $cart_item['quantity'];
                    $OrderItem->price = $cart_item['price'];
                    $OrderItem->option_id = $cart_item['option_id'];
                    $OrderItem->save();
                }

                $order_items = ApiOrderItem::with('order', 'product.options')->where('order_id', $order_id)->get();
                $contact = Contact::where('user_id', auth()->id())->first();
                $user_email = Auth::user();
                $count = $order_items->count();
                $best_products = Product::where('status', '!=', 'Inactive')->orderBy('views', 'DESC')->limit(4)->get();
                $addresses = [
                    'billing_address' => [
                        'firstName' => $contact->firstName,
                        'lastName' => $contact->lastName,
                        'address1' => $contact->address1,
                        'address2' => $contact->address2,
                        'city' => $contact->city,
                        'state' => $contact->state,
                        'zip' => $contact->postCode
                    ],
                    'shipping_address' => [
                        'postalAddress1' => $contact->postalAddress1,
                        'postalAddress2' => $contact->postalAddress2,
                        'phone' => $contact->postalCity,
                        'postalCity' => $contact->postalState,
                        'postalState' => $contact->postalPostCode,
                        'postalPostCode' => $contact->postalPostCode
                    ],
                    'best_product' => $best_products,
                    'user_email' =>   $user_email,
                    'currentOrder' => $currentOrder,
                    'count' => $count,
                    'order_id' => $order_id,
                ];



                $name = $contact->firstName;
                $email =  $contact->email;
                $reference  =  $currentOrder->reference;
                $template = 'emails.admin-order-received';
                $admin_users = DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');
                $admin_users = $admin_users->toArray();

                $users_with_role_admin = User::select("email")
                    ->whereIn('id', $admin_users)
                    ->get();
                $data = [
                    'name' =>  $name,
                    'email' => $email,
                    'subject' => 'New order received',
                    'reference' => $reference,
                    'order_items' => $order_items,
                    'dateCreated' => $dateCreated,
                    'addresses' => $addresses,
                    'best_product' => $best_products,
                    'user_email' => $user_email,
                    '$currentOrder' => $currentOrder,
                    'count' => $count,
                    'from' => 'noreply@indoorsunhydro.com'
                ];

                if (!empty($users_with_role_admin)) {
                    foreach ($users_with_role_admin as $role_admin) {
                        $subject = '';
                        $adminTemplate = 'emails.admin-order-received';
                        $data['email'] = $role_admin->email;

                        MailHelper::sendMailNotification('emails.admin-order-received', $data);
                    }
                }

                $data['subject'] = 'Your order has been received';
                $data['email'] = $email;
                MailHelper::sendMailNotification('emails.admin-order-received', $data);

                $lineItems = [];
                foreach ($order_items as $order_item) {
                    $lineItems[] = [
                        "id" => $order_item->product_id,
                        "createdDate" => '2022-07-31T23:43:38Z',
                        "transaction" => '12',
                        "parentId" => 1,
                        "productId" => $order_item->product_id,
                        "productOptionId" => null,
                        "integrationRef" => "sample string 15",
                        "sort" => 16,
                        "code" => $order_item->code,
                        "name" => $order_item->name,
                        "option1" => "sample string 19",
                        "option2" => "sample string 20",
                        "option3" => "sample string 21",
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
                session()->forget('cart');
                return \Redirect::route('thankyou', $order_id);
            }
        }
        // else {
        //     return redirect('my-account')->with('success', 'Please Select Company Then Place Over Order Thanks !');
        // }

        //print_r($order);exit;
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
        exit;

        return \Redirect::route('thankyou', $order_id);
    }
}
