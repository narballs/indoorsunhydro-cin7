<?php

namespace App\Http\Controllers;
//use Auth\Http\AuthControllers\Auth;

use App\Helpers\MailHelper;
use App\Helpers\UserHelper;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ApiOrderItem;
use App\Models\ApiOrder;
use App\Models\Contact;
use App\Models\Pricingnew;
use App\Models\AdminSetting;
use App\Models\OrderComment;
use App\Models\Productoption;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;

use App\Helpers\SettingHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
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
                $cart_items = session()->get('cart');
                $user_switch = "";
                if (!empty(session()->get('logged_in_as_another_user'))) {
                    $user_switch = "order placed by user switch ";
                } else {
                    $user_switch = "";
                }

                $cart_total = 0;
                $cart_price = 0;
                if ($cart_items) {
                    foreach ($cart_items as $cart_item) {
                        $total_quantity =  $cart_item['quantity'];
                        $total_price = $cart_item['price'] * $total_quantity;
                        $cart_total  = $cart_total + $total_price;
                    }
                } else {
                    return redirect('/');
                }

                $is_primary = Contact::where('contact_id', $session_contact_id)->first();
                $enable_stripe_checkout_setting = AdminSetting::where('option_name', 'enable_stripe_checkout')->first();
                $stripe_is_enabled = !empty($enable_stripe_checkout_setting) && strtolower($enable_stripe_checkout_setting->option_value) == 'yes';

                $go_to_stripe_checkout = false;
                $pay_in_advance = strtolower($request->paymentTerms) === 'pay in advanced' ? true : false;

                if ($stripe_is_enabled && $pay_in_advance) {
                    $go_to_stripe_checkout = true;
                }


                if ($go_to_stripe_checkout) {
                    $order = new ApiOrder;
                    
                    if ($is_primary == null) {
                        $order->secondaryId = $session_contact_id;
                    } else {
                        $order->primaryId = $session_contact_id;
                    }
                    $dateCreated = Carbon::now();
                    $createdDate = Carbon::now();
                    $order->createdDate = $createdDate;
                    $order->modifiedDate = $createdDate;
                    $order->createdBy = 79914;
                    $order->processedBy = 79914;
                    $order->isApproved = false;
                    $order->memberId = $active_contact_id;
                    $order->branchId = "none";
                    $order->distributionBranchId = 0;
                    $order->branchEmail = 'wqszeeshan@gmail.com';
                    $order->productTotal = $cart_total;
                    $order->total = $cart_total;
                    $order->currencyCode = 'USD';
                    $order->currencyRate = 59.0;
                    $order->currencySymbol = '$';

                    $order->user_id = Auth::id();
                    $order->status = "DRAFT";
                    $order->stage = "New";
                    $order->logisticsCarrier = $paymentMethod;
                    $order->tax_class_id = $request->tax_class_id;
                    $order->user_switch = $user_switch;
                    $order->total_including_tax = $request->incl_tax;
                    $order->po_number = $request->po_number;
                    $order->paymentTerms = $request->paymentTerms;
                    $order->memo = $request->memo;
                    $order->date = $request->date;
                    $order->shipment_price = $request->shipment_price;
                    $order->is_stripe = 1;
                    $order->save();

                    $order_id =  $order->id;
                    $currentOrder = ApiOrder::where('id', $order->id)->first();
                    $apiApproval = $currentOrder->apiApproval;
                    $random_string = Str::random(10);
                    $currentOrder->reference = 'DEV4' . '-QCOM-' .$random_string . '-' .$order_id;

                    $currentOrder->save();
                    $currentOrder = ApiOrder::where('id', $order_id)->with(
                        'contact',
                        'user.contact',
                        'apiOrderItem.product.options',
                        'texClasses'
                    )->first();
                    $order_contact = Contact::where('contact_id', $currentOrder->memberId)->first();
                    $product_prices = [];
                    $reference = $currentOrder->reference;
                    if (session()->has('cart')) {
                        foreach ($cart_items as $cart_item) {
                            $OrderItem = new ApiOrderItem;
                            $OrderItem->order_id = $order_id;
                            $OrderItem->product_id = $cart_item['product_id'];
                            $OrderItem->quantity =  $cart_item['quantity'];
                            $OrderItem->price = $cart_item['price'];
                            $OrderItem->option_id = $cart_item['option_id'];
                            $OrderItem->save();

                            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
                            $products = $stripe->products->create([
                                'name' => $cart_item['name'],
                            ]);
                            
                            $productPrice = $stripe->prices->create([
                                'unit_amount' => $cart_item['price'] * 100,
                                'currency' => 'usd',
                                'product' => $products->id,
                            ]);
                            array_push($product_prices, $productPrice);
                        }

                        for ($i = 0; $i <= count($product_prices) - 1; $i++){
                            $items[] = [
                                'price' => $product_prices[$i]->id,
                                'quantity' => 1,
                            ];  
                        }
                        $line_items = [
                            'line_items' => 
                            [
                                $items
                            ]
                        ];
                        
                        $checkout_session = $stripe->checkout->sessions->create([
                            'success_url' => url('/thankyou/' . $order_id) . '?session_id={CHECKOUT_SESSION_ID}',
                            'cancel_url' => url('/checkout'),
                            $line_items,
                            'mode' => 'payment',
                            'payment_intent_data'=> [
                                "metadata" => [
                                    "order_id"=> $order_id,
                                ]
                            ],
                            'customer_email' => auth()->user()->email,
                            
                        ]);
                    } else {
                        session()->forget('cart');
                        return redirect('/');
                    }
                    
                    session()->forget('cart');
                    return redirect($checkout_session->url);
                    
                }
                else {
                    $order = new ApiOrder;
                    if ($is_primary == null) {
                        $order->secondaryId = $session_contact_id;
                    } else {
                        $order->primaryId = $session_contact_id;
                    }
                    $dateCreated = Carbon::now();
                    $createdDate = Carbon::now();
                    $order->createdDate = $createdDate;
                    $order->modifiedDate = $createdDate;
                    $order->createdBy = 79914;
                    $order->processedBy = 79914;
                    $order->isApproved = false;
                    $order->memberId = $active_contact_id;
                    $order->branchId = "none";
                    $order->distributionBranchId = 0;
                    $order->branchEmail = 'wqszeeshan@gmail.com';
                    $order->productTotal = $cart_total;
                    $order->total = $cart_total;
                    $order->currencyCode = 'USD';
                    $order->currencyRate = 59.0;
                    $order->currencySymbol = '$';

                    $order->user_id = Auth::id();
                    $order->status = "DRAFT";
                    $order->stage = "New";
                    $order->logisticsCarrier = $paymentMethod;
                    $order->tax_class_id = $request->tax_class_id;
                    $order->user_switch = $user_switch;
                    $order->total_including_tax = $request->incl_tax;
                    $order->po_number = $request->po_number;
                    $order->paymentTerms = $request->paymentTerms;
                    $order->memo = $request->memo;
                    $order->date = $request->date;
                    $order->shipment_price = $request->shipment_price;
                    $order->save();

                    $order_id =  $order->id;
                    $currentOrder = ApiOrder::where('id', $order->id)->first();
                    $apiApproval = $currentOrder->apiApproval;
                    $random_string = Str::random(10);
                    $currentOrder->reference = 'DEV4' . '-QCOM-' .$random_string . '-' .$order_id;

                    $currentOrder->save();
                    $currentOrder = ApiOrder::where('id', $order_id)->with(
                        'contact',
                        'user.contact',
                        'apiOrderItem.product.options',
                        'texClasses'
                    )->first();
                    $order_contact = Contact::where('contact_id', $currentOrder->memberId)->first();
                    $reference = $currentOrder->reference;
                    if (session()->has('cart')) {
                        foreach ($cart_items as $cart_item) {
                            $OrderItem = new ApiOrderItem;
                            $OrderItem->order_id = $order_id;
                            $OrderItem->product_id = $cart_item['product_id'];
                            $OrderItem->quantity =  $cart_item['quantity'];
                            $OrderItem->price = $cart_item['price'];
                            $OrderItem->option_id = $cart_item['option_id'];
                            $OrderItem->save();
                        }
                    } else {
                        session()->forget('cart');
                        return redirect('/');
                    }

                    $order_items = ApiOrderItem::with('order.texClasses', 'product.options')
                        ->where('order_id', $order_id)
                        ->get();

                    $contact = Contact::where('user_id', auth()->id())->first();
                    // $this->shipping_order($order_id , $currentOrder , $order_contact);
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
                            'zip' => $contact->postCode,
                            'mobile' => $contact->mobile,
                            'phone' => $contact->phone,
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
                        'currentOrder' => $currentOrder,
                        'count' => $count,
                        'from' => SettingHelper::getSetting('noreply_email_address')
                    ];

                    if (!empty($users_with_role_admin)) {
                        foreach ($users_with_role_admin as $role_admin) {
                            $subject = 'New order received';
                            $adminTemplate = 'emails.admin-order-received';
                            $data['email'] = $role_admin->email;
                            MailHelper::sendMailNotification('emails.admin-order-received', $data);
                        }
                    }
                    $credit_limit = $contact->credit_limit;
                    $parent_email = Contact::where('contact_id', $active_contact_id)->first();

                    if ($credit_limit < $cart_total) {
                        if ($is_primary == null) {
                            $data['subject'] = 'Credit limit reached';
                            $data['email'] = $parent_email->email;

                            MailHelper::sendMailNotification('emails.credit-limit-reached', $data);
                        }
                    } else {
                        $data['subject'] = 'Your order has been received';
                        $data['email'] = $email;
                    }
                    MailHelper::sendMailNotification('emails.admin-order-received', $data);


                    $email_sent_to_users = [];
                    $user = User::where('id',  Auth::id())->first();
                    $all_ids = UserHelper::getAllMemberIds($user);
                    $all_members = Contact::whereIn('id', $all_ids)->get();
                    foreach ($all_members as $member) {
                        $member_user = User::find($member->user_id);
                        if (!empty($member_user) && $member_user->hasRole(['Order Approver'])) {
                            if (isset($email_sent_to_users[$member_user->id])) {
                                continue;
                            }

                            $email_sent_to_users[$member_user->id] = $member_user;
                            $data['name'] = $member_user->firstName;
                            $data['subject'] = 'New order awaiting approval';
                            $data['email'] = $member_user->email;
                            MailHelper::sendMailNotification('emails.user-order-received', $data);
                        }
                    }

                    session()->forget('cart');
                    return Redirect::route('thankyou', $order_id);
                }
            }
        }

        return redirect('/checkout');
    }

    // delete item from order by admin 
    public function delete_order_item(Request $request) {
        $item_id = $request->item_id;
        $order_id = $request->order_id;
        $tax_rate = $request->tax_rate;
        $api_order_item_delete = ApiOrderItem::where('id', $item_id)->first();
        $api_order = ApiOrder::with('apiOrderItem')
        ->whereHas('apiOrderItem' , function($q){
            $q->where('deleted_at' , null);
        })
        ->where('id', $order_id)
        ->first();
        if (count($api_order->apiOrderItem) > 1) {
            if (!empty($api_order_item_delete)) {
            
                $update_order = ApiOrder::where('id', $order_id)->first();
                $old_subtotal = $update_order->productTotal;
                $old_tax_value = $old_subtotal * $tax_rate / 100;
                $new_subtotal = $old_subtotal - ($api_order_item_delete->quantity * $api_order_item_delete->price);
                $new_tax_value = $new_subtotal * $tax_rate / 100;
                $new_grand_total = $new_subtotal + $new_tax_value;
                $update_order->total = $new_subtotal;
                $update_order->productTotal = $new_subtotal;
                $update_order->total_including_tax = $new_grand_total;
                $update_order->save();
                $api_order_item_delete->delete();
    
                return response()->json(
                    [
                        'success' => true , 
                        'message' => 'Item deleted successfully.', 
                        'item_count' => count($api_order->apiOrderItem)
                    ]
                );
            } else {
                return response()->json(['success' => false , 'message' => 'Item not found.']);
            }
        } else {
            return response()->json(
                [
                    'success' => false ,
                    'message' => 'By deleteing this item order will be deleted. ',
                    'item_count' => count($api_order->apiOrderItem),
                ]
            );
        }
    }

    // delete last item from order by admin with order
    public function delete_order(Request $request) {
        $item_id = $request->item_id;
        $order_id = $request->order_id;
        $tax_rate = $request->tax_rate;
        $api_order_item_delete = ApiOrderItem::where('id', $item_id)->first();
        if(!empty($api_order_item_delete)) {
            $api_order = ApiOrder::where('id', $order_id)->first();
            $api_order->delete();
            $api_order_item_delete->delete();
            return response()->json(['success' => true , 'message' => 'Order deleted successfully.']);
        } else {
            return response()->json(['success' => false , 'message' => 'Order not found.']);
        }
    }

    //update order by admin 
    public function update_order (Request $request) {
        $order_id = $request->order_id;
        $order = ApiOrder::where('id', $order_id)->first();
        $order_items = ApiOrderItem::where('order_id', $order_id)->get();
        $subtotal = $request->subtotal;
        $total_including_tax = $request->total_including_tax;

        $order_items_data = $request->item_data;
        $order->update([
            'total' => $subtotal,
            'productTotal' => $subtotal,
            'total_including_tax' => $total_including_tax
        ]);


        foreach($order_items as $order_item) {
            foreach ($order_items_data as $item_data) {
                if($order_item->id == $item_data['item_id']) {
                    $order_item->update([
                        'quantity' => $item_data['item_quantity'],
                        'price' => $item_data['item_price']
                    ]);
                }
            }
            
        }
        return response()->json(['success' => true , 'message' => 'Order updated successfully.']);
    }

    // add product in the order

    public function addProduct(Request $request) {
        $product_id = $request->product_id;
        $tax_rate = $request->tax_rate;
        $order_id = $request->order_id;
        $option_id = $request->option_id;
        $product_price = 0;
        $order = ApiOrder::where('id', $order_id)->first();
        $price_column = UserHelper::getUserPriceColumn($is_admin = true , $order->user_id);

        $comparePrice_column = Pricingnew::where('option_id', $option_id)->first();
        $product_price = $comparePrice_column->$price_column; 
        
        $old_subtotal = $order->productTotal;
        $new_subtotal = $old_subtotal + $product_price;
        $new_tax_value = $new_subtotal * $tax_rate / 100;
        $new_grand_total = $new_subtotal + $new_tax_value;
        
        $order->total = $new_subtotal;
        $order->productTotal = $new_subtotal;
        $order->total_including_tax = $new_grand_total;
        $order->save();
        
        $api_order_item = ApiOrderItem::where('order_id', $order_id)->where('product_id', $product_id)->first();
        
        if (!empty($api_order_item)) {
            $api_order_item->quantity = $api_order_item->quantity + 1;
            $api_order_item->save();
            return response()->json(['success' => true , 'message' => 'Product added successfully.']);
        } else{
            $order_item = new ApiOrderItem();
            $order_item->order_id = $order_id;
            $order_item->product_id = $product_id;
            $order_item->quantity = 1;
            $order_item->price = $product_price;
            $order_item->option_id = $request->option_id;
            $order_item->save();

            return response()->json(['success' => true , 'message' => 'Product added successfully.']);
        }

    }

    public function searchProduct (Request $request) {
        $search = $request->search_value;
        $products = Product::with(['options' => function ($q) {
            $q->where('status', '!=', 'Disabled');
        }])->orWhere(function (Builder $query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('code', 'LIKE', '%' . $search . '%');
        })
        ->where('status', '!=', 'Inactive')
        ->get();
        return response()->json(['success' => true , 'data' => $products]);
    }

    public function webhook(Request $request) {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $signature, config('services.stripe.webhook_secret'));
            // dd($event);
            // Handle the event based on its type
            switch ($event->type) {
                case 'checkout.session.completed':
                    // Handle checkout session completed event
                    break;
                case 'payment_intent.succeeded':
                    // Handle payment intent succeeded event
                    break;
                // Add more cases for other event types
            }

            return response()->json(['status' => 'success'], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }


    // Test Funtions 

    
    
    // public function shipping_order($order_id , $currentOrder , $order_contact) {
    //     $order_items = ApiOrderItem::with('order.texClasses', 'product.options', 'product')->where('order_id', $order_id)->get();
    //     for ($i = 0; $i <= count($order_items) - 1; $i++){
    //         $items[] = [
    //             'name' => $order_items[0]->product->name,
    //             'sku' => $order_items[0]->product->code,
    //             'quantity' => $order_items[0]->quantity,
    //             'unitPrice' => $order_items[0]->price,
    //         ];  
    //     }
        
    //     $client = new \GuzzleHttp\Client();
    //     $shipstation_order_url = config('services.shipstation.shipment_order_url');
    //     $shipstation_api_key = config('services.shipstation.key');
    //     $shipstation_api_secret = config('services.shipstation.secret');
    //     $carrier_code = AdminSetting::where('option_name', 'shipping_carrier_code')->first();
    //     $service_code = AdminSetting::where('option_name', 'shipping_service_code')->first();
    //     $created_date = \Carbon\Carbon::parse($currentOrder->createdDate);
    //     $getDate =$created_date->format('Y-m-d');
    //     $getTime = date('H:i:s' ,strtotime($currentOrder->createdDate));
    //     $order_created_date = $getDate . 'T' . $getTime ;
    //     $calculate_tax =$currentOrder->total_including_tax - $currentOrder->productTotal;
    //     $tax = $calculate_tax - $currentOrder->shipment_price;
    //     $orderStatus = null;
    //     if ($currentOrder->payment_status == 'paid') {
    //         $orderStatus = 'awaiting_shipment';
    //     } else {
    //         $orderStatus = 'awaiting_payment';
    //     }
    //     $data = [
    //         'orderNumber' => $order_id,
    //         'orderKey' => $currentOrder->reference,
    //         'orderDate' => $order_created_date,
    //         'carrierCode' => $carrier_code->option_value,
    //         'serviceCode' => $service_code->option_value,
    //         'orderStatus' => $orderStatus,
    //         'shippingAmount' => number_format($currentOrder->shipment_price , 2),
    //         "amountPaid" => number_format($currentOrder->total_including_tax , 2),
    //         "taxAmount" => number_format($tax, 2),
    //         'shipTo' => [
    //             "name" => $order_contact->firstName . $order_contact->lastName,
    //             "company" => $order_contact->company,
    //             "street1" => $order_contact->address1 ? $order_contact->address1 : $order_contact->postalAddress,
    //             "street2" => $order_contact->address2 ? $order_contact->address2 : $order_contact->postalAddress,
    //             "city" => $order_contact->city ? $order_contact->city : $order_contact->postalCity,
    //             "state" => $order_contact->state ? $order_contact->state : $order_contact->postalState,
    //             "postalCode" => $order_contact->postCode ? $order_contact->postCode : $order_contact->postalPostCode,
    //             "country"=>"US",
    //             "phone" => $order_contact->phone ? $order_contact->phone : $order_contact->mobile,
    //             "residential"=>true
    //         ],
    //         'billTo' => [
    //             "name" => $order_contact->firstName . $order_contact->lastName,
    //             "company" => $order_contact->company,
    //             "street1" => $order_contact->address1 ? $order_contact->address1 : $order_contact->postalAddress,
    //             "street2" => $order_contact->address2 ? $order_contact->address2 : $order_contact->postalAddress,
    //             "city" => $order_contact->city ? $order_contact->city : $order_contact->postalCity,
    //             "state" => $order_contact->state ? $order_contact->state : $order_contact->postalState,
    //             "postalCode" => $order_contact->postCode ? $order_contact->postCode : $order_contact->postalPostCode,
    //             "country"=>"US",
    //             "phone" => $order_contact->phone ? $order_contact->phone : $order_contact->mobile,
    //             "residential"=>true
    //         ],
    //         'items'=> $items
    //     ];
    //     $headers = [
    //         "Content-Type: application/json",
    //         'Authorization' => 'Basic ' . base64_encode($shipstation_api_key . ':' . $shipstation_api_secret),
    //     ];
    //     $responseBody = null;
    //     $response = $client->post($shipstation_order_url, [
    //         'headers' => $headers,
    //         'json' => $data,
    //     ]);

    //     $statusCode = $response->getStatusCode();
    //     $responseBody = $response->getBody()->getContents();

    //     dd(json_decode($responseBody));
    // }


    //create label for order
    public function create_label(Request $request) {
        
        $order_id = $request->order_id;
        $order = ApiOrder::where('id', $order_id)->first();
        $order_contact = Contact::where('contact_id', $order->memberId)->first();
        $client = new \GuzzleHttp\Client();
        $shipstation_label_url = config('services.shipstation.shipment_label_url');
        $shipstation_api_key = config('services.shipstation.key');
        $shipstation_api_secret = config('services.shipstation.secret');
        $carrier_code = AdminSetting::where('option_name', 'shipping_carrier_code')->first();
        $service_code = AdminSetting::where('option_name', 'shipping_service_code')->first();
        $shipping_package = AdminSetting::where('option_name', 'shipping_package')->first();
        $company_name = AdminSetting::where('option_name', 'website_name')->first();
        $getDate = now()->format('Y-m-d');
        $order_items = ApiOrderItem::with('order.texClasses', 'product.options', 'product')->where('order_id', $order_id)->get();
        $products_weight = 0;
        foreach ($order_items as $order_item) {
            $product_options = ProductOption::where('product_id', $order_item['product_id'])->where('option_id' , $order_item['option_id'])->get();
            foreach ($product_options as $product_option) {
                $products_weight += $product_option->optionWeight * $order_item['quantity'];
            }
        }
        $data = [
            'orderId' => $order->shipstation_orderId,
            'carrierCode' => $carrier_code->option_value,
            'serviceCode' => $service_code->option_value,
            'packageCode' => $shipping_package->option_value,
            "confirmation" => "delivery",
            // 'shipFrom' => [
            //     "name" => 'Kevin',
            //     "company" => $company_name->option_value,
            //     "street1" => '5671 Warehouse Way',
            //     "street2" => '5671 Warehouse Way',
            //     "city" => 'Sacramento',
            //     "state" => 'CA',
            //     "postalCode" => '95826',
            //     "country"=>"US",
            //     "phone" => '(916) 281-3090',
            //     "residential"=>true
            // ],
            // 'shipTo' => [
            //     "name" => $order_contact->firstName . $order_contact->lastName,
            //     "company" => $order_contact->company,
            //     "street1" => $order_contact->address1 ? $order_contact->address1 : $order_contact->postalAddress1,
            //     "street2" => $order_contact->address2 ? $order_contact->address2 : $order_contact->postalAddress1,
            //     "city" => $order_contact->city ? $order_contact->city : $order_contact->postalCity,
            //     "state" => $order_contact->state ? $order_contact->state : $order_contact->postalState,
            //     "postalCode" => $order_contact->postCode ? $order_contact->postCode : $order_contact->postalPostCode,
            //     "country"=>"US",
            //     "phone" => $order_contact->phone ? $order_contact->phone : $order_contact->mobile,
            //     "residential"=>true
            // ],
            'weight' => [
                "value" => $products_weight,
                "units" => "pounds"
            ],
            'shipDate'=> $getDate,
            'testLabel' => false,
        ];
        $headers = [
            "Content-Type: application/json",
            'Authorization' => 'Basic ' . base64_encode($shipstation_api_key . ':' . $shipstation_api_secret),
        ];
        $responseBody = null;
        try {
            $response = $client->post($shipstation_label_url, [
                'headers' => $headers,
                'json' => $data,
            ]);
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();
            $response = json_decode($responseBody);
            echo "<pre>";var_dump($response);die;
            $order->update([
                'is_shipped' => 1,
                'label_created' => 1,
            ]);

            $label = [
                'orderId' => $response->orderId,
                'labelData' => $response->labelData,
            ];
            $pdfContent = file_get_contents($label['labelData']);
            return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="label.pdf"');
        
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('admin/orders')->with('error', $e->getMessage());
        }
        
    }
    

}

