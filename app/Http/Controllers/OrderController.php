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
use App\Models\ShipstationApiLogs;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;

use App\Helpers\SettingHelper;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Square\SquareClient;
use Square\Models\Money;
use Square\Models\QuickPay;
use Square\Models\CreatePaymentLinkRequest;
use Square\Models\Order as SquareOrder;
use Square\Exceptions\ApiException;
use Illuminate\Support\Facades\Storage;
use Square\Models\OrderLineItem;
use Stripe\TaxRate;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(
            [
                'method_name' => 'required',
            ]
        );
        $address_1_shipping = $request->address_1_shipping;
        $state_shipping = $request->state_shipping;
        $zip_code_shipping = $request->zip_code_shipping;

        $address_1_billing = $request->address_1_billing;
        $state_billing = $request->state_billing;
        $zip_code_billing = $request->zip_code_billing;
        if (empty($address_1_shipping) || empty($state_shipping) || empty($zip_code_shipping) || empty($address_1_billing) || empty($state_billing) || empty($zip_code_billing)) {
            return back()->with('error', 'Blilling and Shipping address is required.');
        }
        
        $paymentMethod = $request->input('method_name');
        $paymentMethodOption = $request->input('method_option');
        $paymentMethod = $paymentMethodOption;
        //check if user have already contact with cin7
        $existing_contact = Contact::where('user_id', Auth::id())->first();
        $session_contact_id = Session::get('contact_id');
        $order_status = OrderStatus::where('status', 'New')->first();
        $discount_amount = !empty($request->discount_amount) ? $request->discount_amount : 0;
        $discount_type = $request->discount_variation;
        $discount_id =  $request->discount_id;
        if (!empty($session_contact_id)) {
            $contact = Contact::where('contact_id', $session_contact_id)->first();
            if ($contact) {
                $active_contact_id = $contact->contact_id;
            } else {
                $contact = Contact::where('secondary_id', $session_contact_id)->first();
                $active_contact_id = $contact->parent_id;
            }
            if ($active_contact_id) {
                // $cart_items = session()->get('cart');
                $cart_items = UserHelper::switch_price_tier($request);
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
                $enable_discount_setting = AdminSetting::where('option_name', 'enable_discount')->first();
                if (!empty($enable_discount_setting) && strtolower($enable_discount_setting->option_value) == 'yes') {
                    $shipment_price = !empty($request->shipment_price) ? $request->shipment_price : 0;
                    $total_tax = !empty($request->total_tax) ? $request->total_tax : 0;
                    $total_amount_with_discount = $cart_total - $discount_amount;
                    $order_total = $total_amount_with_discount + $total_tax + $shipment_price;
                } else {
                    $order_total = $request->incl_tax;
                }
                

                $is_primary = Contact::where('contact_id', $session_contact_id)->first();
                $enable_stripe_checkout_setting = AdminSetting::where('option_name', 'enable_stripe_checkout')->first();
                $square_payment_mode = AdminSetting::where('option_name', 'square_payment_mode')->first();
                $stripe_is_enabled = !empty($enable_stripe_checkout_setting) && strtolower($enable_stripe_checkout_setting->option_value) == 'yes';
                $square_is_enabled = !empty($square_payment_mode) && strtolower($square_payment_mode->option_value) == 'yes';
                $go_to_stripe_checkout = false;
                $go_to_square_checkout = false;
                $pay_in_advance = strtolower($request->paymentTerms) === 'pay in advanced' ? true : false;

                if ($stripe_is_enabled && $pay_in_advance) {
                    $go_to_stripe_checkout = true;
                }

                if ($square_is_enabled && $pay_in_advance) {
                    $go_to_square_checkout = true;
                }


                $square_payment_accessToken = AdminSetting::where('option_name', 'square_payment_access_token')->first();
                $square_payment_environment = AdminSetting::where('option_name', 'square_payment_environment')->first();
                $square_payment_location_id = AdminSetting::where('option_name', 'square_payment_location_id')->first();

                $tax_rate = 0;
                if ($go_to_square_checkout == true) {
                    $client = new SquareClient([
                        'accessToken' => $square_payment_accessToken->option_value,
                        'environment' => !empty($square_payment_environment->option_value) ? $square_payment_environment->option_value : 'sandbox'
                    ]);
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
                    $order->order_status_id = $order_status->id;
                    $order->user_id = Auth::id();
                    $order->status = "DRAFT";
                    $order->stage = "New";
                    $order->payment_status = "unpaid";
                    $order->logisticsCarrier = $paymentMethod;
                    $order->tax_class_id = $request->tax_class_id;
                    $order->user_switch = $user_switch;
                    $order->total_including_tax = $request->incl_tax;
                    $order->po_number = $request->po_number;
                    $order->paymentTerms = $request->paymentTerms;
                    $order->memo = $request->memo;
                    $order->date = $request->date;
                    $order->shipment_price = $request->shipment_price;
                    $order->is_square = 1;
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

                    //adding comment to order

                    $order_comment = new OrderComment;
                    $order_comment->order_id = $order_id;
                    $order_comment->comment = 'Order Placed through Square Payments';
                    $order_comment->save();

                    $order_contact = Contact::where('contact_id', $currentOrder->memberId)->first();
                    $product_prices = [];
                    $lineItems  = [];
                    $product_names = [];
                    $reference = $currentOrder->reference;
                    $get_tax_class = !empty($currentOrder->texClasses) ? $currentOrder->texClasses : null;
                    if (!empty($get_tax_class)) {
                        $tax_rate = $currentOrder->total * ($get_tax_class->rate / 100);
                    } else {
                        $tax_rate = 0;
                    }
                    // Initialize Square Checkout API
                    $checkoutApi = $client->getCheckoutApi();
                    $line_items = [];

                    
                    
                    if (session()->has('cart')) {
                        foreach ($cart_items as $cart_item) {
                            $OrderItem = new ApiOrderItem;
                            $OrderItem->order_id = $order_id;
                            $OrderItem->product_id = $cart_item['product_id'];
                            $OrderItem->quantity =  $cart_item['quantity'];
                            $OrderItem->price = $cart_item['price'];
                            $OrderItem->option_id = $cart_item['option_id'];
                            $OrderItem->save();
                            
                            $order_line_item = new OrderLineItem($cart_item['quantity']);
                            $order_line_item->setName($cart_item['name']);
                            // Create a Money object for base price
                            $base_price_money = new Money();
                            $base_price_money->setAmount($cart_item['price'] * 100); // Convert price to cents
                            $base_price_money->setCurrency('USD');

                            // Set base price money for the order line item
                            $order_line_item->setBasePriceMoney($base_price_money);
                            $line_items[] = $order_line_item;
                        
                        }

                        if ($tax_rate > 0) {
                            $order_tax_item = new OrderLineItem(1); // Assuming only one unit of tax
                            $order_tax_item->setName('Tax');
                            $order_tax_item->setQuantity(1);
    
                            // Set base price for tax line item to be the same as the total tax amount
                            $base_tax_price_money = new Money();
                            $base_tax_price_money->setAmount($tax_rate * 100); // Convert total price to cents
                            $base_tax_price_money->setCurrency('USD');
                            $order_tax_item->setBasePriceMoney($base_tax_price_money);
                            $line_items[] = $order_tax_item;
                        }
    
                        if (!empty($request->shipment_price) && $request->shipment_price > 0) {
                            $order_shipping_item = new OrderLineItem(2); // Assuming only one unit of tax
                            $order_shipping_item->setName('Shipment Price');
                            $order_shipping_item->setQuantity(1);
    
                            // Set base price for tax line item to be the same as the total tax amount
                            $base_shipping_price_money = new Money();
                            $base_shipping_price_money->setAmount($request->shipment_price * 100); // Convert total price to cents
                            $base_shipping_price_money->setCurrency('USD');
                            $order_shipping_item->setBasePriceMoney($base_shipping_price_money);
                            $line_items[] = $order_shipping_item;
                        }
                        // Create an Order object and set line items
                        $square_order = new SquareOrder($square_payment_location_id->option_value);
                        $square_order->setLineItems($line_items);

                        $pre_populated_data = new \Square\Models\PrePopulatedData();
                        $pre_populated_data->setBuyerEmail(!empty($order_contact->email) ? $order_contact->email : '');
                        $pre_populated_data->setBuyerPhoneNumber(!empty($order_contact->phone) ? $order_contact->phone : '');
                        // Create a CreatePaymentLinkRequest object and set the order
                        $body = new CreatePaymentLinkRequest();
                        $body->setIdempotencyKey($order->id);
                        $body->setOrder($square_order );
                        $body->setPrePopulatedData($pre_populated_data);
                        try {
                            // Make the API request to create the payment link
                            $apiResponse = $checkoutApi->createPaymentLink($body);
                        
                            if ($apiResponse->getStatusCode() == 200){
                                $checkoutUrl = $apiResponse->getResult()->getPaymentLink()->getUrl();
                            } else {
                                // Handle API errors
                                $errors = $apiResponse->getErrors();
                            }
                        } catch (\Square\Exceptions\ApiException $e) {

                            return back()->with('error', 'Something went wrong, please try again later.');
                            // Handle API exceptions
                        } catch (\Exception $e) {
                            return back()->with('error', 'Something went wrong, please try again later.');
                            // Handle other exceptions
                        }

                        // $go_to_shipstation = false;
                        // if (!empty($order_contact) && !empty($order_contact->is_parent == 1) && !empty($order_contact->address1) && !empty($order_contact->postalAddress1)) {
                        //     $go_to_shipstation = true;
                        // }
                        // $check_shipstation_create_order_status = AdminSetting::where('option_name', 'create_order_in_shipstation')->first();
                        // if (!empty($check_shipstation_create_order_status) && strtolower($check_shipstation_create_order_status->option_value) == 'yes' && ($go_to_shipstation == true)) {
                        //     $shiping_order = UserHelper::shipping_order($order_id , $currentOrder , $order_contact);
                        //     if ($shiping_order['statusCode'] == 200) {
                        //         $orderUpdate = ApiOrder::where('id', $order_id)->update([
                        //             'shipstation_orderId' => $shiping_order['responseBody']->orderId,
                        //         ]);
                        //     }
                        // }
                        
                    } else {
                        session()->forget('cart');
                        return redirect('/');
                    }

                    session()->forget('cart');
                    return redirect($checkoutUrl);
                }
                elseif ($go_to_stripe_checkout) {
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
                    $order->order_status_id = $order_status->id;
                    $order->user_id = Auth::id();
                    $order->status = "DRAFT";
                    $order->stage = "New";
                    $order->payment_status = "unpaid";
                    $order->logisticsCarrier = $paymentMethod;
                    $order->tax_class_id = $request->tax_class_id;
                    $order->user_switch = $user_switch;
                    $order->po_number = $request->po_number;
                    $order->paymentTerms = $request->paymentTerms;
                    $order->memo = $request->memo;
                    $order->date = $request->date;
                    $order->shipment_price = $request->shipment_price;
                    $order->total_including_tax = $order_total;
                    $order->discount_id = $discount_id;
                    $order->discount_amount = $discount_amount;
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

                    //adding comment to order

                    $order_comment = new OrderComment;
                    $order_comment->order_id = $order_id;
                    $order_comment->comment = 'Order Placed through Stripe';
                    $order_comment->save();

                    $order_contact = Contact::where('contact_id', $currentOrder->memberId)->first();
                    $product_prices = [];
                    $reference = $currentOrder->reference;
                    $get_tax_class = !empty($currentOrder->texClasses) ? $currentOrder->texClasses : null;
                    if (!empty($get_tax_class)) {
                        $tax_rate = $currentOrder->total * ($get_tax_class->rate / 100);
                    } else {
                        $tax_rate = 0;
                    }
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
                                'metadata' => [
                                    'quantity'=> $cart_item['quantity']
                                ]
                            ]);
                            array_push($product_prices, $productPrice);
                        }
                        if (!empty($tax_rate) && $tax_rate > 0) {
                            $formatted_tax = number_format($tax_rate, 2);
                            $formatted_tax_rate = str_replace(',', '', $formatted_tax);
                            $formatted_tax_value = number_format(($formatted_tax_rate * 100) , 2);
                            $tax_value = str_replace(',', '', $formatted_tax_value);
                            $products_tax= $stripe->products->create([
                                'name' => 'Tax',
                            ]);

                            $taxproductPrice = $stripe->prices->create([
                                'unit_amount_decimal' => $tax_value,
                                'currency' => 'usd',
                                'product' => $products_tax->id
                            ]);
                        }

                        for ($i = 0; $i <= count($product_prices) - 1; $i++){
                            $items[] = [
                                'price' => $product_prices[$i]->id,
                                'quantity' => $product_prices[$i]['metadata']['quantity'],
                            ];  
                        }
                        if (!empty($tax_rate) && $tax_rate > 0) {
                            $items[] = [
                                'price' => $taxproductPrice->id,
                                'quantity' => '1',
                            ];
                        }

                        //adding discount to order
                        // $enable_discount_setting = AdminSetting::where('option_name', 'enable_discount')->first();
                        // if (!empty($enable_discount_setting) && strtolower($enable_discount_setting->option_value) == 'yes') {
                        //     if (intval($discount_amount) > 0) {
                        //         $discount_price = number_format(($discount_amount * 100) , 2);
                        //         $discount_value = str_replace(',', '', $discount_price);
                        //         $discount_product = $stripe->products->create([
                        //             'name' => 'Discount',
                        //         ]);
                        //         $discount_product_price = $stripe->prices->create([
                        //             'unit_amount_decimal' => $discount_value,
                        //             'currency' => 'usd',
                        //             'product' => $discount_product->id
                        //         ]);
                        //         $items[] = [
                        //             'price' => $discount_product_price->id,
                        //             'quantity' => '1',
                        //         ];
                        //     }
                        // }

                        // adding shipping price to order
                        if (!empty($request->shipment_price) && $request->shipment_price > 0) {
                            $shipment_price = number_format(($request->shipment_price * 100) , 2);
                            $shipment_value = str_replace(',', '', $shipment_price);
                            $shipment_product = $stripe->products->create([
                                'name' => 'Shipment',
                            ]);
                            $shipment_product_price = $stripe->prices->create([
                                'unit_amount_decimal' => $shipment_value,
                                'currency' => 'usd',
                                'product' => $shipment_product->id
                            ]);
                            $items[] = [
                                'price' => $shipment_product_price->id,
                                'quantity' => '1',
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
                            // 'shipping_cost' =>  !empty($request->shipment_price) ? $request->shipment_price : 0,
                            'customer_email' => auth()->user()->email,
                            
                        ]);
                        // $go_to_shipstation = false;
                        // if (!empty($order_contact) && !empty($order_contact->is_parent == 1) && !empty($order_contact->address1) && !empty($order_contact->postalAddress1)) {
                        //     $go_to_shipstation = true;
                        // }
                        // $check_shipstation_create_order_status = AdminSetting::where('option_name', 'create_order_in_shipstation')->first();
                        // if (!empty($check_shipstation_create_order_status) && strtolower($check_shipstation_create_order_status->option_value) == 'yes' && ($go_to_shipstation == true)) {
                        //     $shiping_order = UserHelper::shipping_order($order_id , $currentOrder , $order_contact);
                        //     if ($shiping_order['statusCode'] == 200) {
                        //         $orderUpdate = ApiOrder::where('id', $order_id)->update([
                        //             'shipstation_orderId' => $shiping_order['responseBody']->orderId,
                        //         ]);
                        //     }
                        // }
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
                    $order->order_status_id = $order_status->id;
                    $order->user_id = Auth::id();
                    $order->status = "DRAFT";
                    $order->stage = "New";
                    $order->logisticsCarrier = $paymentMethod;
                    $order->tax_class_id = $request->tax_class_id;
                    $order->user_switch = $user_switch;
                    $order->po_number = $request->po_number;
                    $order->paymentTerms = $request->paymentTerms;
                    $order->memo = $request->memo;
                    $order->date = $request->date;
                    $order->shipment_price = $request->shipment_price;
                    $order->total_including_tax = $order_total;
                    $order->discount_id = $discount_id;
                    $order->discount_amount = $discount_amount;
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
                    // $user_default = User::where('id', Auth::id())->first();
                    // $all_ids = UserHelper::getAllMemberIds($user_default);
                    // $check_default_user = Contact::whereIn('id', $all_ids)->where('is_default' , 1)->first();
                    // if (!empty($check_default_user)) {
                    //     $contact = Contact::where('id', $check_default_user->id)->first();
                    // } else {
                    //     $contact = Contact::where('user_id', auth()->id())->first();
                    // }
                    $user = User::where('id', $currentOrder->user_id)->first();
                    $all_ids = UserHelper::getAllMemberIds($user);
                    $contact_ids = Contact::whereIn('id', $all_ids)->pluck('contact_id')->toArray();
                    $customer = ApiOrder::with(['createdby'])->whereIn('memberId', $contact_ids)
                    ->with('contact' , function($query) {
                        $query->orderBy('company');
                    })
                    ->with('apiOrderItem.product')
                    ->where('id' , $order_id)
                    ->first();
                    // $go_to_shipstation = false;
                    // if (!empty($order_contact) && !empty($order_contact->is_parent == 1) && !empty($order_contact->address1) && !empty($order_contact->postalAddress1)) {
                    //     $go_to_shipstation = true;
                    // }
                    // $check_shipstation_create_order_status = AdminSetting::where('option_name', 'create_order_in_shipstation')->first();
                    // if (!empty($check_shipstation_create_order_status) && strtolower($check_shipstation_create_order_status->option_value) == 'yes' && ($go_to_shipstation == true)) {
                    //     $shiping_order = UserHelper::shipping_order($order_id , $currentOrder , $order_contact);
                    //     if ($shiping_order['statusCode'] == 200) {
                    //         $orderUpdate = ApiOrder::where('id', $order_id)->update([
                    //             'shipstation_orderId' => $shiping_order['responseBody']->orderId,
                    //         ]);
                    //     }
                    // }
                    
                    $user_email = Auth::user();
                    $count = $order_items->count();
                    $best_products = Product::where('status', '!=', 'Inactive')->orderBy('views', 'DESC')->limit(4)->get();
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
                            'postalCity' => $customer->contact->postalCity,
                            'postalState' => $customer->contact->postalState,
                            'postalPostCode' => $customer->contact->postalPostCode,
                        ],
                        'payment_terms' =>  '30 Days from Invoice',
                        'shipping_fee' => !empty($currentOrder->shipment_price) ? $currentOrder->shipment_price : '',
                        'best_product' => $best_products,
                        'user_email' =>   $customer->contact->email,
                        'currentOrder' => $currentOrder,
                        'count' => $count,
                        'order_id' => $order_id,
                        'company' => $currentOrder->contact->company, 
                        'order_status' => '',
                    ];
                    $name = $customer->contact->firstName;
                    $email =  $customer->contact->email;
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
                        'user_email' => $email,
                        'currentOrder' => $currentOrder,
                        'count' => $count,
                        'from' => SettingHelper::getSetting('noreply_email_address')
                    ];

                    if (!empty($users_with_role_admin)) {
                        foreach ($users_with_role_admin as $role_admin) {
                            $subject = 'New Indoorsun Hydro order' .'#'.$currentOrder->id. ' ' . 'received';
                            $adminTemplate = 'emails.admin-order-received';
                            $data['subject'] = $subject;
                            $data['email'] = $role_admin->email;
                            MailHelper::sendMailNotification('emails.admin-order-received', $data);
                        }
                    }
                    $credit_limit = $customer->contact->credit_limit;
                    $parent_email = Contact::where('contact_id', $active_contact_id)->first();

                    // if ($credit_limit < $cart_total) {
                    //     if ($is_primary == null) {
                    //         $data['subject'] = 'Credit limit reached';
                    //         $data['email'] = $parent_email->email;

                    //         MailHelper::sendMailNotification('emails.credit-limit-reached', $data);
                    //     }
                    //     if ($is_primary != null) {
                    //         $data['subject'] = 'Credit limit reached';
                    //         $data['email'] = $email;

                    //         MailHelper::sendMailNotification('emails.credit-limit-reached', $data);
                    //     }
                        
                    //     $data['subject'] = 'Your Indoorsun Hydro order' .'#'.$currentOrder->id. ' ' .'has been received';
                    //     $data['email'] = $email;
                    //     MailHelper::sendMailNotification('emails.admin-order-received', $data);
                    // } else {
                    //     $data['subject'] = 'Your Indoorsun Hydro order' .'#'.$currentOrder->id. ' ' .'has been received';
                    //     $data['email'] = $email;
                    //     MailHelper::sendMailNotification('emails.admin-order-received', $data);
                    // }
                    if (!empty($email)) {
                        $data['subject'] = 'Your Indoorsun Hydro order' .'#'.$currentOrder->id. ' ' .'has been received';
                        $data['email'] = $email;
                        MailHelper::sendMailNotification('emails.admin-order-received', $data);
                    }
                    $email_sent_to_users = [];
                    
                    // $user = User::where('id',  Auth::id())->first();
                    // $all_ids = UserHelper::getAllMemberIds($user);
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

    //create label for order
    public function create_label(Request $request) {
        $order_id = $request->order_id;
        $order = ApiOrder::where('id', $order_id)->first();
        if ($order->label_created == 1) {
            return redirect('admin/orders')->with('error', 'Label already created for this order.');
        } 
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
        $responseBody = null;
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
        $check_mode = AdminSetting::where('option_name', 'shipment_mode')->first();
        if  (strtolower($check_mode->option_value) == strtolower('sandbox')) {
            $labelData = UserHelper::shipment_label();

            $label_data = base64_decode($labelData);
            $file_name = 'label-' . $order_id . '-' . date('YmdHis') . '.pdf';
            $label_path = 'public/' . $file_name;
            Storage::disk('local')->put($label_path, $label_data);
            
            $order->update([
                'is_shipped' => 1,
                'label_created' => 1,
                'label_link' => $file_name,
            ]);

            $ship_station_api_logs  = new ShipstationApiLogs();      
            $ship_station_api_logs->api_url = $shipstation_label_url;
            $ship_station_api_logs->request = json_encode($data);
            $ship_station_api_logs->response = 'label created from sandbox';
            $ship_station_api_logs->status = 200;
            $ship_station_api_logs->save();

            return response($label_data)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename='.$file_name);
        } else {
            try {
                $response = $client->post($shipstation_label_url, [
                    'headers' => $headers,
                    'json' => $data,
                ]);
                $statusCode = $response->getStatusCode();
                
                $responseBody = $response->getBody()->getContents();
                $label_api_response = json_decode($responseBody);
                $label_data = base64_decode($label_api_response->labelData);
                
                $file_name = 'label-' . $order_id . '-' . date('YmdHis') . '.pdf';
                $label_path = 'public/' . $file_name;
                Storage::disk('local')->put($label_path, $label_data);
                
                $order->update([
                    'is_shipped' => 1,
                    'label_created' => 1,
                    'label_link' => $file_name,
                ]);
    
                $label = [
                    'orderId' => $label_api_response->orderId,
                    'labelData' => $label_api_response->labelData,
                ];
    
    
                $ship_station_api_logs  = new ShipstationApiLogs();      
                $ship_station_api_logs->api_url = $shipstation_label_url;
                $ship_station_api_logs->request = json_encode($data);
                $ship_station_api_logs->response = $responseBody;
                $ship_station_api_logs->status = $statusCode;
                $ship_station_api_logs->save();
    
                return response($label_data)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename='.$file_name);
                 
                    
            
            } catch (\Exception $e) {
                Log::error($e->getMessage());
    
                $ship_station_api_logs  = new ShipstationApiLogs();      
                $ship_station_api_logs->api_url = $shipstation_label_url;
                $ship_station_api_logs->request = json_encode($data);
                $ship_station_api_logs->response = $e->getMessage();
                $ship_station_api_logs->status = $response->getStatusCode();
                $ship_station_api_logs->save();
    
                return redirect('admin/orders')->with('error', $e->getMessage());
            }
            
        }
        
        
    }
    

    // download shipment label for order
    public function download_label($filename) {
        $file = Storage::disk('public')->get($filename);
        return response($file)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename='.$filename);
    }

    // uppdate order status manually 
    public function update_order_status(Request $request) {
        $order_id = $request->order_id;
        $order_status_id = $request->order_status_id;
        $order = ApiOrder::where('id', $order_id)->first();
        $previous_order_status = OrderStatus::where('id', $order->order_status_id)->first();
        $current_order_status = OrderStatus::where('id', $order_status_id)->first();
        $order->update([
            'order_status_id' => $order_status_id
        ]);


        $update_order_status_comment = new OrderComment;
        $update_order_status_comment->order_id = $order_id;
        $update_order_status_comment->comment = 'Order status updated manually from' . ' ' . (!empty($previous_order_status->status) ? $previous_order_status->status : '') . ' ' . 'to' . ' ' .  (!empty($current_order_status->status) ? $current_order_status->status : '');
        $update_order_status_comment->save();
        
        return response()->json(['success' => true , 'message' => 'Order status updated successfully.']);
    }

    public function mark_order_paid(Request $request) {
        $order_id = $request->order_id;
        $currentOrder = ApiOrder::where('id', $order_id)->with(
            'contact',
            'user.contact',
            'apiOrderItem.product.options',
            'texClasses'
        )->first();
        $order_contact = Contact::where('contact_id', $currentOrder->memberId)->first();
        $shiping_order = UserHelper::shipping_order($order_id , $currentOrder , $order_contact);
    
        if ($shiping_order['statusCode'] == 200) {
            $orderUpdate = ApiOrder::where('id', $order_id)->update([
                'payment_status' => 'paid',
                'shipstation_orderId' => $shiping_order['responseBody']->orderId,
            ]);
        }
        return redirect()->back()->with('success', 'Order marked as paid successfully.');
    }


    // uppdate order status manually 
    public function update_order_status_by_admin(Request $request) {
        $order_id = $request->order_id;
        $payment_status = $request->payment_status;
        $order_status_id = $request->order_status_id;
        $order = ApiOrder::where('id', $order_id)->first();
        $previous_order_status = OrderStatus::where('id', $order->order_status_id)->first();
        $current_order_status = OrderStatus::where('id', $order_status_id)->first();
        $order->update([
            'order_status_id' => $order_status_id,
            'payment_status' => $payment_status,
            'isApproved' => $current_order_status->status == 'Cancelled' ? 2 : $order->isApproved
        ]);


        $update_order_status_comment = new OrderComment;
        $update_order_status_comment->order_id = $order_id;
        $update_order_status_comment->comment = 'Order status updated manually from' . ' ' . (!empty($previous_order_status->status) ? $previous_order_status->status : '') . ' ' . 'to' . ' ' .  (!empty($current_order_status->status) ? $current_order_status->status : '');
        $update_order_status_comment->save();

        $order_items = ApiOrderItem::with('order.texClasses', 'product.options')->where('order_id', $order_id)->get();
        $user = User::where('id', $order->user_id)->first();
        $all_ids = UserHelper::getAllMemberIds($user);
        $contact_ids = Contact::whereIn('id', $all_ids)->pluck('contact_id')->toArray();
        $customer = ApiOrder::with(['createdby'])->whereIn('memberId', $contact_ids)
        ->with('contact' , function($query) {
            $query->orderBy('company');
        })
        ->with('apiOrderItem.product')
        ->where('id' , $order_id)
        ->first();
        $currentOrder = ApiOrder::where('id', $order_id)->with(
            'contact',
            'user.contact',
            'apiOrderItem.product.options',
            'texClasses'
        )->first();
        $count = $order_items->count();
        $best_products = Product::where('status', '!=', 'Inactive')->orderBy('views', 'DESC')->limit(4)->get();
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
                'phone' => $customer->contact->postalCity,
                'postalCity' => $customer->contact->postalState,
                'postalState' => $customer->contact->postalPostCode,
                'postalPostCode' => $customer->contact->postalPostCode
            ],
            'best_product' => $best_products,
            'user_email' =>   $customer->contact->email,
            'currentOrder' => $currentOrder,
            'count' => $count,
            'order_id' => $order_id,
            'company' => $currentOrder->contact->company, 
            'order_status' => 'updated',
        ];

        $name = $customer->contact->firstName;
        $email =  $customer->contact->email;
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
            'dateCreated' => Carbon::now(),
            'addresses' => $addresses,
            'best_product' => $best_products,
            'user_email' => $email,
            'currentOrder' => $currentOrder,
            'count' => $count,
            'from' => SettingHelper::getSetting('noreply_email_address')
        ];

        if (!empty($users_with_role_admin)) {
            foreach ($users_with_role_admin as $role_admin) {
                $subject = 'Indoorsun Hydro order' .'#'.$currentOrder->id. ' ' . 'status has been updated';
                $adminTemplate = 'emails.admin-order-received';
                $data['subject'] = $subject;
                $data['email'] = $role_admin->email;
                MailHelper::sendMailNotification('emails.admin-order-received', $data);
            }
        }
        $data['subject'] = 'Your Indoorsun Hydro order' .'#'.$currentOrder->id. ' ' .'status has been updated';
        $data['email'] = $email;
        MailHelper::sendMailNotification('emails.admin-order-received', $data);


        $email_sent_to_users = [];
        $all_members = Contact::whereIn('id', $all_ids)->get();
        foreach ($all_members as $member) {
            $member_user = User::find($member->user_id);
            if (!empty($member_user) && $member_user->hasRole(['Order Approver'])) {
                if (isset($email_sent_to_users[$member_user->id])) {
                    continue;
                }

                $email_sent_to_users[$member_user->id] = $member_user;
                $data['name'] = $member_user->firstName;
                $data['subject'] =  '#'.$currentOrder->id. ' ' .'Order status updated';
                $data['email'] = $member_user->email;
                MailHelper::sendMailNotification('emails.user-order-received', $data);
            }
        }
        
        return response()->json(['success' => true , 'message' => 'Order status updated successfully.']);
    }
}

