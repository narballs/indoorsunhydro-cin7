<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderComment;
use App\Models\Product;
use App\Models\ApiOrder;
use App\Models\ApiOrderItem;
use App\Models\Contact;
use App\Models\Cart;
use App\Models\State;
use App\Models\PaymentMethod;
use App\Models\UsState;
use App\Models\TaxClass;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Helpers\MailHelper;
use Stripe\Event;
use Stripe\StripeObject;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\OperationalZipCode;
use App\Helpers\SettingHelper;
use App\Helpers\UserHelper;
use App\Models\AdminSetting;
use App\Models\CustomerDiscount;
use App\Models\CustomerDiscountUses;
use App\Models\Discount;
use App\Models\ProductOption;
use App\Models\SelectedShippingQuote;
use App\Models\ShippingQuote;
use App\Models\SurchargeSetting;
use App\Models\UsCity;
use App\Models\UserLog;
use JeroenNoten\LaravelAdminLte\View\Components\Form\Select;
use PSpell\Config;

class CheckoutController extends Controller
{
    
    public function index(Request $request) {
       $new_checkout = AdminSetting::where('option_name', 'new_checkout_flow')->first();
        if (!empty($new_checkout) && strtolower($new_checkout->option_value) == 'yes') {
            return $this->new_checkout($request);
        } else {
           return $this->old_checkout($request);
        }
    }
    
