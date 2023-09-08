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
use App\Models\ProductOption;
use PSpell\Config;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->id();
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
        $cart_items = session()->get('cart');
        $cart_total = 0;
        foreach ($cart_items as $cart_item) {
            $row_price = $cart_item['quantity'] * $cart_item['price'];
            $cart_total = $row_price + $cart_total;
        }
        
        $produts_weight = 0;
        foreach ($cart_items as $cart_item) {
            $product_options = ProductOption::where('product_id', $cart_item['product_id'])->where('option_id' , $cart_item['option_id'])->get();
            foreach ($product_options as $product_option) {
                $produts_weight += $product_option->optionWeight * $cart_item['quantity'];
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
            $pluck_default_user = Contact::whereIn('id', $all_ids)->where('is_default' , 1)->first();
            if (!empty($pluck_default_user)) {
                $user_address = Contact::where('id' ,$pluck_default_user->id)->first();
            } else {
                if ($contact->secondary_id) {
                    $parent_id = Contact::where('secondary_id', $contact->secondary_id)->first();
                    $user_address = Contact::where('user_id', $user_id)->where('secondary_id', $parent_id->secondary_id)->first();
                } else {
                    $user_address = Contact::where('user_id', $user_id)->where('contact_id', $contact_id)->orWhere('contact_id' , $contact->contact_id)->first();
                }
            }
            if (($user_address->postCode == null && $user_address->postalPostCode == null) || ($user_address->postalAddress1 == null && $user_address->address1 == null)) {
                return redirect()->back()->with('address_message', "Please update your address before proceeding to checkout" );
            }

            $tax_class = TaxClass::where('name', $user_address->tax_class)->first();
            $tax_class_none = TaxClass::where('name', 'none')->first();
            
            $matchZipCode = null;
            if ($user_address->postalPostCode != null || $user_address->postCode != null) {
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

            $client = new \GuzzleHttp\Client();
            $ship_station_host_url = config('services.shipstation.host_url');
            $ship_station_api_key = config('services.shipstation.key');
            $ship_station_api_secret = config('services.shipstation.secret');
            $carrier_code = AdminSetting::where('option_name', 'shipping_carrier_code')->first();
            $service_code = AdminSetting::where('option_name', 'shipping_service_code')->first();
            $data = [
                'carrierCode' => $carrier_code->option_value,
                'serviceCode' => $service_code->option_value,
                'fromPostalCode' => '95826',
                'toCountry' => 'US',
                'toPostalCode' => $user_address->postalPostCode ? $user_address->postalPostCode : $user_address->postCode,
                'weight' => [
                    'value' => $produts_weight,
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
                'shipment_price'
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
                'texClasses'
            )->first();
        
        $user = User::where('id', $user_id)->first();
        $all_ids = UserHelper::getAllMemberIds($user);
        $order_contact_query = Contact::whereIn('id', $all_ids)->where('is_default' , 1)->first();
        if (!empty($order_contact_query)) {
            $order_contact = Contact::where('id', $order_contact_query->id)->first();
        } else {
            $order_contact = Contact::where('contact_id', $order->memberId)->first();
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
        return view(
            'checkout/order-received',
            compact(
                'order',
                'orderitems',
                'order_contact',
                'formatedDate',
                'count',
                'best_products',
                'pricing'
            )
        );
    }
    public function webhook(Request $request) {
        $payload = $request->getContent();
        $stripeSignature = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');
        
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
                if($active_contact_id) {
                    $is_primary = Contact::where('contact_id', $session_contact_id)->first();
                }
                $order_id = $payment_succeeded->data->object->metadata->order_id;

                $currentOrder = ApiOrder::where('id', $order_id)->with(
                        'user.contact',
                        'apiOrderItem.product.options',
                        'texClasses'
                    )->first();
                $order_contact = Contact::where('contact_id', $currentOrder->memberId)->first();
                if ($payment_succeeded->data->object->paid == true) {
                    $currentOrder->payment_status = 'paid';
                    $currentOrder->save();
                } else {
                    $currentOrder->payment_status =  'unpaid';
                    $currentOrder->save();
                }

                $order_comment = new OrderComment;
                $order_comment->order_id = $order_id;
                $order_comment->comment = 'Order Placed through Stripe';
                $order_comment->save();


               
                $order_items = ApiOrderItem::with('order.texClasses', 'product.options')
                ->where('order_id', $order_id)
                ->get();
                $contact = Contact::where('user_id', auth()->id())->first();
                $check_shipstation_create_order_status = AdminSetting::where('option_name', 'create_order_in_shipstation')->first();
                if (!empty($check_shipstation_create_order_status) && strtolower($check_shipstation_create_order_status->option_value) == 'yes') {
                    UserHelper::shipping_order($order_id , $currentOrder , $order_contact);
                    $shiping_order = UserHelper::shipping_order($order_id , $currentOrder , $order_contact);
                    if ($shiping_order['statusCode'] == 200) {
                        $orderUpdate = ApiOrder::where('id', $order_id)->update([
                            'shipstation_orderId' => $shiping_order['responseBody']->orderId,
                        ]);
                    }
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
                $auth_user_email = $contact->email;
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

                if ($auth_user_email) {
                    $data['email'] = $auth_user_email;
                    $data['subject'] = 'Your order has been received';
                    MailHelper::sendMailNotification('emails.admin-order-received', $data);
                }
                
            break;
            case 'invoice.payment_failed':
                // Handle payment failure event
            break;
            // Add more cases for other event types you want to handle
        }
        
        return response()->json(['status' => 'success']);
    }
}
