<?php

namespace App\Http\Controllers;
//use Auth\Http\AuthControllers\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ApiOrderItem;
use App\Models\ApiOrder;
use App\Models\Contact;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Redirect;
use Carbon\Carbon;
use App\Jobs\SalesOrders;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;
use App\Helpers\MailHelper;
use Spatie\Permission\Models\Role;
use DB;


use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'method_name' => 'required'
        ]);

   
        $paymentMethod = $request->input('method_name');
        $paymentMethodOption = $request->input('method_option');
        $paymentMethod = $paymentMethod.'-'.$paymentMethodOption;
   

        //check if user have already contact with cin7
    
        $existing_contact = Contact::where('user_id', Auth::id())->first();
        if (empty($existing_contact->contact_id)) {

            Session::flash('message', "Your account is being reviewed you can't make order, you can still make carts");
            return redirect('/checkout/');

        }
        // if(empty($existing_contact)) {
        //     $company = $request->input('company');
        //     $mobile = $request->input('phone');
        //     $first_name = $request->input('firstName');
        //     $email = $request->input('email');
        //     $phone = $request->input('phone');
        //     $last_name = $request->input('lastName');
        //     $fax = $request->input('fax');
        //     $job_title = '';
        //     $website = $request->input('website');
        //     $city = $request->input('city');
        //     $email = $request->input('email');
        //     $status = $request->input('status');
        //     $type = $request->input('type');
        //     $pricing_tier = $request->input('priceCol');
        //     $billing_address_1 = $request->input('address');
        //     $billing_address_2 = $request->input('address2');
        //     $billing_city = $request->input('town_city');
        //     $billing_state = $request->input('state');
        //     $billing_postal_code = $request->input('zip');
        //     $delivery_address_1 = $request->input('delivery_address_1');
        //     $delivery_address_2 = $request->input('delivery_address_2');
        //     $delivery_city = $request->input('delivery_town_city');
        //     $delivery_state = $request->input('delivery_state');
        //     $delivery_zip = $request->input('delivery_zip');
        //     $contact_id = '';
        //     $contact = new Contact;
        //     $contact->status = 0;
        //     $contact->user_id = Auth::id();
        //     $contact->type = 'Customer';
        //     $contact->priceColumn = 'RetailUSD';
        //     $contact->company = $company;
        //     $contact->firstName = $first_name;
        //     $contact->lastName = $last_name;
        //     $contact->mobile = $mobile;
        //     $contact->phone = $phone;
        //     $contact->address1 = $billing_address_1;
        //     $contact->address2 =$billing_address_2;
        //     $contact->city   = $billing_city;
        //     $contact->state = $billing_state;
        //     $contact->postCode = $billing_postal_code;
        //     $contact->billing_phone = $phone;
        //     $contact->postalAddress1 = $delivery_address_1;
        //     $contact->postalAddress2 = $delivery_address_2;
        //     $contact->postalCity = $delivery_city;
        //     $contact->postalState = $delivery_state;
        //     $contact->postalPostCode = $delivery_zip;
        //     $contact->email = $email; 
        //     $contact->save();
        // }
        // else 
        // {
          
        //}
        $contact_id = Contact::where('user_id', Auth::id())->pluck('contact_id')->first();
        if ($contact_id) {
        $order = new ApiOrder;
        $cart_items = session()->get('cart');
        $cart_total = 0;
        $cart_price = 0;
        if ($cart_items) {
            foreach($cart_items as $cart_item) {
                $total_quatity =  $cart_item['quantity'];
                $total_price = $cart_item['price'] * $total_quatity;
                $cart_total  = $cart_total + $total_price ;
            }
        }
        
        // $order->user_id    =  Auth::id(); 
        // $order->first_name =  $request->input('firstName');
        // $order->last_name  =  $request->input('lastName');
        // $order->company    =  $request->input('company');
        // $order->country    =  $request->input('country');
        // $order->street_address    =  $request->input('address');
        // $order->street_address_2   =  $request->input('address2');
        // $order->town_city  =  $request->input('town_city');
        // $order->state      =  $request->input('state');
        // $order->zip        =  $request->input('zip');
        // $order->phone      =  $request->input('phone');
        // $order->email      =  $request->input('email');
        // $order->grand_total = $cart_total;
        // $order->save();
        // $order_id =  $order->id;
   
        // foreach($cart_items as $cart_item) {
        //     $OrderItem = new OrderItem;
        //     $OrderItem->order_id = $order_id;
        //     $OrderItem->product_id = $cart_item['product_id'];
        //     $OrderItem->quantity =  $cart_item['quantity'];
        //     $OrderItem->price = $cart_item['price'];
        //     $OrderItem->save();
        // }
        //moving to Api order items
        $dateCreated = Carbon::now();
        $createdDate = '2022-07-31T23:43:38';
        $order->createdDate = $createdDate;
        $order->modifiedDate = $createdDate;
        $order->createdBy =  79914;
        $order->processedBy  =  79914;
        $order->isApproved    =  false;
        $order->memberId    =  $contact_id;
        $order->branchId   =  3;
        $order->branchEmail  =  'wqszeeshan@gmail.com';
        $order->productTotal      =  $cart_total;
        $order->total        =  $cart_total;
        $order->currencyCode      =  'USD';
        $order->currencyRate      =  59.0;
        $order->currencySymbol = '$';
        $order->user_id = Auth::id();
        $order->status = 'DRAFT';
        $order->stage = 'Sample string';
        $order->paymentTerms = $paymentMethod;

        $order->save();

        $order_id =  $order->id;
        $currentOrder = ApiOrder::where('id', $order->id)->first();
        $apiApproval = $currentOrder->apiApproval;
        $currentOrder->reference = 'DEV2'.'-QCOM-'.$order_id;

        //dd($currentOrder);

   


        $currentOrder->save();
        $currentOrder = ApiOrder::where('id', $order->id)->first();
        $reference = $currentOrder->reference;
        foreach($cart_items as $cart_item) {
            $OrderItem = new ApiOrderItem;
            $OrderItem->order_id = $order_id;
            $OrderItem->product_id = $cart_item['product_id'];
            $OrderItem->quantity =  $cart_item['quantity'];
            $OrderItem->price = $cart_item['price'];
            $OrderItem->option_id = $cart_item['option_id'];
            $OrderItem->save();
        }
        //exit;
        $order_items = ApiOrderItem::with('product.options')->where('order_id', $order_id)->get();
        $contact = Contact::where('user_id' ,auth()->id())->first();
        //dd($contact);
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

            ]
        ];
     
        $name = $contact->firstName;
        $email =  $contact->email;
        $reference  =  $currentOrder->reference;

        $template = 'emails.admin-order-received';
        $admin_users =  DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');
        $admin_users = $admin_users->toArray();

        $users_with_role_admin = User::select("email")
                    ->whereIn('id',$admin_users)
                    ->get();
       
        $data = [
            'name' =>  $name,
            'email' => $email,
            'subject' => 'New order received',
            'reference' => $reference,
            'order_items' => $order_items, 
            'dateCreated' => $dateCreated, 
            'addresses' => $addresses,
            'from' => 'wqszeeshan@gmail.com'
        ];

        if (!empty($users_with_role_admin)) {
            foreach($users_with_role_admin as $role_admin) {
                $subject = '';
                $adminTemplate = 'emails.admin-order-received';
                $data['email'] = $role_admin->email;

                MailHelper::sendMailNotification('emails.admin-order-received', $data);
            }
        }

        $data['subject'] = 'Your order has been received';
        $data['email'] = $email;
        MailHelper::sendMailNotification('emails.admin-order-received', $data);
        

        // $new_data = [
        //     'from' => 'wqszeeshan@gmail.com',
        //     'email' => $email,
        //     'subject' => 'Forgot Password',
        //     'full_name' => 'Iqrar Ahmad',
        //     'any_other_variable' => 'This is just another variable name',
        // ];

        // MailHelper::sendMailNotification('emails.forgot_password', $new_data);

        
        $lineItems = [];
        foreach($order_items as $order_item) {
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
                "lineComments" => "sample string 5",
                "unitCost" => $order_item->price,
                "unitPrice" => $order_item->price,
                "discount" => 6,
                "qtyShipped" => 7,
                "holdingQty" => 8,
                "accountCode" => "sample string 9",
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


        // $order = [];
        // $order = [
        //             [
        //                 $currentOrder,
        //                 "reference" => "QCOM".$order_id,
        //                 "memberId" => $contact_id,
        //                 "lineItems" => $lineItems,
        //                 "firstName" => $request->input('firstName'),
        //                 "lastName" => $request->input('lastName'),
        //                 "company" => $request->input('company'),
        //                 "email" => $request->input('email'),
        //                 "memberEmail" => $request->input('email'),
        //                 "phone" => $request->input('phone'),
        //                 "fax" => "",
        //                 "mobile" => $request->input('phone'),
        //                 "deliveryFirstName" => $request->input('firstName'),
        //                 "deliveryLastName" => $request->input('lastName'),
        //                 "deliveryCompany" => $request->input('company'),
        //                 "deliveryAddress1" => $request->input('address'),
        //                 "deliveryAddress2" => $request->input('address2'),
        //                 "deliveryCity" => $request->input('town_city'),
        //                 "deliveryState" => $request->input('state'),
        //                 "deliveryPostalCode" => $request->input('zip'),
        //                 "deliveryCountry" => $request->input('country'),
        //                 "billingFirstName" => $request->input('firstName'),
        //                 "billingLastName" => $request->input('lastName'),
        //                 "billingCompany" => $request->input('company'),
        //                 "billingAddress1" => $request->input('address'),
        //                 "billingAddress2" => $request->input('address2'),
        //                 "billingCity" => $request->input('town_city'),
        //                 "billingPostalCode" => $request->input('zip'),
        //                 "billingState" => $request->input('state'),
        //                 "billingCountry" => $request->input('country'),
        //                 "taxStatus" => "Undefined",
        //                 "taxRate" => null,
        //                 "source" => "sample string 62",
        //                 "isVoid" => true,
        //                 "projectName" => "sample string 49",
        //                 "trackingCode" => "sample string 50",
        //                 "internalComments" => "",
        //                 "freightTotal" => 1.0,
        //                 "freightDescription" => "sample string 53",
        //                 "surcharge" => 1.0,
        //                 "surchargeDescription" => "sample string 54",
        //                 "discountTotal" => 1.0,
        //                 "discountDescription" => "sample string 55",
        //                 "accountingAttributes" => 
        //                     [
        //                         "importDate" => "2022-07-13T15:21:16.1946848+12:00",
        //                         "accountingImportStatus" => "NotImported"
        //                     ],
                  
        //                 "memberCostCenter" => "sample string 6",
        //                 "memberAlternativeTaxRate" => "sample string 7",
        //                 "costCenter" => "sample string 8",
        //                 "alternativeTaxRate" => "sample string 9",
        //                 "estimatedDeliveryDate" => "2022-07-13T15:21:16.1946848+12:00",
        //                 "salesPersonId" => 10,
        //                 "salesPersonEmail" => "wqszeeshan@gmail.com",
        //                 "paymentTerms" => "sample string 12",
        //                 "customerOrderNo" => "sample string 13",
        //                 "voucherCode" => "sample string 14",
        //                 "deliveryInstructions" => "sample string 15",
        //                 "status" => "DRAFT",
        //                 "stage" => "sample string 4",
        //                 "invoiceDate" => now() ,
        //                 "invoiceNumber" => 4232,
        //                 "dispatchedDate" => null,
        //                 "logisticsCarrier" => "sample string 2",
        //                 "logisticsStatus" => 1,
        //                 "distributionBranchId" => 1,
        //             ]
        //         ];
        //dd($order);
        // $order = [];
        // $order = [
        //             [
        //                 "createdDate" => $dateCreated,
        //                 "modifiedDate" => "",
        //                 "createdBy" => 79914,
        //                 "processedBy" => 79914,
        //                 "isApproved" => true,
        //                 "reference" => "QCOM-".$order_id,
        //                 "memberId" => "",
        //                 "firstName" => $request->input('firstName'),
        //                 "lastName" => $request->input('lastName'),
        //                 "company" => $request->input('company'),
        //                 "email" => $request->input('email'),
        //                 "phone" => $request->input('phone'),
        //                 "mobile" => $request->input('phone'),
        //                 "fax" => "",
        //                 "deliveryFirstName" => $request->input('firstName'),
        //                 "deliveryLastName" => $request->input('lastName'),
        //                 "deliveryCompany" => $request->input('company'),
        //                 "deliveryAddress1" => $request->input('address'),
        //                 "deliveryAddress2" => $request->input('address2'),
        //                 "deliveryCity" => $request->input('town_city'),
        //                 "deliveryState" => $request->input('state'),
        //                 "deliveryPostalCode" => $request->input('zip'),
        //                 "deliveryCountry" => $request->input('country'),
        //                 "billingFirstName" => $request->input('firstName'),
        //                 "billingLastName" => $request->input('lastName'),
        //                 "billingCompany" => $request->input('company'),
        //                 "billingAddress1" => $request->input('address'),
        //                 "billingAddress2" => $request->input('address2'),
        //                 "billingCity" => $request->input('town_city'),
        //                 "billingPostalCode" => $request->input('zip'),
        //                 "billingState" => $request->input('state'),
        //                 "billingCountry" => $request->input('country'),
        //                 "branchId" => 3,
        //                 "branchEmail" => "wqszeeshan@gmail.com",
        //                 "projectName" => "sample string 49",
        //                 "trackingCode" => "sample string 50",
        //                 "internalComments" => "sample string 51",
        //                 "productTotal" => 100,
        //                 "freightTotal" => 1.0,
        //                 "freightDescription" => "sample string 53",
        //                 "surcharge" => 1.0,
        //                 "surchargeDescription" => "sample string 54",
        //                 "discountTotal" => 1.0,
        //                 "discountDescription" => "sample string 55",
        //                 "total" => 100,
        //                 "currencyCode" => "USD",
        //                 "currencyRate" => 59.0,
        //                 "currencySymbol" => "sample string 60",
        //                 "taxStatus" => "Undefined",
        //                 "taxRate" => null,
        //                 "source" => "sample string 62",
        //                 "isVoid" => true,
        //                 "accountingAttributes" => 
        //                     [
        //                         "importDate" => "2022-07-13T15:21:16.1946848+12:00",
        //                         "accountingImportStatus" => "NotImported"
        //                     ],
        //                 "memberEmail" => "wqszeeshan@gmail.com",
        //                 "memberCostCenter" => "sample string 6",
        //                 "memberAlternativeTaxRate" => "sample string 7",
        //                 "costCenter" => "sample string 8",
        //                 "alternativeTaxRate" => "sample string 9",
        //                 "estimatedDeliveryDate" => "2022-07-13T15:21:16.1946848+12:00",
        //                 "salesPersonId" => 10,
        //                 "salesPersonEmail" => "wqszeeshan@gmail.com",
        //                 "paymentTerms" => "sample string 12",
        //                 "customerOrderNo" => "sample string 13",
        //                 "voucherCode" => "sample string 14",
        //                 "deliveryInstructions" => "sample string 15",
        //                 "status" => "DRAFT",
        //                 "stage" => "sample string 4",
        //                 "invoiceDate" => now(),
        //                 "invoiceNumber" => 4232,
        //                 "dispatchedDate" => null,
        //                 "logisticsCarrier" => "sample string 2",
        //                 "logisticsStatus" => 1,
        //                 "distributionBranchId" => 1,
        //                 "lineItems" => $lineItems
         
        //                 ],
        //              ];
        //dd($order);
        //SalesOrders::dispatch('list_order', []);
        //SalesOrders::dispatch('create_order', $order);
        session()->forget('cart');
            return \Redirect::route('thankyou', $order_id);
        }
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
                $order
            , 
        ]);
        // $apiOrderUpdate = Order::find($order_id);
        // $apiOrderUpdate->api_ref_no = '10';
        // $apiOrderUpdate->api_status = 'SUCCESS';
        // $apiOrderUpdate->save();


        echo $response->getBody();exit;

        return \Redirect::route('thankyou', $order_id);
    }
}