    public function old_checkout(Request $request)
    {
        $user_id = auth()->user()->id;
        $selected_company = Session::get('company');
        if (!$selected_company) {
            Session::flash('message', "Please select a company for which you want to make an order for");
            return redirect('/cart/');
        }
        $contact = Contact::where('user_id', $user_id)
            ->where('status', 1)
            ->where('company', $selected_company)
            ->with('states')
            ->with('cities')
            ->first();
        // $cart_items = session()->get('cart');
        $cart_items = UserHelper::switch_price_tier($request);
        $cart_total = 0;
        foreach ($cart_items as $cart_item) {
            $row_price = $cart_item['quantity'] * $cart_item['price'];
            $cart_total = $row_price + $cart_total;
        }
        
        $products_weight = 0;
        foreach ($cart_items as $cart_item) {
            $product_options = ProductOption::where('product_id', $cart_item['product_id'])->where('option_id' , $cart_item['option_id'])->get();
            foreach ($product_options as $product_option) {
                $products_weight += $product_option->optionWeight * $cart_item['quantity'];
            }
        }
        if ($contact) {
            $isApproved = $contact->contact_id;
        }

        $zip_code_is_valid = true;

        if (Auth::check() && (!empty($contact->contact_id) || !empty($contact->secondary_id)) && $contact->status == 1) {
            // $tax_class = TaxClass::where('is_default', 1)->first();
            $user_address = null;
            $states = UsState::all();
            $payment_methods = PaymentMethod::with('options')->get();
            $contact_id = session()->get('contact_id');

            $user = User::where('id', $user_id)->first();
            $all_ids = UserHelper::getAllMemberIds($user);
            $pluck_default_user = Contact::whereIn('id', $all_ids)->where('contact_id' , $contact_id)->first();
            
            if (!empty($contact->contact_id)) {
                $user_address = Contact::where('user_id', $user_id)->where('contact_id' , $contact->contact_id)->first();
            } else {
                if (!empty($contact->secondary_id)) {
                    $parent = Contact::where('secondary_id', $contact->secondary_id)->first();
                    $user_address = Contact::where('contact_id', $parent->parent_id)->first();
                }
            }
            if (empty($user_address) && ($user_address->postalAddress1 == null  && $user_address->postalPostCode == null)) {
                return redirect()->back()->with('address_message', "Please contact support to update your billing address" );
            }

            $charge_shipment_fee = false;
            if (!empty($user_address) && $user_address->charge_shipping == 1) {
                $charge_shipment_fee = true;
            }

            $tax_class = TaxClass::where('name', $user_address->tax_class)->first();
            $tax_class_none = TaxClass::where('name', 'none')->first();
            
            $matchZipCode = null;
            if (empty($user_address) && ($user_address->postalPostCode != null || $user_address->postCode != null)) {
                $matchZipCode = OperationalZipCode::where('status' , 'active')->where('zip_code', $user_address->postalPostCode)->orWhere('zip_code' , $user_address->postCode)->first();
            }
            
            $check_zip_code_setting = AdminSetting::where('option_name', 'check_zipcode')->where('option_value' , 'Yes')->first();

            if (!empty($check_zip_code_setting) && strtolower($check_zip_code_setting->option_value) == 'yes') {
                $zip_code_is_valid = false;
                $operational_zip_code = OperationalZipCode::where('status' , 'active')
                    ->where('zip_code', $user_address->postalPostCode)
                    ->orWhere('zip_code' , $user_address->postCode)
                    ->first();
                if (!empty($operational_zip_code)) {
                    $zip_code_is_valid = true;
                }
            }

            
            // adding shipment rates
            if ($charge_shipment_fee == true) {
                $client = new \GuzzleHttp\Client();
                $ship_station_host_url = config('services.shipstation.host_url');
                $ship_station_api_key = config('services.shipstation.key');
                $ship_station_api_secret = config('services.shipstation.secret');
                $carrier_code = AdminSetting::where('option_name', 'shipping_carrier_code')->first();
                $service_code = AdminSetting::where('option_name', 'shipping_service_code')->first();
                $carrier_code_2 = AdminSetting::where('option_name', 'shipping_carrier_code_2')->first();
                $service_code_2 = AdminSetting::where('option_name', 'shipping_service_code_2')->first();

                if ($products_weight > 150) {
                    $carrier_code = $carrier_code_2->option_value;
                    $service_code = $service_code_2->option_value;
                } else {
                    $carrier_code = $carrier_code->option_value;
                    $service_code = $service_code->option_value;
                }

                $data = [
                    'carrierCode' => $carrier_code ,
                    'serviceCode' => $service_code ,
                    'fromPostalCode' => '95826',
                    'toCountry' => 'US',
                    'toPostalCode' => $user_address->postCode ? $user_address->postCode : $user_address->postalPostCode,
                    'weight' => [
                        'value' => $products_weight,
                        'units' => 'pounds'
                    ],
                ];
                
                $headers = [
                    'Authorization' => 'Basic ' . base64_encode($ship_station_api_key . ':' . $ship_station_api_secret),
                    'Content-Type' => 'application/json',
                ];
                $responseBody = null;
                try {
                    $response = $client->post($ship_station_host_url, [
                        'headers' => $headers,
                        'json' => $data,
                    ]);

                    $statusCode = $response->getStatusCode();
                    $responseBody = $response->getBody()->getContents();
                } catch (\Exception $e) {
                    $e->getMessage();
                }

                $shipment_price = 0;
                if ($responseBody != null) {
                    $shipping_response = json_decode($responseBody);
                    foreach ($shipping_response as $shipping_response) {
                        $shipment_price = $shipping_response->shipmentCost;
                    } 
                }
            } else {
                $shipment_price = 0;
            }
            return view('checkout/index2', compact(
                'user_address',
                'states',
                'payment_methods',
                'tax_class',
                'contact_id',
                'tax_class_none',
                'matchZipCode',
                'zip_code_is_valid',
                'check_zip_code_setting',
                'shipment_price',
                'cart_items'
            ));
        } else {
            return redirect()->back()->with('message', 'Your account is disabled. You can not proceed with checkout. Please contact us.');
        }
    }

    
    public function new_checkout(Request $request)
    {
        $states = UsState::all();
        $cart_items = UserHelper::switch_price_tier($request);
        $cart_total = 0;
        $charge_shipment_to_customer = 0;
        if (!empty($cart_items)) {
            foreach ($cart_items as $cart_item) {
                $row_price = $cart_item['quantity'] * $cart_item['price'];
                $cart_total = $row_price + $cart_total;
            }
        }
        
        if (!auth()->user()) {
            $tax_class = TaxClass::where('is_default', 1)->first();
            $shipment_price = 0;
            return view ('checkout.checkout_without_login' ,compact('states','cart_total' , 'cart_items' , 'tax_class' , 'shipment_price'));
        }
        $user_id = auth()->user()->id;
        $selected_company = Session::get('company');
        if (!$selected_company) {
            Session::flash('message', "Please select a company for which you want to make an order for");
            return redirect('/cart');
        }
        $contact = Contact::where('user_id', $user_id)
            ->where('status', 1)
            ->where('company', $selected_company)
            ->with('states')
            ->with('cities')
            ->first();
        // $cart_items = session()->get('cart');
        $current_date = Carbon::now()->format('Y-m-d');
        
        $products_weight = 0;
        $sub_total_of_cart = 0;
        foreach ($cart_items as $cart_item) {
            $sub_total_of_cart += $cart_item['quantity'] * $cart_item['price'];
            $product_options = ProductOption::where('product_id', $cart_item['product_id'])->where('option_id' , $cart_item['option_id'])->get();
            foreach ($product_options as $product_option) {
                $products_weight += $product_option->optionWeight * $cart_item['quantity'];
            }
        }
        if ($contact) {
            $isApproved = $contact->contact_id;
        }

        $zip_code_is_valid = true;

        if (Auth::check() && (!empty($contact->contact_id) || !empty($contact->secondary_id)) && $contact->status == 1) {
            // $tax_class = TaxClass::where('is_default', 1)->first();
            $shipment_prices = [];
            $user_address = null;
            $payment_methods = PaymentMethod::with('options')->get();
            $contact_id = session()->get('contact_id');

            $user = User::where('id', $user_id)->first();
            $all_ids = UserHelper::getAllMemberIds($user);
            $pluck_default_user = Contact::whereIn('id', $all_ids)->where('contact_id' , $contact_id)->first();

            $shipping_quotes = ShippingQuote::with('selected_shipping_quote')->get();
            $selected_shipment_quotes = SelectedShippingQuote::with('shipping_quote')->get();
            $admin_area_for_shipping = AdminSetting::where('option_name', 'admin_area_for_shipping')->first();
            $surcharge_settings = SurchargeSetting::where('apply_surcharge', 1)->first(); 
            if (!empty($contact->contact_id)) {
                $user_address = Contact::where('user_id', $user_id)->where('contact_id' , $contact->contact_id)->first();
            } else {
                if (!empty($contact->secondary_id)) {
                    $parent = Contact::where('secondary_id', $contact->secondary_id)->first();
                    $user_address = Contact::where('contact_id', $parent->parent_id)->first();
                }
            }
            if (empty($user_address) && ($user_address->postalAddress1 == null  && $user_address->postalPostCode == null)) {
                return redirect()->back()->with('address_message', "Please contact support to update your billing address" );
            }

            $charge_shipment_fee = false;
            if (!empty($user_address) && $user_address->charge_shipping == 1) {
                $charge_shipment_fee = true;
                $charge_shipment_to_customer = 1;
            }

            $tax_class = TaxClass::where('name', $user_address->tax_class)->first();
            $tax_class_none = TaxClass::where('name', 'none')->first();
            $get_tax_rate = 0;  
            if (!empty($tax_class)) {
                $get_tax_rate = $tax_class->rate;
            }
            $matchZipCode = null;
            if (empty($user_address) && ($user_address->postalPostCode != null || $user_address->postCode != null)) {
                $matchZipCode = OperationalZipCode::where('status' , 'active')->where('zip_code', $user_address->postalPostCode)->orWhere('zip_code' , $user_address->postCode)->first();
            }
            
            $check_zip_code_setting = AdminSetting::where('option_name', 'check_zipcode')->where('option_value' , 'Yes')->first();

            if (!empty($check_zip_code_setting) && strtolower($check_zip_code_setting->option_value) == 'yes') {
                $zip_code_is_valid = false;
                $operational_zip_code = OperationalZipCode::where('status' , 'active')
                    ->where('zip_code', $user_address->postalPostCode)
                    ->orWhere('zip_code' , $user_address->postCode)
                    ->first();
                if (!empty($operational_zip_code)) {
                    $zip_code_is_valid = true;
                }
            }
            $shipment_price = 0;
            $shipping_free_over_1000 = 0;
            $sub_total_of_cart = $sub_total_of_cart + ($sub_total_of_cart * $get_tax_rate / 100);
            $free_shipping_state = AdminSetting::where('option_name', 'free_shipping_state')->first();
            if (!empty($free_shipping_state)) {
                if ($free_shipping_state->option_value == $user_address->state || $user_address->state == 'CA') {
                    if ($sub_total_of_cart >= 1000) {
                        $shipping_free_over_1000 = 1;
                    } else {
                        $shipping_free_over_1000 = 0;
                    }
                } else {
                    $shipping_free_over_1000 = 0;
                }
            } else {
                $shipping_free_over_1000 = 0;
            }

            $admin_selected_shipping_quote = [];
            $shipstation_shipment_prices = [];
            $surcharge_value = 0;
            $shipping_carrier_code = null;
            $shipping_service_code = null;
            $carrier_code = AdminSetting::where('option_name', 'shipping_carrier_code')->first();
            $service_code = AdminSetting::where('option_name', 'shipping_service_code')->first();
            $carrier_code_2 = AdminSetting::where('option_name', 'shipping_carrier_code_2')->first();
            $service_code_2 = AdminSetting::where('option_name', 'shipping_service_code_2')->first();   
            // adding shipment rates
            if ($charge_shipment_fee == true) {
                if ($shipping_free_over_1000 == 1) {
                    $shipment_price = 0;
                } 
                else {
                    if (!empty($admin_area_for_shipping) && strtolower($admin_area_for_shipping->option_value) == 'yes') {
                        if ($products_weight > 150) {
                            $shipping_carrier_code = $carrier_code_2->option_value;
                            $shipping_service_code = $service_code_2->option_value;
                        }
                        else {
                            $shipping_carrier_code = null;
                            $shipping_service_code = null;
                        }
                        $get_shipping_rates = $this->get_shipping_rate($products_weight, $user_address , $selected_shipment_quotes ,$shipping_quotes, $shipment_prices, $shipment_price);
                        $shipment_price = $get_shipping_rates['shipment_price'];
                        $shipping_carrier_code = $get_shipping_rates['shipping_carrier_code'];
                        // if (!empty($surcharge_settings) && $surcharge_settings->surcharge_type === 'fixed') {
                        //     $surcharge_value = $surcharge_settings->surcharge_value;
                        // } else {
                        //     $surcharge_value = $surcharge_settings->surcharge_value * $get_shipping_rates['shipment_price'] / 100;
                        // }
                        // $shipment_price = $get_shipping_rates['shipment_price'] + $surcharge_value;
                        $shipstation_shipment_prices = $get_shipping_rates['shipment_prices'];
                        if (count($selected_shipment_quotes) > 0) {
                            foreach ($selected_shipment_quotes as $selected_shipment_quote) {
                                if (!empty($selected_shipment_quote->shipping_quote)) {
                                    if (!empty($shipstation_shipment_prices)) {
                                        foreach ($shipstation_shipment_prices as $shipstation_shipment_price) {
                                            if ($shipstation_shipment_price->serviceCode == ($selected_shipment_quote->shipping_quote->service_code)) {
                                                array_push($admin_selected_shipping_quote, $shipstation_shipment_price);
                                            }
                                        }
                                    
                                    }
                                }
                                
                            }
                        }
                    }
                    else {
                        $client = new \GuzzleHttp\Client();
                        $ship_station_host_url = config('services.shipstation.host_url');
                        $ship_station_api_key = config('services.shipstation.key');
                        $ship_station_api_secret = config('services.shipstation.secret');
                        
                        $shipping_package = AdminSetting::where('option_name', 'shipping_package')->first();
                        if ($products_weight > 150) {
                            $carrier_code = $carrier_code_2->option_value;
                            $service_code = $service_code_2->option_value;
                        } else {
                            $carrier_code = $carrier_code->option_value;
                            $service_code = $service_code->option_value;
                        }
    
                        $shipping_carrier_code = $carrier_code;
                        $shipping_service_code = $service_code;
    
                        $data = [
                            'carrierCode' => $carrier_code ,
                            'serviceCode' => $service_code ,
                            'fromPostalCode' => '95826',
                            'toCountry' => 'US',
                            'toPostalCode' => $user_address->postCode ? $user_address->postCode : $user_address->postalPostCode,
                            'weight' => [
                                'value' => $products_weight,
                                'units' => 'pounds'
                            ],
                        ];
                        
                        $headers = [
                            'Authorization' => 'Basic ' . base64_encode($ship_station_api_key . ':' . $ship_station_api_secret),
                            'Content-Type' => 'application/json',
                        ];
                        $responseBody = null;
                        try {
                            $response = $client->post($ship_station_host_url, [
                                'headers' => $headers,
                                'json' => $data,
                            ]);
    
                            $statusCode = $response->getStatusCode();
                            $responseBody = $response->getBody()->getContents();
                        } catch (\Exception $e) {
                            $e->getMessage();
                        }
    
                        
                        if ($responseBody != null) {
                            $shipping_response = json_decode($responseBody);
                            
                            foreach ($shipping_response as $shipping_response) {
                                $shipment_price = $shipping_response->shipmentCost + $shipping_response->otherCost;
                            } 
                        }
                    }
                }
            } 
            else {
                $shipment_price = 0;
            }
            $enable_discount_setting = AdminSetting::where('option_name', 'enable_discount')->first();
            if (!empty($enable_discount_setting) && strtolower($enable_discount_setting->option_value) == 'yes') {
                $discount_code = Discount::where('end_date', '>=', $current_date)->where('status', 1)->first();
                if (!empty($discount_code) ) {
                    $customer_discount_uses = CustomerDiscountUses::where('contact_id', $contact_id)->where('discount_id', $discount_code->id)->count();
                    $max_usage_count = CustomerDiscountUses::where('discount_id', $discount_code->id)->count();
                    if (strtolower($discount_code->max_discount_uses) === 'limit for user') {
                        if (!empty($customer_discount_uses)) {
                            if ($customer_discount_uses >= intval($discount_code->limit_per_user)) {
                                $discount_code = null;
                            }
                        } else {
                            $discount_code = $discount_code;
                        }
                    }  
                    elseif(strtolower($discount_code->max_discount_uses) === 'limit max times') {
                        if (!empty($max_usage_count)) {
                            $usage_count = !empty($discount_code->usage_count) ? $discount_code->usage_count : 0;
                            if ($usage_count >= $discount_code->max_usage_count) {
                                $discount_code = null;
                            }
                        } else {
                            $discount_code = $discount_code;
                        }
                        
                    } 
                    
                } else {
                    $discount_code = null;
                }
            } else {
                $discount_code = null;
            }
            return view('checkout/checkout_for_login', compact(
                'user_address',
                'states',
                'payment_methods',
                'tax_class',
                'contact_id',
                'tax_class_none',
                'matchZipCode',
                'zip_code_is_valid',
                'check_zip_code_setting',
                'shipment_price',
                'cart_items',
                'discount_code',
                'enable_discount_setting',
                'admin_area_for_shipping', 
                'shipment_prices' , 
                'products_weight',
                'shipping_quotes' , 
                'admin_selected_shipping_quote','surcharge_settings',
                'shipping_carrier_code' , 'shipping_service_code', 'shipstation_shipment_prices' , 'charge_shipment_to_customer', 'shipping_free_over_1000'
                
            ));
        } else {
            return redirect()->back()->with('message', 'Your account is disabled. You can not proceed with checkout. Please contact us.');
        }
    }

    public function thankyou(Request $request , $id)
    {
        $user_id = Auth::id();
        $order = ApiOrder::where('id', $id)
            ->with(
                'user.contact',
                'apiOrderItem.product.options',
                'texClasses',
                'discount',
            )->first();
        $user = User::where('id', $user_id)->first();
        $all_ids = UserHelper::getAllMemberIds($user);
        $order_contact_query = Contact::whereIn('id', $all_ids)->first();
        // dd($order_contact_query);
        // if (!empty($order_contact_query)) {
        //     $order_contact = Contact::where('id', $order_contact_query->id)->first();
        // } else {
        //     $order_contact = Contact::where('contact_id', $order->memberId)->first();
        // }
        
        $order_contact = Contact::where('contact_id', $order->memberId)->first();
        if (empty($order_contact) && $order_contact->is_parent == 0) {
            $order_contact = Contact::where('contact_id', $order_contact->parent_id)->first();
        }
         
        $createdDate = $order->created_at;
        $formatedDate = $createdDate->format('F  j, Y h:i:s A');
        $orderitems = ApiOrderItem::where('order_id', $id)->with('product')->get();
        $count = $orderitems->count();
        $best_products = Product::where('status', '!=', 'Inactive')->orderBy('views', 'DESC')->limit(4)->get();
        
        Cart::where('user_id', $user_id)->where('is_active', 1)->delete();

        Session::forget('cart');
        Session::forget('cart_hash');

        $contact = Contact::where('user_id', $user_id)->first();

        $pricing = $contact->priceColumn;
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
        $tax_class = TaxClass::where('name', $order_contact->tax_class)->first();
        $discount_amount = $order->discount_amount;
        if (isset($discount_variation_value) && !empty($discount_variation_value) && $discount_amount > 0) {
            $discount_variation_value = $discount_variation_value;
            if (!empty($tax_class)) {
                $tax_rate = $tax_class->rate;
                $tax_without_discount = $subtotal * ($tax_rate / 100);
                if (!empty($discount_variation) && $discount_variation == 'percentage') {
                    $tax = $tax_without_discount - ($tax_without_discount * ($discount_variation_value / 100));
                } else {
                    $tax = $tax_without_discount > $discount_variation_value ?  $tax_without_discount - $discount_variation_value : 0;
                }
            }

        } else {
            if (!empty($tax_class)) {
                $tax_rate = $tax_class->rate;
                $tax = $subtotal * ($tax_rate / 100);
            }
        } 
        return view(
            'checkout/order-received',
            compact(
                'order',
                'orderitems',
                'order_contact',
                'formatedDate',
                'count',
                'best_products',
                'pricing',
                'tax'
            )
        );
    }
    public function webhook(Request $request) {
        $payload = $request->getContent();
        $stripeSignature = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');
        $charge_id = null;
        try {
            $event = Webhook::constructEvent($payload, $stripeSignature, $webhookSecret);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Invalid webhook signature'], 400);
        }

        switch ($event->type) {
            case 'charge.succeeded':
                $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
                $payment_succeeded = $stripe->events->retrieve(
                    $event->id,
                    []
                );
                $charge = $event->data->object;
                $chargeId = $charge->id;
                $dateCreated = Carbon::now();
                $createdDate = Carbon::now();
                $session_contact_id = Session::get('contact_id');
                $active_contact_id = null;
                $is_primary = null;
                if (!empty($session_contact_id)) {
                    $contact = Contact::where('contact_id', $session_contact_id)->first();
                    if ($contact) {
                        $active_contact_id = $contact->contact_id;
                    } else {
                        $contact = Contact::where('secondary_id', $session_contact_id)->first();
                        $active_contact_id = $contact->parent_id;
                    }
                }
                if ($active_contact_id) {
                    $is_primary = Contact::where('contact_id', $session_contact_id)->first();
                }
                $order_id = $payment_succeeded->data->object->metadata->order_id;
                $currentOrder = ApiOrder::where('id', $order_id)->with(
                        'user.contact',
                        'apiOrderItem.product.options',
                        'texClasses'
                    )->first();
                
                if(!empty($currentOrder)) {
                    if ($payment_succeeded->data->object->paid == true) {
                        $currentOrder->payment_status = 'paid';
                        $currentOrder->charge_id = $chargeId;
                        $currentOrder->save();
    
                        $order_comment = new OrderComment;
                        $order_comment->order_id = $order_id;
                        $order_comment->comment = 'Order marked as paid through webhook. (charge.succeeded)';
                        $order_comment->save();

                        
    
                    } else {
                        $currentOrder->payment_status = 'unpaid';
                        $currentOrder->charge_id = $chargeId;
                        $currentOrder->save();
    
    
                        $order_comment = new OrderComment;
                        $order_comment->order_id = $order_id;
                        $order_comment->comment = 'Order marked as unpaid through webhook, unable to verify payment. Although (charge.succeeded).';
                        $order_comment->save();
                    }
    
                  
                    $order_items = ApiOrderItem::with('order.texClasses', 'product.options')
                    ->where('order_id', $order_id)
                    ->get();
                    
                    $check_shipstation_create_order_status = AdminSetting::where('option_name', 'create_order_in_shipstation')->first();
                    if (!empty($check_shipstation_create_order_status) && strtolower($check_shipstation_create_order_status->option_value) == 'yes') {
                        $order_contact = Contact::where('contact_id', $currentOrder->memberId)->orWhere('parent_id' , $currentOrder->memberId)->first();
                        if (!empty($order_contact)) {
                            // UserHelper::shipping_order($order_id , $currentOrder , $order_contact);
                            $shiping_order = UserHelper::shipping_order($order_id , $currentOrder , $order_contact);
                            if ($shiping_order['statusCode'] == 200) {
                                $orderUpdate = ApiOrder::where('id', $order_id)->update([
                                    'shipstation_orderId' => $shiping_order['responseBody']->orderId,
                                ]);
                            }
                        }
                    }
                    $customer_email = Contact::where('contact_id', $currentOrder->memberId)->first();
                    // $customer_email  = $payment_succeeded->data->object->billing_details->email;
                    if (!empty($customer_email)) {
                        $contact = Contact::where('email', $customer_email->email)->first();
                    }
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
                            'postalAddress1' =>$contact->postalAddress1,
                            'postalAddress2' =>$contact->postalAddress2,
                            'postalCity' =>$contact->postalCity,
                            'postalState' =>$contact->postalState,
                            'postalPostCode' =>$contact->postalPostCode,
                        ],
                        'payment_terms' =>  'Stripe',
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
                    $template = 'emails.admin-order-received';
                    $admin_users = DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');
    
                    $admin_users = $admin_users->toArray();
    
                    $users_with_role_admin = User::select("email")
                        ->whereIn('id', $admin_users)
                        ->get();
                    $parent_email = Contact::where('contact_id', $active_contact_id)->first();
                    $data = [
                        'name' =>  $name,
                        'email' => $email,
                        'subject' => 'New order received',
                        'reference' => $reference,
                        'order_items' => $order_items,
                        'dateCreated' => $dateCreated,
                        'addresses' => $addresses,
                        'best_product' => $best_products,
                        'currentOrder' => $currentOrder,
                        'user_email' => $user_email,
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
    
                    if (!empty($customer_email->email)) {
                        $data['email'] = $customer_email->email;
                        $data['subject'] = 'Your order has been received';
                        MailHelper::sendMailNotification('emails.admin-order-received', $data);
                    }
                }
            break;
            case 'charge.failed':
                $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
                $payment_failed = $stripe->events->retrieve(
                    $event->id,
                    []
                );
                $order_id = $payment_failed->data->object->metadata->order_id;
                $currentOrder = ApiOrder::where('id', $order_id)->with(
                    'user.contact',
                    'apiOrderItem.product.options',
                    'texClasses'
                )->first();
                if (!empty($currentOrder)) {
                    if ($payment_failed->data->object->paid != true) {
                        $currentOrder->payment_status =  'unpaid';
                        $currentOrder->charge_id = null;
                        $currentOrder->save();
                    }
                    
                    $order_comment = new OrderComment;
                    $order_comment->order_id = $order_id;
                    $order_comment->comment = 'Order marked as unpaid through webhook, unable to verify payment (charge.failed).';
                    $order_comment->save();
                }


            break;
            // Add more cases for other event types you want to handle
        }
        
        return response()->json(['status' => 'success']);
    }

    public function check_existing_email(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!empty($user)) {
            return response()->json(['status' => 'success', 'user_status' => 'Existed' , 'message' => 'Please enter your password to continue.']);
        } else {
            return response()->json(['status' => 'error', 'user_status' => 'Not Exists' , 'message' => 'Please enter your complete details to continue.']);
        }
    }

    public function authenticate_user(Request $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');
        
        $user = User::where('email', $request->email)->first();
        $main_contact = Contact::where('email', $request->email)->first();
        $email_user = session::put('user', $user);
        $cart = [];
        $access = false;
        $message = '';
        $admin = false; 
        $already_in_cin7 = false; 
        $registration_status = false;
        $auto_approved = false;
        $content = null;
        if (auth()->attempt($credentials)) {
            if (auth()->user()->allow_access == 0) {
                Session::flush();
                Auth::logout();
                $access = false;
                $message = 'Your account has been disabled.';
                // session()->flash('message', 'Your account has been disabled.');
                // return redirect()->back();
            } else {
                $user_id = auth()->user()->id;
                if ($request->session()->has('cart_hash')) {
                    $cart_hash = $request->session()->get('cart_hash');
                    $cart_items = Cart::where('cart_hash', $cart_hash)->where('is_active', 1)->where('user_id', 0)->get();
                    foreach ($cart_items as $cart_item) {
                        $cart_item->user_id = $user_id;
                        $cart_item->save();
                    }
                }
                if ($user->hasRole(['Admin'])) {
                    session()->flash('message', 'Successfully Logged in');
                    $companies = Contact::where('user_id', auth()->user()->id)->get();
                    if ($companies->count() == 1) {
                        
                        if ($companies[0]->contact_id == null) {
                            UserHelper::switch_company($companies[0]->secondary_id);
                        } else {
                            UserHelper::switch_company($companies[0]->contact_id);
                        }
                    }
                    Session::put('companies', $companies);
                    $admin = true;
                } else {
                    $companies = Contact::where('user_id', auth()->user()->id)->get();
                    if ($companies->count() == 1) {
                        if ($companies[0]->contact_id == null) {
                            UserHelper::switch_company($companies[0]->secondary_id);
                        } else {
                            UserHelper::switch_company($companies[0]->contact_id);
                        }
                    }
                    if ($companies->count() == 2) {
                        foreach ($companies as $company) {
                            if ($company->status == 1) {
                                if ($company->contact_id == null) {
                                    UserHelper::switch_company($company->secondary_id);
                                } else {
                                    UserHelper::switch_company($company->contact_id);
                                }
                            }
                        }
                    }
                    Session::put('companies', $companies);
                    // if (!empty(session()->get('cart'))) {
                    //     return redirect()->route('cart');
                    // } else {
                        if ($user->is_updated == 1) {

                            $companies = Contact::where('user_id', auth()->user()->id)->get();

                            if ($companies[0]->contact_id == null) {
                                UserHelper::switch_company($companies[0]->secondary_id);
                            } else {
                                UserHelper::switch_company($companies[0]->contact_id);
                            }
                            // Session::put('companies', $companies);
                            $previousUrl = session('previous_url', '/'); 
                            // return redirect()->intended($previousUrl);
                            // return redirect()->route('my_account');
                        } else {
                            $companies = Contact::where('user_id', auth()->user()->id)->get();
                            // Session::put('companies', $companies);
                            if ($companies[0]->contact_id == null) {
                                UserHelper::switch_company($companies[0]->secondary_id);
                            } else {
                                UserHelper::switch_company($companies[0]->contact_id);
                            }
                            // return redirect('/');
                        }
                        $admin = false;
                    // }
                }
                $message = 'Successfully Logged in';
                $access = true;
                $auto_approved = !empty($main_contact) && $main_contact->status == 1 ? true : false;
            }
            return response()->json(['status' => 'success', 'message' => $message, 'access' => $access , 'is_admin' , $admin , 'auto_approved' => $auto_approved]);
            
        } 
        else {
            if (!empty($user)) {
                $message = 'Invalid credentials';
                return response()->json(['status' => 'error', 'message' => $message, 'access' => $access]);
            } else {
                
                if (!empty($request->different_shipping_address) && $request->different_shipping_address == 1) {
                    $request->validate([
                        'email' => 'required|email',
                        'password' => 'required',
                        'first_name' => 'required',
                        'company' => 'required',
                        'address' => 'required',
                        // 'city' => 'required',
                        'state' => 'required',
                        'zip_code' => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
                        'phone' => 'required',
                        'postal_address1' => 'required',
                        'postal_state' => 'required',
                        'postal_zip_code' => 'required',
                    ]);
                } else {
                    $request->validate([
                        'email' => 'required|email',
                        'password' => 'required',
                        'first_name' => 'required',
                        'company' => 'required',
                        'address' => 'required',
                        // 'city' => 'required',
                        'state' => 'required',
                        'zip_code' => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
                        'phone' => 'required',
                    ]);
                }

                
                $states = UsState::where('id', $request->state)->first();
                $state_name = $states->state_name;
                $toggle_registration = AdminSetting::where('option_name', 'toggle_registration_approval')->first();
                $first_name = $request->first_name;
                $last_name = $request->last_name;
                $company = $request->company;
                $different_shipping = $request->different_shipping_address;
                $address1 = $request->address;
                $address2 = $request->address_2;
                $city = $request->city;
                $postCode = $request->zip_code;
                $phone = $request->phone;
                if (!empty($request->postal_state)) {
                    $postal_state = UsState::where('id', $request->postal_state)->first();
                    $postal_state_name = $postal_state->state_name;
                } else {
                    $postal_state_name = '';
                }
                if ($different_shipping == 1) {
                    $postalAddress1 = $request->postal_address1;
                    $postalAddress2 = $request->postal_address2;
                    $postalCity = $request->postal_city;
                    $postalState = $postal_state_name;
                    $postalPostCode = $request->postal_zip_code;
                } else {
                    $postalAddress1 = $address1;
                    $postalAddress2 =$address2;
                    $postalCity = $city;
                    $postalState = $state_name;
                    $postalPostCode =$postCode;
                }
                try {
                    $price_column = null;
                    $default_price_column = AdminSetting::where('option_name', 'default_price_column')->first();
                    if (!empty($default_price_column)) {
                        $price_column = ucfirst($default_price_column->option_value);
                    }
                    else {
                        $price_column = 'RetailUSD';
                    }

                    $user = User::create([
                        'email' => strtolower($request->get('email')),
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "password" => bcrypt($request->get('password'))
                    ]);
                    $user_id = $user->id;

                    $contact = new Contact([
                        // 'website' => $request->input('company_website'),
                        'company' => $company,
                        'phone' => $phone,
                        'status' => !empty($toggle_registration) && strtolower($toggle_registration->option_value) == 'yes' ? 1 : 0,
                        'priceColumn' => $price_column,
                        'user_id' => $user_id,
                        'firstName' => $user->first_name,
                        'type' => 'Customer',
                        'lastName' => $user->last_name,
                        'email' => $user->email,
                        'is_parent' => 1,
                        'tax_class' => strtolower($state_name) == strtolower('California') ? '8.75%' : 'Out of State',
                        'paymentTerms' => 'Pay in Advanced',
                        'charge_shipping' => 1,
                        'address1' => $address1,
                        'address2' => $address2,
                        'city' => $city,
                        'state' => $state_name,
                        'postCode' => $postCode,
                        'postalAddress1' => $postalAddress1,
                        'postalAddress2' => $postalAddress2,
                        'postalCity' => $postalCity,
                        'postalState' => $postalState,
                        'postalPostCode' => $postalPostCode,                                
                    ]);
                    
                    if (!empty($toggle_registration) && strtolower($toggle_registration->option_value) == 'yes') {
                        $api_contact = $contact->toArray();
                        $client = new \GuzzleHttp\Client();
                        $url = "https://api.cin7.com/api/v1/Contacts/";
                        $response = $client->post($url, [
                            'headers' => ['Content-type' => 'application/json'],
                            'auth' => [
                                SettingHelper::getSetting('cin7_auth_username'),
                                SettingHelper::getSetting('cin7_auth_password')
                            ],
                            'json' => [
                                $api_contact
                            ],
                        ]);
                        $response = json_decode($response->getBody()->getContents());
                        if ($response[0]->success == false) {
                            $message = 'User already exists in Cin7 . Please contact support.';
                            $registration_status = false;
                        }
                        else {
                            $contact->contact_id = $response[0]->id;
                            $contact->save();
                            $registration_status = true;
                            $created_contact = Contact::where('id', $contact->id)->first();
                            $admin_users =  DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');
                            $admin_users = $admin_users->toArray();

                            $users_with_role_admin = User::select("email")
                                ->whereIn('id', $admin_users)
                                ->get();

                            $user_log = UserLog::create([
                                'user_id' => $created_contact->user_id,
                                'action' => 'Signup',
                                'user_notes' => 'new contact created' . Carbon::now()->toDateTimeString()
                            ]);
                            $content = 'Your account has been created successfully and approved by admin.';
                            $auto_approved = true;
                            $message = $content;
                        }
                        
                    } else {
                        $contact->save();
                        $registration_status = true;
                        $created_contact = Contact::where('id', $contact->id)->first();
                        $admin_users =  DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');
                        $admin_users = $admin_users->toArray();

                        $users_with_role_admin = User::select("email")
                            ->whereIn('id', $admin_users)
                            ->get();

                        $user_log = UserLog::create([
                            'user_id' => $created_contact->user_id,
                            'action' => 'Signup',
                            'user_notes' => 'new contact created' . Carbon::now()->toDateTimeString()
                        ]);
                        $content = 'Your account registration request has been submitted. You will receive an email once your account has been approved.';
                        $auto_approved = false;
                        $message = $content;
                    }


                    $data = [
                        'user' => $user,
                        'subject' => 'New Register User',
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'content' => $content,
                        'email' => $user->email,
                        'subject' => 'Your account registration request ',
                        'from' => 'noreply@indoorsunhydro.com',
                    ];
                    $access = true;
                    if ($registration_status == true) {
                        if (!empty($users_with_role_admin)) {
                            foreach ($users_with_role_admin as $role_admin) {
                                $subject = 'New Register User';
                                $data['email'] = $role_admin->email;
                                MailHelper::sendMailNotification('emails.admin_notification', $data);
                            }
                        }

                        if (!empty($created_contact)) {
                            if ($auto_approved == true) {
                                $data['contact_name'] = $created_contact->firstName . ' ' . $created_contact->lastName;
                                $data['contact_email'] = $created_contact->email;
                                $data['content'] = $content;
                                $data['subject'] = $content;
                                MailHelper::sendMailNotification('emails.approval-notifications', $data);
                            } else {
                                $data['name'] = $created_contact->firstName . ' ' . $created_contact->lastName;
                                $data['email'] =  $created_contact->email;
                                $data['content'] = $content;
                                $data['subject'] = 'Your account registration request';
                                MailHelper::sendMailNotification('emails.user_registration_notification', $data);
                            }
                        }
    
                    }

                    if (!empty($created_contact)) {
                        $auth_user = Auth::loginUsingId($created_contact->user_id);
                        if ($request->session()->has('cart_hash')) {
                            $cart_hash = $request->session()->get('cart_hash');
                            $cart_items = Cart::where('cart_hash', $cart_hash)->where('is_active', 1)->where('user_id', 0)->get();
                            foreach ($cart_items as $cart_item) {
                                $cart_item->user_id = $user_id;
                                $cart_item->save();
                            }
                        }
                        $companies = Contact::where('user_id', $auth_user->id)->get();
                        if (count($companies) > 0 ) {
                            if ($companies[0]->contact_id == null) {
                                UserHelper::switch_company($companies[0]->secondary_id);
                            } else {
                                UserHelper::switch_company($companies[0]->contact_id);
                            }
                            Session::put('companies', $companies);
                        }
                    } else {
                        $access = false;
                        $message = 'Something went wrong. Please try again.';
                    }
                        

                    
                } catch (\Exception $e) {
                    // $message = $e->getMessage();
                    $message = 'Something went wrong. Please contact admin .';
                    $access = true;
                    $registration_status = false;
                }
            }
            return response()->json(['status' => 'error', 'message' => $message, 'access' => $access , 'registration_status' => $registration_status, 'auto_approved' => $auto_approved]);
        }   
    }

    public function apply_discount_code(Request $request) {
        $specific_customers = false;
        $eligible = false;
        $max_uses = false;
        $success = false;
        $message = '';
        $discount_per_user = false;
        $discount_max_times = false;
        $max_discount_uses_none = false;
        $coupen_code = $request->coupen_code;
        $contact_id = $request->contact_id;
        $discount_variation_value = 0;
        $discount_variation = '';
        $current_date = Carbon::now()->format('Y-m-d');

        $discount = Discount::where('discount_code', $coupen_code)
        ->where('end_date', '>=', $current_date)
        ->where('status', 1)
        ->first();
        
        if (!empty($discount)) {
            $success = true;
            $discount_variation = $discount->discount_variation;
            $discount_variation_value = $discount->discount_variation_value;
            $total_discount_uses = CustomerDiscountUses::where('discount_id', $discount->id)->count();
            if (strtolower($discount->customer_eligibility) == 'specific customers')  {
                $specific_customers = true;
                $customer_discount = CustomerDiscount::where('contact_id', $contact_id)->where('discount_id', $discount->id)->first();
                if (empty($customer_discount)) {
                    $eligible = false;
                    $message = 'You are not eligible for this discount';
                } else {
                    $eligible = true;
                    if (!empty($discount->limit_per_user) && strtolower($discount->max_discount_uses) == 'limit for user') {
                        $discount_per_user = true;
                        $customer_discount_count = CustomerDiscount::where('contact_id', $contact_id)->where('discount_id', $discount->id)->count();
                        if ($customer_discount_count > $discount->limit_per_user) {
                            $max_uses = false;
                            $message = 'You have reached the maximum usage of this discount';   
                        } else {
                            $max_uses = true;
                            $message = 'Discount applied successfully';
                        }
                    } elseif(!empty($discount->max_usage_count) && strtolower($discount->max_discount_uses) == 'limit max times') {
                        $discount_max_times = true;
                        $max_discont_count_in_total = $discount->usage_count == 'Null' ? 0 : $discount->usage_count;
                        if ($max_discont_count_in_total > $discount->max_usage_count) {
                            $max_uses = false;
                            $message = 'Discount has reached the maximum usage';   
                        } else {
                            $max_uses = true;
                            $message = 'Discount applied successfully';
                        }
                    } elseif(strtolower($discount->max_discount_uses) == 'none') {
                        $max_uses = false;
                        $discount_per_user = false;
                        $discount_max_times = false;
                        $max_discount_uses_none = true;
                        $message = 'Discount applied successfully';
                    }
                }
            } else {
                $specific_customers = false;
                $eligible = true;
                if (!empty($discount->limit_per_user) && strtolower($discount->max_discount_uses) == 'limit for user') {
                    $discount_per_user = true;
                    $customer_discount_count = CustomerDiscountUses::where('contact_id', $contact_id)->where('discount_id', $discount->id)->count();
                    if ($customer_discount_count > $discount->limit_per_user) {
                        $max_uses = false;
                        $message = 'You have reached the maximum usage of this discount';   
                    } else {
                        $max_uses = true;
                        $message = 'Discount applied successfully';
                    }
                } 
                elseif(!empty($discount->max_usage_count) && strtolower($discount->max_discount_uses) == 'limit max times') {
                    $discount_max_times = true;
                    $max_discont_count_in_total = empty($discount->usage_count) ? 0 : $discount->usage_count;
                    if ($max_discont_count_in_total >= $discount->max_usage_count) {
                        $max_uses = false;
                        $message = 'Discount has reached the maximum usage';   
                    } else {
                        $max_uses = true;
                        $message = 'Discount applied successfully';
                    }
                } 
                elseif(strtolower($discount->max_discount_uses) == 'none') {
                    $max_uses = false;
                    $discount_per_user = false;
                    $discount_max_times = false;
                    $max_discount_uses_none = true;
                    $message = 'Discount applied successfully';
                } 
            }
        } else {
            $success = false;
            $message = 'Invalid discount code';
        }

        return response()->json([
            'success' => $success,
            'specific_customers' => $specific_customers,
            'eligible' => $eligible,
            'max_uses' => $max_uses,
            'discount_per_user' => $discount_per_user,
            'discount_max_times' => $discount_max_times,
            'message' => $message,
            'discount_variation' => $discount_variation,
            'discount_variation_value' => $discount_variation_value,
            'max_discount_uses_none' => $max_discount_uses_none
        ]);
    }

    public function get_shipping_rate($products_weight, $user_address, $selected_shipment_quotes,$shipping_quotes,$shipment_prices ,$shipment_price) {
        $client = new \GuzzleHttp\Client();
        $ship_station_host_url = config('services.shipstation.host_url');
        $ship_station_api_key = config('services.shipstation.key');
        $ship_station_api_secret = config('services.shipstation.secret');
        $carrier_code_2 = AdminSetting::where('option_name', 'shipping_carrier_code_2')->first();
        $service_code_2 = AdminSetting::where('option_name', 'shipping_service_code_2')->first();
        $shipping_carrier_code = null;
    
        foreach ($shipping_quotes as $quote) {
            if (!empty($quote->selected_shipping_quote)) {
                $shipping_carrier_code = $quote->carrier_code;
                $data = [
                    'carrierCode' => $products_weight > 150 ? $carrier_code_2->option_value : $quote->carrier_code,
                    'serviceCode' => $products_weight > 150 ? $service_code_2->option_value : null,
                    'fromPostalCode' => '95826',
                    'toCountry' => 'US',
                    'toPostalCode' => $user_address->postCode ? $user_address->postCode : $user_address->postalPostCode,
                    'weight' => [
                        'value' => $products_weight,
                        'units' => 'pounds'
                    ],
                ];
        
                $headers = [
                    'Authorization' => 'Basic ' . base64_encode($ship_station_api_key . ':' . $ship_station_api_secret),
                    'Content-Type' => 'application/json',
                ];
        
                try {
                    $response = $client->post($ship_station_host_url, [
                        'headers' => $headers,
                        'json' => $data,
                    ]);
        
                    $statusCode = $response->getStatusCode();
                    $responseBody = $response->getBody()->getContents();
                    $shipping_response = json_decode($responseBody);
                    $shipment_prices[] = $shipping_response;
                    $shipment_price = $shipping_response->shipmentCost + $shipping_response->otherCost;
                } catch (\Exception $e) {
                    $e->getMessage();
                }
            } 
        }
        return [
            'shipment_prices' => !empty($shipment_prices) ? $shipment_prices[0] : null,
            'shipment_price' => $shipment_price,
            'shipping_carrier_code' => $products_weight > 150 ? $carrier_code_2->option_value : $shipping_carrier_code,    
        ];
    }

    public function refund_webhook(Request $request)
{
    $payload = $request->getContent();
    $stripeSignature = $request->header('Stripe-Signature');
    $webhookSecret = config('services.stripe.refund_webhook_secret');

    try {
        $event = Webhook::constructEvent($payload, $stripeSignature, $webhookSecret);
    } catch (\Stripe\Exception\SignatureVerificationException $e) {
        Log::error($e->getMessage());
        return response()->json(['error' => 'Invalid webhook signature'], 400);
    }

    switch ($event->type) {
        case 'charge.succeeded':
            // Handle charge succeeded event
            break;

        case 'charge.failed':
            // Handle charge failed event
            break;

        case 'charge.refunded':
            // Handle charge refunded event
            $charge = $event->data->object;
            $refundAmount = $charge->amount_refunded / 100; // Convert amount from cents to dollars
            $order_id = $charge->metadata->order_id;
            $currentOrder = ApiOrder::find($order_id);

            if (!empty($currentOrder)) {
                // Update order status or perform any other actions related to the refund
                $currentOrder->payment_status = 'refunded';
                $currentOrder->is_refunded = 1;
                $currentOrder->save();


                $order_comment = new OrderComment;
                $order_comment->order_id = $order_id;
                $order_comment->comment = 'Order marked as refunded through webhook. (charge.refunded)';
                $order_comment->save();

                // Log refund information or perform any other necessary actions
                Log::info('Refund processed for order ID: ' . $order_id . ', Amount: $' . $refundAmount);
            }

            break;

        // Add more cases for other event types you want to handle

        default:
            // Handle unknown event types
            break;
    }

    return response()->json(['status' => 'success']);
}

    
}
