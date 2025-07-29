<?php

namespace App\Http\Controllers;
//use Auth\Http\AuthControllers\Auth;

use App\Exports\PayoutBalanceExport;
use App\Helpers\LabelHelper;
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
use App\Helpers\UtilHelper;
use App\Models\ApiKeys;
use App\Models\BuyListShippingAndDiscount;
use App\Models\Cart;
use App\Models\CustomerDiscountUses;
use App\Models\Discount;
use App\Models\OrderRefund;
use App\Models\OrderReminder;
use App\Models\OrderStatus;
use App\Models\Payout;
use App\Models\PayoutBalance;
use App\Models\SpecificAdminNotification;
use Barryvdh\DomPDF\Facade\Pdf;
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
use Stripe\Refund;
use Stripe\TaxRate;
use Maatwebsite\Excel\Facades\Excel;
class OrderController extends Controller
{
    public function store(Request $request)
    {
        
        $request->validate(
            [
                'method_name' => 'required',
                // 'internal_comments' => 'required',
                // 'memo' => 'required',
            ] 
            // , 
            // [
            //     'internal_comments.required' => 'Internal comments is required',
            //     'memo.required' => 'Delivery instructions is required',
            // ]
        );



        $shipment_error = $request->shipment_error;
        $re_order_id = !empty($request->re_order_id) ? $request->re_order_id : null;
        
        $parcel_guard = !empty($request->parcel_guard) ? $request->parcel_guard : 0;
        $shipping_service_code = null;
        $shipping_carrier_code = null;
        $actual_shipping_price = 0;
        $shipstation_shipment_value = 0;
        $upgrade_shipping = 0;
        $admin_area_for_shipping = AdminSetting::where('option_name', 'admin_area_for_shipping')->first();
        $upgrade_shipping = AdminSetting::where('option_name', 'enable_upgrade_shipping')->first();
        $buyListData = $request->buyListData;
        $buyListShipping = !empty($request->buyLIst_shipping_cost) ? floatval($request->buyLIst_shipping_cost) : 0;
        $buy_list_id = !empty(session()->get('buy_list_id')) ? session()->get('buy_list_id') : null;
        $buyListDiscount = !empty($request->buyListDiscountInput) ? floatval($request->buyListDiscountInput) : 0;
        $buyListDiscountType = !empty($request->buyListdiscount_type) ? $request->buyListdiscount_type : null;


        if (!empty($buyListData) &&  $buyListData == true && $buyListShipping > 0) {
            $actual_shipping_price = $buyListShipping;
            $shipstation_shipment_value = floatval($actual_shipping_price);
        }
        elseif (!empty($buyListData) &&  $buyListData == true && $buyListShipping == 0) {
            if ($shipment_error == 1) {
                return back()->with('error', 'There was an issue getting a freight quote, please try again later');
            }

            if (!empty($buyListData))  {
                $buyListDiscount = $buyListDiscount;
                $buyListDiscountType =$buyListDiscountType;
            } else {
                $buyListDiscount = null;
                $buyListDiscountType = null;
            }
    
    
            if (!empty($upgrade_shipping) && strtolower($upgrade_shipping->option_value) == 'yes') {
    
                if (!empty($request->charge_shipment_to_customer) && $request->charge_shipment_to_customer == 1) {
                    if (empty($request->shipping_free_over_1000) && ($request->shipping_free_over_1000 != '1')) {
                        if (!empty($admin_area_for_shipping) && strtolower($admin_area_for_shipping->option_value) == 'yes') {
                            if (!empty($request->product_weight) && floatval($request->product_weight) > 99) {
                                $actual_shipping_price = $request->shipment_cost_single;
                                $shipping_service_code = $request->shipping_service_code;
                                $shipping_carrier_code = $request->shipping_carrier_code;
                                $shipstation_shipment_value = $actual_shipping_price;
                            } else {
                                if (empty($request->shipping_multi_price)) {
                                    return back()->with('error', 'Shipping price is required. Please select shipping method.');
                                } else {
                                    $actual_shipping_price = $request->shipping_multi_price;
                                    $shipping_service_code = $request->shipping_service_code;
                                    $shipping_carrier_code = $request->shipping_carrier_code;
                                    $shipstation_shipment_value = $actual_shipping_price;
                                }
                            }
                        } else {
                            $actual_shipping_price = $request->shipment_price;
                            $shipping_service_code = $request->shipping_service_code;
                            $shipping_carrier_code = $request->shipping_carrier_code;
                            $shipstation_shipment_value = $actual_shipping_price;
                        }
                    } else {
    
                        if (!empty($request->upgrade_shipping) && $request->upgrade_shipping == 1) {
                            if (empty($request->upgrade_shipping_multi_price)) {
                                return back()->with('error', 'Shipping price is required. Please select shipping method.');
                            } else {
                                $actual_shipping_price = $request->upgrade_shipping_multi_price;
                                $shipping_service_code = $request->upgrade_shipping_service_code;
                                $shipping_carrier_code = $request->upgrade_shipping_carrier_code;
                                $shipstation_shipment_value = $actual_shipping_price;
                                $upgrade_shipping = 1;
                            }
                        }
    
                        else {
                            if (!empty($request->product_weight) && floatval($request->product_weight) > 99) {
                                $shipping_service_code =  SettingHelper::getSetting('shipping_carrier_code_2');
                                $shipping_carrier_code = SettingHelper::getSetting('shipping_service_code_2');
                            } else {
                                $shipping_service_code =  SettingHelper::getSetting('shipping_service_code');
                                $shipping_carrier_code = SettingHelper::getSetting('shipping_carrier_code');
                            }
        
                            $actual_shipping_price = !empty($request->shipment_price) ? $request->shipment_price : 0;
                            $shipstation_shipment_value = $actual_shipping_price;
                        }
                    }
                } else{
                    $actual_shipping_price = $request->shipment_price;
                    $shipping_service_code = $request->shipping_service_code;
                    $shipping_carrier_code = $request->shipping_carrier_code;
                    $shipstation_shipment_value = $actual_shipping_price;
                }
            } 
            else {
                if (!empty($request->charge_shipment_to_customer) && $request->charge_shipment_to_customer == 1) {
                    if (empty($request->shipping_free_over_1000) && ($request->shipping_free_over_1000 != '1')) {
                        if (!empty($admin_area_for_shipping) && strtolower($admin_area_for_shipping->option_value) == 'yes') {
                            if (!empty($request->product_weight) && floatval($request->product_weight) > 99) {
                                $actual_shipping_price = $request->shipment_cost_single;
                                $shipping_service_code = $request->shipping_service_code;
                                $shipping_carrier_code = $request->shipping_carrier_code;
                                $shipstation_shipment_value = $actual_shipping_price;
                            } else {
                                if (empty($request->shipping_multi_price)) {
                                    return back()->with('error', 'Shipping price is required. Please select shipping method.');
                                } else {
                                    $actual_shipping_price = $request->shipping_multi_price;
                                    $shipping_service_code = $request->shipping_service_code;
                                    $shipping_carrier_code = $request->shipping_carrier_code;
                                    $shipstation_shipment_value = $actual_shipping_price;
                                }
                            }
                        } else {
                            $actual_shipping_price = !empty($request->shipment_price) ? $request->shipment_price : 0;
                            $shipping_service_code = $request->shipping_service_code;
                            $shipping_carrier_code = $request->shipping_carrier_code;
                            $shipstation_shipment_value = $actual_shipping_price;
                        }
                    } else {
    
                        if (!empty($request->product_weight) && floatval($request->product_weight) > 99) {
                            $shipping_service_code =  SettingHelper::getSetting('shipping_carrier_code_2');
                            $shipping_carrier_code = SettingHelper::getSetting('shipping_service_code_2');
                        } else {
                            $shipping_service_code =  SettingHelper::getSetting('shipping_service_code');
                            $shipping_carrier_code = SettingHelper::getSetting('shipping_carrier_code');
                        }
    
                        $actual_shipping_price = !empty($request->shipment_price) ? $request->shipment_price : 0;
                        $shipstation_shipment_value = $actual_shipping_price;
                    }
                } else{
                    $actual_shipping_price = !empty($request->shipment_price) ? $request->shipment_price : 0;
                    $shipping_service_code = $request->shipping_service_code;
                    $shipping_carrier_code = $request->shipping_carrier_code;
                    $shipstation_shipment_value = $actual_shipping_price;
                }
            }
        } 
        else {
            if ($shipment_error == 1) {
                return back()->with('error', 'There was an issue getting a freight quote, please try again later');
            }

            if (!empty($buyListData))  {
                $buyListDiscount = $buyListDiscount;
                $buyListDiscountType =$buyListDiscountType;
            } else {
                $buyListDiscount = null;
                $buyListDiscountType = null;
            }
    
    
            if (!empty($upgrade_shipping) && strtolower($upgrade_shipping->option_value) == 'yes') {
    
                if (!empty($request->charge_shipment_to_customer) && $request->charge_shipment_to_customer == 1) {
                    if (empty($request->shipping_free_over_1000) && ($request->shipping_free_over_1000 != '1')) {
                        if (!empty($admin_area_for_shipping) && strtolower($admin_area_for_shipping->option_value) == 'yes') {
                            if (!empty($request->product_weight) && floatval($request->product_weight) > 99) {
                                $actual_shipping_price = $request->shipment_cost_single;
                                $shipping_service_code = $request->shipping_service_code;
                                $shipping_carrier_code = $request->shipping_carrier_code;
                                $shipstation_shipment_value = $actual_shipping_price;
                            } else {
                                if (empty($request->shipping_multi_price)) {
                                    return back()->with('error', 'Shipping price is required. Please select shipping method.');
                                } else {
                                    $actual_shipping_price = $request->shipping_multi_price;
                                    $shipping_service_code = $request->shipping_service_code;
                                    $shipping_carrier_code = $request->shipping_carrier_code;
                                    $shipstation_shipment_value = $actual_shipping_price;
                                }
                            }
                        } else {
                            $actual_shipping_price = $request->shipment_price;
                            $shipping_service_code = $request->shipping_service_code;
                            $shipping_carrier_code = $request->shipping_carrier_code;
                            $shipstation_shipment_value = $actual_shipping_price;
                        }
                    } else {
    
                        if (!empty($request->upgrade_shipping) && $request->upgrade_shipping == 1) {
                            if (empty($request->upgrade_shipping_multi_price)) {
                                return back()->with('error', 'Shipping price is required. Please select shipping method.');
                            } else {
                                $actual_shipping_price = $request->upgrade_shipping_multi_price;
                                $shipping_service_code = $request->upgrade_shipping_service_code;
                                $shipping_carrier_code = $request->upgrade_shipping_carrier_code;
                                $shipstation_shipment_value = $actual_shipping_price;
                                $upgrade_shipping = 1;
                            }
                        }
    
                        else {
                            if (!empty($request->product_weight) && floatval($request->product_weight) > 99) {
                                $shipping_service_code =  SettingHelper::getSetting('shipping_carrier_code_2');
                                $shipping_carrier_code = SettingHelper::getSetting('shipping_service_code_2');
                            } else {
                                $shipping_service_code =  SettingHelper::getSetting('shipping_service_code');
                                $shipping_carrier_code = SettingHelper::getSetting('shipping_carrier_code');
                            }
        
                            $actual_shipping_price = !empty($request->shipment_price) ? $request->shipment_price : 0;
                            $shipstation_shipment_value = $actual_shipping_price;
                        }
                    }
                } else{
                    $actual_shipping_price = $request->shipment_price;
                    $shipping_service_code = $request->shipping_service_code;
                    $shipping_carrier_code = $request->shipping_carrier_code;
                    $shipstation_shipment_value = $actual_shipping_price;
                }
            } 
            else {
                if (!empty($request->charge_shipment_to_customer) && $request->charge_shipment_to_customer == 1) {
                    if (empty($request->shipping_free_over_1000) && ($request->shipping_free_over_1000 != '1')) {
                        if (!empty($admin_area_for_shipping) && strtolower($admin_area_for_shipping->option_value) == 'yes') {
                            if (!empty($request->product_weight) && floatval($request->product_weight) > 99) {
                                $actual_shipping_price = $request->shipment_cost_single;
                                $shipping_service_code = $request->shipping_service_code;
                                $shipping_carrier_code = $request->shipping_carrier_code;
                                $shipstation_shipment_value = $actual_shipping_price;
                            } else {
                                if (empty($request->shipping_multi_price)) {
                                    return back()->with('error', 'Shipping price is required. Please select shipping method.');
                                } else {
                                    $actual_shipping_price = $request->shipping_multi_price;
                                    $shipping_service_code = $request->shipping_service_code;
                                    $shipping_carrier_code = $request->shipping_carrier_code;
                                    $shipstation_shipment_value = $actual_shipping_price;
                                }
                            }
                        } else {
                            $actual_shipping_price = !empty($request->shipment_price) ? $request->shipment_price : 0;
                            $shipping_service_code = $request->shipping_service_code;
                            $shipping_carrier_code = $request->shipping_carrier_code;
                            $shipstation_shipment_value = $actual_shipping_price;
                        }
                    } else {
    
                        if (!empty($request->product_weight) && floatval($request->product_weight) > 99) {
                            $shipping_service_code =  SettingHelper::getSetting('shipping_carrier_code_2');
                            $shipping_carrier_code = SettingHelper::getSetting('shipping_service_code_2');
                        } else {
                            $shipping_service_code =  SettingHelper::getSetting('shipping_service_code');
                            $shipping_carrier_code = SettingHelper::getSetting('shipping_carrier_code');
                        }
    
                        $actual_shipping_price = !empty($request->shipment_price) ? $request->shipment_price : 0;
                        $shipstation_shipment_value = $actual_shipping_price;
                    }
                } else{
                    $actual_shipping_price = !empty($request->shipment_price) ? $request->shipment_price : 0;
                    $shipping_service_code = $request->shipping_service_code;
                    $shipping_carrier_code = $request->shipping_carrier_code;
                    $shipstation_shipment_value = $actual_shipping_price;
                }
            }
        }

        

        $parcel_guard = 0;
        if (floatval($actual_shipping_price) > 0) {
            $parcel_guard = (ceil($actual_shipping_price / 100) * 0.99);
        }
        
        $first_name_shipping = $request->first_name_shipping;
        $last_name_shipping = $request->last_name_shipping;
        $address_1_shipping = $request->address_1_shipping;
        $address_2_shipping = $request->address_2_shipping;
        $city_shipping = $request->city_shipping;
        $state_shipping = $request->state_shipping;
        $zip_code_shipping = $request->zip_code_shipping;
        $country_shipping = $request->country_shipping;
        $phone_shipping = $request->phone_shipping;
        $company_shipping = $request->shipping_company;


        $first_name_billing = $request->first_name_billing;
        $last_name_billing = $request->last_name_billing;
        $address_1_billing = $request->address_1_billing;
        $address_2_billing = $request->address_2_billing;
        $city_billing = $request->city_billing;
        $state_billing = $request->state_billing;
        $zip_code_billing = $request->zip_code_billing;
        $country_billing = $request->country_billing;
        $phone_billing = $request->phone_billing;
        $company_billing = $request->billing_company;

        if (empty($address_1_shipping) || empty($state_shipping) || empty($zip_code_shipping) || empty($address_1_billing) || empty($state_billing) || empty($zip_code_billing)) {
            return back()->with('error', 'Billing and Shipping address is required.');
        }
        
        $paymentMethod = $request->input('method_name');
        $paymentMethodOption = $request->input('method_option');
        $paymentMethod = $paymentMethodOption;
        $date = !empty($request->date) ? Carbon::parse($request->date)->format('Y-m-d H:i') : Carbon::now();
        $delivery_instructions = null;
        if (!empty($request->method_option) && (strtolower($request->method_option) === 'pickup order')) {
            $delivery_instructions = 'web pickup order placed on ' . $date . ' ' . $request->memo;
        } else {
            $delivery_instructions = $request->memo;
        }
        //check if user have already contact with cin7
        $existing_contact = Contact::where('user_id', Auth::id())->first();
        $session_contact_id = Session::get('contact_id');
        $order_status = OrderStatus::where('status', 'New')->first();
        $discount_amount = !empty($request->discount_amount) ? floatval($request->discount_amount) : floatval(0);
        $discount_type = $request->discount_variation;
        $discount_id =  $request->discount_id;
        $total_tax_rate = !empty($request->total_tax) ? $request->total_tax : 0;
        $discount_variation_value = !empty($request->discount_variation_value) ? $request->discount_variation_value : 0;
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
                    // $shipment_price = !empty($request->shipment_price) ? $request->shipment_price : 0;
                    // $total_tax = !empty($request->total_tax) ? $request->total_tax : 0;
                    // $total_amount_with_discount = $cart_total - $discount_amount;
                    // $order_total = $total_amount_with_discount + $total_tax + $shipment_price;
                    $order_total = floatval($request->incl_tax);
                } else {
                    $order_total = floatval($request->incl_tax);
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
                    $order->buylist_id = $buy_list_id;
                    $order->buylist_discount = $buyListDiscount;
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
                    $order->internal_comments = $request->internal_comments;
                    // $order->delievery_instructions = $request->delievery_instructions;
                    $order->shipment_price = $request->shipment_price;
                    $order->is_square = 1;
                    $order->shipping_carrier_code = $shipping_carrier_code;
                    $order->shipping_service_code = $shipping_service_code;
                    $order->parcel_guard = $parcel_guard;

                    //saving shipping details
                    $order->DeliveryFirstName = $first_name_shipping;
                    $order->DeliveryLastName = $last_name_shipping;
                    $order->DeliveryCompany = $company_shipping;
                    $order->DeliveryAddress1 = $address_1_shipping;
                    $order->DeliveryAddress2 = $address_2_shipping;
                    $order->DeliveryCity = $city_shipping;
                    $order->DeliveryState = $state_shipping;
                    $order->DeliveryZip = $zip_code_shipping;
                    $order->DeliveryCountry = $country_shipping;
                    $order->DeliveryPhone = $phone_shipping;

                    // saving billing details
                    $order->BillingFirstName = $first_name_billing;
                    $order->BillingLastName = $last_name_billing;
                    $order->BillingCompany = $company_billing;
                    $order->BillingAddress1 = $address_1_billing;
                    $order->BillingAddress2 = $address_2_billing;
                    $order->BillingCity = $city_billing;
                    $order->BillingState = $state_billing;
                    $order->BillingZip = $zip_code_billing;
                    $order->BillingCountry = $country_billing;
                    $order->BillingPhone = $phone_billing;

                    $order->upgrade_shipping = $upgrade_shipping;
                    $order->re_order_id = $re_order_id;

                    $order->save();

                    $order_id =  $order->id;
                    $currentOrder = ApiOrder::where('id', $order->id)->first();
                    $apiApproval = $currentOrder->apiApproval;
                    $random_string = Str::random(10);
                    $currentOrder->reference = 'DEV4' . '-QCOM-' .$random_string . '-' .$order_id;
                    
                    
                    if (!empty($currentOrder->date)) {
                        $delivery_date = (!empty($currentOrder->date) && Carbon::parse($currentOrder->date)->lt($currentOrder->createdDate))
                            ? Carbon::parse($currentOrder->createdDate)->addHours(24)
                            : Carbon::parse($currentOrder->date);
                    } else {
                        $delivery_date = Carbon::parse($currentOrder->createdDate)->addHours(24);
                    }

                    $currentOrder->date = $delivery_date;
                    $currentOrder->save();
                    $currentOrder = ApiOrder::where('id', $order_id)->with(
                        'contact',
                        'user.contact',
                        'apiOrderItem.product.options',
                        'texClasses'
                    )->first();


                    // update order reminder table to expire the notification
                    if (!empty($order->re_order_id)) {

                        $order_reminder = OrderReminder::where('order_id' ,$order->re_order_id)->first();
                        $order_reminder->update([
                            'is_expired' => Carbon::today()
                        ]);

                        session()->forget('re_order_id');
                    }


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

                        
                        
                    } else {
                        session()->forget('cart');
                        return redirect('/');
                    }

                    session()->forget('cart');
                    return redirect($checkoutUrl);
                }
                elseif ($go_to_stripe_checkout) {

                    if (floatval($order_total)  < floatval(0.50)) {
                        return back()->with('error', 'Order total must be greater or equal to than $0.50 to proceed with payment.');
                    }

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
                    $order->buylist_id = $buy_list_id;
                    $order->buylist_discount = $buyListDiscount;
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
                    $order->memo = $delivery_instructions;
                    $order->date = $request->date;
                    $order->internal_comments = $request->internal_comments;
                    // $order->delievery_instructions = $request->delievery_instructions;
                    $order->shipment_price = $actual_shipping_price;
                    $order->total_including_tax = $order_total;
                    $order->discount_id = $discount_id;
                    $order->discount_amount = $discount_amount;
                    $order->tax_rate = $total_tax_rate;
                    $order->is_stripe = 1;
                    $order->shipping_carrier_code = $shipping_carrier_code;
                    $order->shipping_service_code = $shipping_service_code;
                    $order->parcel_guard = $parcel_guard;

                    //saving shipping details
                    $order->DeliveryFirstName = $first_name_shipping;
                    $order->DeliveryLastName = $last_name_shipping;
                    $order->DeliveryCompany = $company_shipping;
                    $order->DeliveryAddress1 = $address_1_shipping;
                    $order->DeliveryAddress2 = $address_2_shipping;
                    $order->DeliveryCity = $city_shipping;
                    $order->DeliveryState = $state_shipping;
                    $order->DeliveryZip = $zip_code_shipping;
                    $order->DeliveryCountry = $country_shipping;
                    $order->DeliveryPhone = $phone_shipping;

                    // saving billing details
                    $order->BillingFirstName = $first_name_billing;
                    $order->BillingLastName = $last_name_billing;
                    $order->BillingCompany = $company_billing;
                    $order->BillingAddress1 = $address_1_billing;
                    $order->BillingAddress2 = $address_2_billing;
                    $order->BillingCity = $city_billing;
                    $order->BillingState = $state_billing;
                    $order->BillingZip = $zip_code_billing;
                    $order->BillingCountry = $country_billing;
                    $order->BillingPhone = $phone_billing;
                    $order->upgrade_shipping = $upgrade_shipping;
                    $order->re_order_id = $re_order_id;

                    $order->save();

                    
                    if (!empty($enable_discount_setting) && strtolower($enable_discount_setting->option_value) == 'yes') {
                        if (!empty($order->discount_id)) {
                            $update_discount_count = Discount::where('id', $order->discount_id)->first();
                            if (!empty($update_discount_count)) {
                                $update_discount_count->usage_count = !empty($update_discount_count->usage_count) ? intval($update_discount_count->usage_count) + 1 : 0 + 1;
                                $update_discount_count->save();

                                $customer_discount_uses = new CustomerDiscountUses();
                                $customer_discount_uses->discount_id = $order->discount_id;
                                $customer_discount_uses->contact_id = $order->memberId;
                                $customer_discount_uses->save();
                            }
                        }
                    }

                    $order_id =  $order->id;
                    $currentOrder = ApiOrder::where('id', $order->id)->first();
                    $apiApproval = $currentOrder->apiApproval;
                    $random_string = Str::random(10);
                    
                    $currentOrder->reference = 'Stripe-Paid-CC-' .$random_string . '-' .$order_id;

                    if (!empty($currentOrder->date)) {
                        $delivery_date = (!empty($currentOrder->date) && Carbon::parse($currentOrder->date)->lt($currentOrder->createdDate))
                            ? Carbon::parse($currentOrder->createdDate)->addHours(24)
                            : Carbon::parse($currentOrder->date);
                    } else {
                        $delivery_date = Carbon::parse($currentOrder->createdDate)->addHours(24);
                    }

                    $currentOrder->date = $delivery_date;

                    $currentOrder->save();
                    $currentOrder = ApiOrder::where('id', $order_id)->with(
                        'contact',
                        'user.contact',
                        'apiOrderItem.product.options',
                        'texClasses'
                    )->first();


                    if (!empty($order->re_order_id)) {

                        $order_reminder = OrderReminder::where('order_id' ,$order->re_order_id)->first();
                        $order_reminder->update([
                            'is_expired' => Carbon::today()
                        ]);

                        session()->forget('re_order_id');
                    }

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
                        UtilHelper::update_product_stock_on_local($cart_items);
                        $checkout_session = null;
                        if (!empty($enable_discount_setting) && strtolower($enable_discount_setting->option_value) == 'yes' && ($discount_amount > 0)) {
                            $checkout = $this->apply_discount($tax_rate,  $discount_amount, $discount_type, $order_id, $currentOrder, $cart_items, $request , $discount_variation_value , $product_prices , $order_total , $actual_shipping_price,$shipstation_shipment_value , $parcel_guard);
                            if ($checkout) {
                                // session()->forget('cart');
                                $this->empty_cart_for_current_order();
                                return redirect($checkout->url);
                            }
                            
                        } 
                        else {
                            $checkout = $this->checkout_without_discount($tax_rate,  $discount_amount, $discount_type, $order_id, $currentOrder, $cart_items, $request , $discount_variation_value , $product_prices , $order_total, $actual_shipping_price ,$shipstation_shipment_value, $parcel_guard,$buyListDiscount ,$buyListDiscountType );
                            if ($checkout) {
                                // session()->forget('cart');
                                $this->empty_cart_for_current_order();
                                return redirect($checkout->url);
                            }
                        }
                        
                    } else {
                        session()->forget('cart');
                        return redirect('/');
                    }
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
                    $order->buylist_id = $buy_list_id;
                    $order->buylist_discount = $buyListDiscount;
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
                    $order->memo = $delivery_instructions;
                    $order->date = $request->date;
                    $order->internal_comments = $request->internal_comments;
                    // $order->delievery_instructions = $request->delievery_instructions;
                    $order->shipment_price = $actual_shipping_price;
                    $order->total_including_tax = $order_total;
                    $order->discount_id = $discount_id;
                    $order->discount_amount = $discount_amount;
                    $order->tax_rate = $total_tax_rate;
                    $order->parcel_guard = $parcel_guard;
                    $order->shipping_carrier_code = $shipping_carrier_code;
                    $order->shipping_service_code = $shipping_service_code;
                    
                    //saving shipping details
                    $order->DeliveryFirstName = $first_name_shipping;
                    $order->DeliveryLastName = $last_name_shipping;
                    $order->DeliveryCompany = $company_shipping;
                    $order->DeliveryAddress1 = $address_1_shipping;
                    $order->DeliveryAddress2 = $address_2_shipping;
                    $order->DeliveryCity = $city_shipping;
                    $order->DeliveryState = $state_shipping;
                    $order->DeliveryZip = $zip_code_shipping;
                    $order->DeliveryCountry = $country_shipping;
                    $order->DeliveryPhone = $phone_shipping;

                    // saving billing details
                    $order->BillingFirstName = $first_name_billing;
                    $order->BillingLastName = $last_name_billing;
                    $order->BillingCompany = $company_billing;
                    $order->BillingAddress1 = $address_1_billing;
                    $order->BillingAddress2 = $address_2_billing;
                    $order->BillingCity = $city_billing;
                    $order->BillingState = $state_billing;
                    $order->BillingZip = $zip_code_billing;
                    $order->BillingCountry = $country_billing;
                    $order->BillingPhone = $phone_billing;
                    $order->upgrade_shipping = $upgrade_shipping;
                    $order->re_order_id = $re_order_id;
                    $order->save();


                    if (!empty($enable_discount_setting) && strtolower($enable_discount_setting->option_value) == 'yes') {
                        if (!empty($order->discount_id)) {
                            $update_discount_count = Discount::where('id', $order->discount_id)->first();
                            if (!empty($update_discount_count)) {
                                $update_discount_count->usage_count = !empty($update_discount_count->usage_count) ? intval($update_discount_count->usage_count) + 1 : 0 + 1;
                                $update_discount_count->save();

                                $customer_discount_uses = new CustomerDiscountUses();
                                $customer_discount_uses->discount_id = $order->discount_id;
                                $customer_discount_uses->contact_id = $order->memberId;
                                $customer_discount_uses->save();
                            }
                        }
                    }

                    $order_id =  $order->id;
                    $currentOrder = ApiOrder::where('id', $order->id)->first();
                    $apiApproval = $currentOrder->apiApproval;
                    $random_string = Str::random(10);
                    $currentOrder->reference = 'DEV4' . '-QCOM-' .$random_string . '-' .$order_id;

                    if (!empty($currentOrder->date)) {
                        $delivery_date = (!empty($currentOrder->date) && Carbon::parse($currentOrder->date)->lt($currentOrder->createdDate))
                            ? Carbon::parse($currentOrder->createdDate)->addHours(24)
                            : Carbon::parse($currentOrder->date);
                    } else {
                        $delivery_date = Carbon::parse($currentOrder->createdDate)->addHours(24);
                    }

                    $currentOrder->date = $delivery_date;
                    $currentOrder->save();
                    $currentOrder = ApiOrder::where('id', $order_id)->with(
                        'contact',
                        'user.contact',
                        'apiOrderItem.product.options',
                        'texClasses'
                    )->first();


                    if (!empty($order->re_order_id)) {

                        $order_reminder = OrderReminder::where('order_id' ,$order->re_order_id)->first();
                        $order_reminder->update([
                            'is_expired' => Carbon::today()
                        ]);

                        session()->forget('re_order_id');
                    }

                    if (!empty($currentOrder->buylist_id) && !empty($currentOrder->buylist_discount)) {
                        $update_buy_list_shipping_and_discount = BuyListShippingAndDiscount::where('buylist_id', $currentOrder->buylist_id)->first();
                        if (!empty($update_buy_list_shipping_and_discount)) {
                            $update_buy_list_shipping_and_discount->discount_count = !empty($update_buy_list_shipping_and_discount->discount_count) ? $update_buy_list_shipping_and_discount->discount_count + 1 : 1;
                            $update_buy_list_shipping_and_discount->save();
                        }
                    }

                    $order_contact = Contact::where('contact_id', $currentOrder->memberId)->first();
                    $reference = $currentOrder->reference;
                    $cart_items = UserHelper::switch_price_tier($request);
                    // if (session()->has('cart')) {
                    if ($cart_items) {
                        foreach ($cart_items as $cart_item) {
                            $OrderItem = new ApiOrderItem;
                            $OrderItem->order_id = $order_id;
                            $OrderItem->product_id = $cart_item['product_id'];
                            $OrderItem->quantity =  $cart_item['quantity'];
                            $OrderItem->price = $cart_item['price'];
                            $OrderItem->option_id = $cart_item['option_id'];
                            $OrderItem->save();
                        }

                        UtilHelper::update_product_stock_on_local($cart_items);
                    } else {
                        session()->forget('cart');
                        return redirect('/');
                    }

                    // $order_items = ApiOrderItem::with('order.texClasses','product' ,'product.options')
                    //     ->where('order_id', $order_id)
                    //     ->get();

                    $option_ids = ApiOrderItem::where('order_id', $order_id)->pluck('option_id')->toArray();
                    $order_items = ApiOrderItem::with(['product.options' => function ($q) use ($option_ids) {
                        $q->whereIn('option_id', $option_ids);
                    }])->where('order_id', $order_id)->get();

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
                        'delievery_method' => $currentOrder->logisticsCarrier,
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
                    //old code
                    
                    // if (count($specific_admin_notifications) > 0) {
                    //     foreach ($specific_admin_notifications as $specific_admin_notification) {
                    //         if (empty($specific_admin_notification->receive_order_notifications)) {
                    //             continue;
                    //         }   
                    //         $subject = 'New Indoorsun Hydro order' .'#'.$currentOrder->id. ' ' . 'received';
                    //         $adminTemplate = 'emails.admin-order-received';
                    //         $data['subject'] = $subject;
                    //         $data['email'] = $specific_admin_notification->email;
                    //         MailHelper::sendMailNotification('emails.admin-order-received', $data);
                    //     }
                    // }
                    $specific_admin_notifications = SpecificAdminNotification::all();
                    if ($specific_admin_notifications->isNotEmpty()) {
                        foreach ($specific_admin_notifications as $specific_admin_notification) {
                            // Check if this admin should receive order notifications
                            if (!$specific_admin_notification->receive_order_notifications) {
                                continue;
                            }

                            $subject = 'New Indoorsun Hydro order #' . $currentOrder->id . ' received';
                            $data['subject'] = $subject;
                            $data['email'] = $specific_admin_notification->email;
                            
                            MailHelper::sendMailNotification('emails.admin-order-received', $data);
                        }
                    }

                    
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

                    // session()->forget('cart');
                    $this->empty_cart_for_current_order();
                    return Redirect::route('thankyou', $order_id);
                }
            }
        }

        return redirect('/checkout');
    }

    public function empty_cart_for_current_order() {
        $user_id = Auth::id();
        $session_contact_id = Session::get('contact_id');
        
        $delete_cart = Cart::where('user_id', $user_id)->where('is_active', 1)->get();
        if (count($delete_cart) > 0) {
            foreach ($delete_cart as $cart) {
                if (!empty($cart->contact_id) && $cart->contact_id == $session_contact_id) {
                    $cart->delete();
                } 
            }
        }

        if ($session_contact_id) {
            Session::forget('cart');
            Session::forget('cart_hash');
        }

        Session::forget('buy_list_id');

        return true;
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

    public function create_label(Request $request) {
        $currentDate = date('Y-m-d');
        $client = new \GuzzleHttp\Client();
        $order_id = $request->order_id;
        $order = ApiOrder::where('id', $order_id)->first();
        $shipstation_order_id = $request->shipstation_orderId;
        $order_items_array = [];

        if ($order->label_created == 1 ) {
            return redirect()->route('admin.orders')->with('error', 'Label already created for this order.');
        }

        if (empty($shipstation_order_id)) {
            return redirect()->route('admin.orders')->with('error', 'shipstation_order_id not found.');
        }
        
        $label_created = LabelHelper::processControllerOrder($order, $client, $currentDate, $shipstation_order_id);

        // if ($label_created) {
        //     return redirect()->route('admin.orders')->with('success', 'Label created successfully.');
        // } else {
        //     return redirect()->route('admin.orders')->with('error', 'Error creating label.');
        // }

        if ($label_created == true) {
            return redirect()->route('admin.orders')->with('success', 'Label created successfully.');
        } else {
            return redirect()->route('admin.orders')->with('error', 'Error creating label.');
        }
    }

    

    // download shipment label for order
    public function download_label($filename) {
        // Get the full file path from the public directory
        $filePath = public_path('labels/' . $filename);
    
        // Check if the file exists
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }
    
        // Get the file contents
        $file = file_get_contents($filePath);
    
        // Return the file as a downloadable response
        return response($file)
            ->header('Content-Type', 'application/pdf') // Set content type for PDF
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"'); // Make it downloadable
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
        $shipstation_order_status = 'create_order';
        $shiping_order = UserHelper::shipping_order($order_id , $currentOrder , $order_contact , $shipstation_order_status);
    
        if ($shiping_order['statusCode'] == 200) {
            $orderUpdate = ApiOrder::where('id', $order_id)->update([
                'payment_status' => 'paid',
                'shipstation_orderId' => $shiping_order['responseBody']->orderId,
                'shipstation_orderKey' => $shiping_order['responseBody']->orderKey,
                'shipstation_orderNumber' => $shiping_order['responseBody']->orderNumber,
            ]);
        }
        return redirect()->back()->with('success', 'Order marked as paid successfully.');
    }


    // uppdate order status manually 
    public function update_order_status_by_admin(Request $request) {
        $request_status =  true;
        $message = 'Order status updated and  successfully.';
        $order_id = $request->order_id;
        $payment_status = $request->payment_status;
        $order_status_id = $request->order_status_id;
        $order = ApiOrder::with('apiOrderItem')->where('id', $order_id)->first();
        $previous_order_status = OrderStatus::where('id', $order->order_status_id)->first();
        $current_order_status = OrderStatus::where('id', $order_status_id)->first();
        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password_2');
        $refund_value = floatval($request->refund_value);
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        if ($order->is_stripe === 1) {
            if ((strtolower($current_order_status->status) === 'partial refund')  && $refund_value > 0) {
                
                try {

                    $order_refunds = OrderRefund::where('order_id', $order_id)->get();
                    $total_amount_refunded = 0;
                    if (count($order_refunds) > 0) {
                        foreach ($order_refunds as $order_refund) {
                            $total_amount_refunded += $order_refund->refund_amount;
                        }
                    }

                    if ($total_amount_refunded >= $order->total_including_tax) {
                        $request_status = 'Total amount refunded already';
                        $message = 'Partial Refund failed';
                        return response()->json(['success' => false , 'message' => $message]);
                    }
                    else {
                        $partial_refund = $stripe->refunds->create([
                            'charge' => $order->charge_id,
                            'amount' => intval($refund_value * 100),
                        ]);
        
                        if ($partial_refund->status === 'succeeded') { 
                            $request_status = true;
                            $message = 'Partial Refund request has been successfully created.';
        
                            $order->update([
                                'order_status_id' => $order_status_id,
                                'isApproved' => $current_order_status->status == 'Partial Refund' ? 4 : $order->isApproved
                            ]);
    
                            $order_refund = new OrderRefund;
                            $order_refund->order_id = $order_id;
                            $order_refund->refund_amount = $refund_value;
                            $order_refund->save();

                            if (!empty($request->refund_reason)) {

                                $order_comment = new OrderComment;
                                $order_comment->order_id = $order_id;
                                $order_comment->comment = !empty($request->refund_reason) ? 'Partial Refund Note :'.' '.$request->refund_reason : '';    
                                $order_comment->save();
                            }


                        } else {
                            $request_status = false;
                            $message = 'Partial Refund failed';
                            return response()->json(['success' => false , 'message' => $message]);
                        }
                    }
                } catch (\Exception $e) {
                    $request_status = false;
                    $message = $e->getMessage();
                    return response()->json(['success' => false , 'message' => $message]);
                }
            }
            if (strtolower($current_order_status->status) === 'refunded') {
                try {
                    $refund = $stripe->refunds->create(['charge' => $order->charge_id]);
                    if ($refund->status === 'succeeded') {
                        $request_status = true;
                        $message = 'Refund request has been successfully created.';
                        
                        $order_refund = new OrderRefund;
                        $order_refund->order_id = $order_id;
                        $order_refund->refund_amount = $order->total_including_tax;
                        $order_refund->save();


                        $order_comment = new OrderComment;
                        $order_comment->order_id = $order_id;
                        $order_comment->comment = !empty($request->refund_reason) ? 'Refund Note:'.' '.$request->refund_reason : '';    
                        $order_comment->save();

                        if (!empty($order->order_id)) {
                            $this->cancel_order($order , $current_order_status , $order_status_id,$cin7_auth_username , $cin7_auth_password);
                        }
                        
                    } else {
                        $request_status = false;
                        $message = 'Refund failed';
                        return response()->json(['success' => false , 'message' => $message]);
                    }
                } 
                catch (\Exception $e) {
                    $request_status = false;
                    $message = 'Stripe Refund failed: ' . $e->getMessage();
                    return response()->json(['success' => false , 'message' => $message]);
                }
            }
            $order->update([
                'order_status_id' => $order_status_id,
                'payment_status' => $order->payment_status,
                'isApproved' => $current_order_status->status == 'Cancelled' ? 2 : $order->isApproved
            ]);


            if ($order->isApproved == 2 && $order->payment_status == 'paid') {
                $order->update([
                    'payment_status' => 'unpaid'
                ]);
            } 
            elseif ($order->isApproved == 2 && $order->payment_status == 'unpaid') {
                $order->update([
                    'payment_status' => 'unpaid'
                ]);
            } 
            else {
                $order->update([
                    'payment_status' => $payment_status
                ]);
            }
        } 
        else {
            $order->update([
                'order_status_id' => $order_status_id,
                'isApproved' => $current_order_status->status == 'Cancelled' ? 2 : $order->isApproved
            ]);
        }

        
        if (($current_order_status->status == 'Cancelled') && $order->isApproved == 2) {
            UtilHelper::update_product_stock_on_cancellation($order);
            $this->cancel_order($order , $current_order_status , $order_status_id,$cin7_auth_username , $cin7_auth_password);
        }


        if (($current_order_status->status == 'Refunded') && $order->isApproved == 3) {
            UtilHelper::update_product_stock_on_cancellation($order);
        }
        


        $update_order_status_comment = new OrderComment;
        $update_order_status_comment->order_id = $order_id;
        $update_order_status_comment->comment = 'Order status updated manually from' . ' ' . (!empty($previous_order_status->status) ? $previous_order_status->status : '') . ' ' . 'to' . ' ' .  (!empty($current_order_status->status) ? $current_order_status->status : '');
        $update_order_status_comment->save();

        // $order_items = ApiOrderItem::with('order.texClasses', 'product.options')->where('order_id', $order_id)->get();
        $option_ids = ApiOrderItem::where('order_id', $order_id)->pluck('option_id')->toArray();
        $order_items = ApiOrderItem::with([
            'product.options' => function ($q) use ($option_ids) {
                $q->whereIn('option_id', $option_ids);
            },
            'order.texClasses'
        ])
        ->where('order_id', $order_id)
        ->get();
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
            'texClasses',
            'order_refund'
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
                'phone' => $customer->contact->phone,
                'postalCity' => $customer->contact->postalCity,
                'postalState' => $customer->contact->postalState,
                'postalPostCode' => $customer->contact->postalPostCode,
            ],
            'payment_terms' => !empty($customer->contact->paymentTerms) ? $customer->contact->paymentTerms : '30 Days from Invoice',
            'best_product' => $best_products,
            'user_email' =>   $customer->contact->email,
            'currentOrder' => $currentOrder,
            'count' => $count,
            'order_id' => $order_id,
            'company' => $currentOrder->contact->company, 
            'order_status' => 'updated',
            'delievery_method' => $currentOrder->logisticsCarrier,
            'new_order_status' => !empty($current_order_status->status) ? $current_order_status->status : '',
            'previous_order_status' => !empty($previous_order_status->status) ? $previous_order_status->status : '',
        ];
        // $email_template = !empty($current_order_status->status) && ($current_order_status->status === 'Cancelled') ? 'emails.cancel_order_email_template' : 'emails.admin-order-received';
        $email_template = 'emails.admin-order-received';
        $name = $customer->contact->firstName;
        $email =  $customer->contact->email;
        $reference  =  $currentOrder->reference;
        $template = 'emails.admin-order-received';
        
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

        



        if ($current_order_status->status !== 'Cancelled') {

            if (!empty($email)) {
                $data['subject'] = 'Your Indoorsun Hydro order' .'#'.$currentOrder->id. ' ' .'status has been updated';
                $data['email'] = $email;
                MailHelper::sendMailNotification($email_template, $data);
            }

            $specific_admin_notifications = SpecificAdminNotification::all();
            if (count($specific_admin_notifications) > 0) {
                foreach ($specific_admin_notifications as $specific_admin_notification) {
                    $subject = 'Indoorsun Hydro order' .'#'.$currentOrder->id. ' ' . 'status has been updated';
                    $adminTemplate = 'emails.admin-order-received';
                    $data['subject'] = $subject;
                    $data['email'] = $specific_admin_notification->email;
                    MailHelper::sendMailNotification($email_template, $data);
                }
            }


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
        }
        
        return response()->json(['success' => $request_status , 'message' => $message]);
    }


    public function cancel_order($order , $current_order_status ,$order_status_id, $cin7_auth_username , $cin7_auth_password) {
        
        $cin7api_key_for_other_jobs =  ApiKeys::where('password', $cin7_auth_password)
        ->where('is_active', 1)
        ->where('is_stop', 0)
        ->first();

        $api_key_id = null;
        
        if (!empty($cin7api_key_for_other_jobs)) {
            $cin7_auth_username = $cin7api_key_for_other_jobs->username;
            $cin7_auth_password = $cin7api_key_for_other_jobs->password;
            $threshold = $cin7api_key_for_other_jobs->threshold;
            $request_count = !empty($cin7api_key_for_other_jobs->request_count) ? $cin7api_key_for_other_jobs->request_count : 0;
            $api_key_id = $cin7api_key_for_other_jobs->id;
        } else {
            Log::info('No active api key found');
            return false;
        }

        if ($request_count >= $threshold) {
            Log::info('Request count exceeded');
            return false;
        }

        
        
        try {
            $client = new \GuzzleHttp\Client();
            
            $res = $client->request(
                'GET', 
                'https://api.cin7.com/api/v1/SalesOrders/' . $order->order_id,
                [
                    'auth' => [
                        $cin7_auth_username,
                        $cin7_auth_password
                    ]                    
                ]
            );

            UtilHelper::saveEndpointRequestLog('Get Sales Order','https://api.cin7.com/api/v1/SalesOrders', $api_key_id);
    
            $cin7_order = $res->getBody()->getContents();
            $get_order = json_decode($cin7_order);
            

            $update_internal_comments = '';
            $get_refund_reason_comment = '';
            $get_refund_reason = OrderComment::where('order_id', $order->id)
                ->where('comment', 'like', '%Refund Note:%')
                ->first();
            if (!empty($get_refund_reason)) {
                $get_refund_reason_comment = str_replace('Refund Note:', '', $get_refund_reason->comment);
            }
    
            if (!empty($get_order)) {
                if ($current_order_status->status == 'Refunded') {
                    $order->update([
                        'order_status_id' => $order_status_id,
                        'isApproved' => $current_order_status->status == 'Refunded' ? 3 : $order->isApproved
                    ]);

                    $update_internal_comments = !empty($get_refund_reason_comment) ? $get_order->internalComments . ' ' . $get_refund_reason_comment : $get_order->internalComments;
                }

                if ($current_order_status->status == 'Cancelled') {
                    $order->update([
                        'order_status_id' => $order_status_id,
                        'isApproved' => $current_order_status->status == 'Cancelled' ? 2 : $order->isApproved
                    ]);

                    $update_internal_comments = !empty($get_refund_reason_comment) ? $get_order->internalComments . ' ' . $get_refund_reason_comment : $get_order->internalComments;
                }

                $curent_order_voided = $get_order->isVoid ?? false;
                
                if ($curent_order_voided == false) {
                    $url = 'https://api.cin7.com/api/v1/SalesOrders';
                    $authHeaders = [
                        'headers' => ['Content-Type' => 'application/json'],
                        'auth' => [
                            $cin7_auth_username,
                            $cin7_auth_password,
                        ],
                    ];

                    UtilHelper::saveEndpointRequestLog('Update Sales Order','https://api.cin7.com/api/v1/SalesOrders', $api_key_id);

                    $update_array = [
                        [
                            "id" => $order->order_id,
                            "internalComments" => $update_internal_comments,
                        ]
                    ];

                    $authHeaders['json'] = $update_array;

                    $res = $client->put($url, $authHeaders);

                    $response = json_decode($res->getBody()->getContents());

                    // voiding the order
                    
                    UtilHelper::saveEndpointRequestLog('Update Sales Order','https://api.cin7.com/api/v1/SalesOrders', $api_key_id);

                    $void_payload = [
                        [
                            "id" => $order->order_id,
                            "isVoid" => true,
                            "isApproved" => false,
                        ]
                    ];

                    $authHeaders['json'] = $void_payload;

                    $res = $client->put($url, $authHeaders);

                    $response = json_decode($res->getBody()->getContents());

                }
            }
        } catch (\Exception $e) {
            // Handle request exception (e.g., log the error)
            Log::info('request_failded' . $e->getMessage());
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::info("An error occurred: " . $e->getMessage());
        }
    }

    public function apply_discount($tax_rate,  $discount_amount, $discount_type, $order_id, $currentOrder, $cart_items, $request , $discount_variation_value , $product_prices , $order_total , $actual_shipping_price,$shipstation_shipment_value , $parcel_guard) {
        $tax_rate = number_format($tax_rate, 2);
        $discount_variation_value  = $discount_variation_value;
        $percentage = null;
        
        if ($discount_variation_value >= 100  && $discount_type == 'percentage') {
            foreach ($cart_items as $cart_item) {
                $OrderItem = new ApiOrderItem;
                $OrderItem->order_id = $order_id;
                $OrderItem->product_id = $cart_item['product_id'];
                $OrderItem->quantity =  $cart_item['quantity'];
                $OrderItem->price = $cart_item['price'];
                $OrderItem->option_id = $cart_item['option_id'];
                $OrderItem->save();
            }
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
            $current_Order = ApiOrder::where('id', $order_id)->with(
                'user.contact',
                'apiOrderItem.product.options',
                'texClasses'
            )->first();
            if(!empty($current_Order)) {
                $current_Order->payment_status = 'paid';
                $current_Order->save();

                $order_comment = new OrderComment;
                $order_comment->order_id = $order_id;
                $order_comment->comment = 'Order marked as paid through qcom because of 100% discount.';
                $order_comment->save();

                
                $order_items = ApiOrderItem::with('order.texClasses', 'product.options')
                ->where('order_id', $order_id)
                ->get();
                
                $check_shipstation_create_order_status = AdminSetting::where('option_name', 'create_order_in_shipstation')->first();
                if (!empty($check_shipstation_create_order_status) && strtolower($check_shipstation_create_order_status->option_value) == 'yes') {
                    $order_contact = Contact::where('contact_id', $current_Order->memberId)->orWhere('parent_id' , $current_Order->memberId)->first();
                    if (!empty($order_contact)) {
                        $shipstation_order_status = 'create_order';
                        $shiping_order = UserHelper::shipping_order($order_id , $current_Order , $order_contact, $shipstation_order_status);
                        if ($shiping_order['statusCode'] == 200) {
                            $orderUpdate = ApiOrder::where('id', $order_id)->update([
                                'shipstation_orderId' => $shiping_order['responseBody']->orderId,
                                'shipstation_orderKey' => $shiping_order['responseBody']->orderKey,
                                'shipstation_orderNumber' => $shiping_order['responseBody']->orderNumber,
                            ]);
                        }
                    }
                }
                $customer_email = Contact::where('contact_id', $current_Order->memberId)->first();
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
                    'shipping_fee' => !empty($current_Order->shipment_price) ? $current_Order->shipment_price : '',
                    'best_product' => $best_products,
                    'user_email' =>   $user_email,
                    'currentOrder' => $current_Order,
                    'count' => $count,
                    'order_id' => $order_id,
                    'company' => !empty($current_Order->user->contact) ?  $current_Order->user->contact[0]->company : '',
                    'order_status' => '',
                    'delievery_method' => $currentOrder->logisticsCarrier,
                ];
                $name = $contact->firstName;
                $email =  $contact->email;
                $reference  =  $current_Order->reference;
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
                    'currentOrder' => $current_Order,
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

                session()->forget('cart');
                $checkout_session = '/thankyou/' .$order_id;
                return $checkout_session;
            }            
        }
        elseif ($order_total == 0  && $discount_type == 'fixed') {
            foreach ($cart_items as $cart_item) {
                $OrderItem = new ApiOrderItem;
                $OrderItem->order_id = $order_id;
                $OrderItem->product_id = $cart_item['product_id'];
                $OrderItem->quantity =  $cart_item['quantity'];
                $OrderItem->price = $cart_item['price'];
                $OrderItem->option_id = $cart_item['option_id'];
                $OrderItem->save();
            }
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
            $current_Order = ApiOrder::where('id', $order_id)->with(
                'user.contact',
                'apiOrderItem.product.options',
                'texClasses'
            )->first();
            if(!empty($current_Order)) {
                $current_Order->payment_status = 'paid';
                $current_Order->save();

                $order_comment = new OrderComment;
                $order_comment->order_id = $order_id;
                $order_comment->comment = 'Order marked as paid through qcom because of 100% discount.';
                $order_comment->save();

                
                $order_items = ApiOrderItem::with('order.texClasses', 'product.options')
                ->where('order_id', $order_id)
                ->get();
                
                $check_shipstation_create_order_status = AdminSetting::where('option_name', 'create_order_in_shipstation')->first();
                if (!empty($check_shipstation_create_order_status) && strtolower($check_shipstation_create_order_status->option_value) == 'yes') {
                    $order_contact = Contact::where('contact_id', $current_Order->memberId)->orWhere('parent_id' , $current_Order->memberId)->first();
                    if (!empty($order_contact)) {
                        $shipstation_order_status = 'create_order';
                        $shiping_order = UserHelper::shipping_order($order_id , $current_Order , $order_contact, $shipstation_order_status);
                        if ($shiping_order['statusCode'] == 200) {
                            $orderUpdate = ApiOrder::where('id', $order_id)->update([
                                'shipstation_orderId' => $shiping_order['responseBody']->orderId,
                                'shipstation_orderKey' => $shiping_order['responseBody']->orderKey,
                                'shipstation_orderNumber' => $shiping_order['responseBody']->orderNumber,
                            ]);
                        }
                    }
                }
                $customer_email = Contact::where('contact_id', $current_Order->memberId)->first();
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
                    'shipping_fee' => !empty($current_Order->shipment_price) ? $current_Order->shipment_price : '',
                    'best_product' => $best_products,
                    'user_email' =>   $user_email,
                    'currentOrder' => $current_Order,
                    'count' => $count,
                    'order_id' => $order_id,
                    'company' => !empty($current_Order->user->contact) ?  $current_Order->user->contact[0]->company : '',
                    'order_status' => '',
                    'delievery_method' => $currentOrder->logisticsCarrier,
                ];
                $name = $contact->firstName;
                $email =  $contact->email;
                $reference  =  $current_Order->reference;
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
                    'currentOrder' => $current_Order,
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

                session()->forget('cart');
                $checkout_session = '/thankyou/' .$order_id;
                return $checkout_session;
            }            
        } 
        else {
            $order_contact = Contact::where('contact_id', $currentOrder->memberId)->orWhere('parent_id' , $currentOrder->memberId)->first();
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $customer = $stripe->customers->create([
                'name' => $order_contact->firstName . ' ' . $order_contact->lastName,
                'email' => $order_contact->email,
            ]);    
            foreach ($cart_items as $cart_item) {
                $OrderItem = new ApiOrderItem;
                $OrderItem->order_id = $order_id;
                $OrderItem->product_id = $cart_item['product_id'];
                $OrderItem->quantity =  $cart_item['quantity'];
                $OrderItem->price = $cart_item['price'];
                $OrderItem->option_id = $cart_item['option_id'];
                $OrderItem->save();

                $products = $stripe->products->create([
                    'name' => $cart_item['name'],
                ]);
                $unit_price = 0;
                if ($discount_type == 'percentage') {
                    $percentage = 'percentage';
                    $unit_price = $cart_item['price'] -  ($cart_item['price'] * ($discount_variation_value / 100));
                } else {
                    $unit_price = $cart_item['price'] - $discount_variation_value;
                }
                $productPrice = $stripe->prices->create([
                    'unit_amount' => round($cart_item['price'] * 100),
                    'currency' => 'usd',
                    'product' => $products->id,
                    'metadata' => [
                        'quantity'=> $cart_item['quantity']
                    ]
                ]);
                array_push($product_prices, $productPrice);
            }
            
            if (!empty($tax_rate) && $tax_rate > 0) {
                $formatted_tax = number_format(($tax_rate * 100 ), 2);
                $tax_value = str_replace(',', '', $formatted_tax);
                // $formatted_tax_value = number_format(($formatted_tax_rate * 100) , 2);
                // $tax_value = str_replace(',', '', $formatted_tax_value);
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

            

            // adding shipping price to order
            if (!empty($shipstation_shipment_value) && floatval($shipstation_shipment_value) > 0) {
                $shipment_price = number_format((floatval($shipstation_shipment_value)) , 2);
                $shipment_value = str_replace(',', '', $shipment_price);
                $formatted_ship_value = number_format(($shipment_value * 100) , 2);
                $shipment_value_string = str_replace(',', '', $formatted_ship_value);
                $shipment_product = $stripe->products->create([
                    'name' => 'Shipment',
                ]);
                $shipment_product_price = $stripe->prices->create([
                    'unit_amount_decimal' => $shipment_value_string,
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
            $adding_discount = $stripe->coupons->create([
                'amount_off' => round($discount_amount * 100),
                'duration' => 'once',
                'currency' => 'usd',
            ]);
            $checkout_session = $stripe->checkout->sessions->create([
                'success_url' => url('/thankyou/' . $order_id) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => url('/checkout'),
                $line_items,
                'mode' => 'payment',
                'discounts' => [['coupon' => $adding_discount->id]],
                'payment_intent_data'=> [
                    "metadata" => [
                        "order_id"=> $order_id,
                    ]
                ],
                // 'shipping_cost' =>  !empty($request->shipment_price) ? $request->shipment_price : 0,
                'customer' => $customer->id,
                // 'customer_email' => auth()->user()->email,
                
            ]);
    
            return $checkout_session;
        }    
    }
    
    public function checkout_without_discount($tax_rate,  $discount_amount, $discount_type, $order_id, $currentOrder, $cart_items, $request , $discount_variation_value , $product_prices , $order_total , $actual_shipping_price,$shipstation_shipment_value, $parcel_guard , $buyListDiscount ,$buyListDiscountType ) {
        $tax_rate = number_format($tax_rate, 2);

        $buyListDiscount = !empty($buyListDiscount) ? number_format($buyListDiscount , 2) : 0;

        $order_contact = Contact::where('contact_id', $currentOrder->memberId)->orWhere('parent_id' , $currentOrder->memberId)->first();
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $customer = $stripe->customers->create([
            'name' => $order_contact->firstName . ' ' . $order_contact->lastName,
            'email' => $order_contact->email,
        ]);    
        foreach ($cart_items as $cart_item) {
            $OrderItem = new ApiOrderItem;
            $OrderItem->order_id = $order_id;
            $OrderItem->product_id = $cart_item['product_id'];
            $OrderItem->quantity =  $cart_item['quantity'];
            $OrderItem->price = $cart_item['price'];
            $OrderItem->option_id = $cart_item['option_id'];
            $OrderItem->save();



            $enable_selling_through_ai = AdminSetting::where('option_name', 'enable_selling_through_ai')
                ->where('option_value', 'Yes')
                ->first();

            if (!empty($enable_selling_through_ai) && strtolower($enable_selling_through_ai->option_value) == 'yes') {
                $product_option = ProductOption::where('option_id', $cart_item['option_id'])->first();
                $pricing_new = Pricingnew::where('option_id', $cart_item['option_id'])->first();

                if (!empty($pricing_new) && $pricing_new->enable_ai_price == 1) {
                    $product_option->update([
                        'sold_by_ai' => $cart_item['quantity']
                    ]);
                }
            }

            
            $products = $stripe->products->create([
                'name' => $cart_item['name'],
            ]);
            
            $productPrice = $stripe->prices->create([
                'unit_amount' => round($cart_item['price'] * 100),
                'currency' => 'usd',
                'product' => $products->id,
                'metadata' => [
                    'quantity'=> $cart_item['quantity']
                ]
            ]);
            array_push($product_prices, $productPrice);
        }

        if (!empty($tax_rate) && $tax_rate > 0) {
            $formatted_tax = number_format(($tax_rate * 100 ), 2);
            // $formatted_tax_rate = str_replace(',', '', $formatted_tax);
            $tax_value = str_replace(',', '', $formatted_tax);
            // $formatted_tax_value = number_format(($formatted_tax_rate * 100) , 2);
            //$tax_value = str_replace(',', '', $formatted_tax_value);
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

       

        // adding shipping price to order
        // if (!empty($actual_shipping_price) && $actual_shipping_price > 0) {
        //     $shipment_price = number_format(($actual_shipping_price * 100) , 2);
        //     $shipment_value = str_replace(',', '', $shipment_price);
        //     $shipment_product = $stripe->products->create([
        //         'name' => 'Shipment',
        //     ]);
        //     $shipment_product_price = $stripe->prices->create([
        //         'unit_amount_decimal' => $shipment_value,
        //         'currency' => 'usd',
        //         'product' => $shipment_product->id
        //     ]);
        //     $items[] = [
        //         'price' => $shipment_product_price->id,
        //         'quantity' => '1',
        //     ];
        // }

        if (!empty($shipstation_shipment_value) && floatval($shipstation_shipment_value) > 0) {
            $shipment_price = number_format((floatval($shipstation_shipment_value)) , 2);
            $shipment_value = str_replace(',', '', $shipment_price);
            $formatted_ship_value = number_format(($shipment_value * 100) , 2);
            $shipment_value_string = str_replace(',', '', $formatted_ship_value);
            $shipment_product = $stripe->products->create([
                'name' => 'Shipment',
            ]);
            $shipment_product_price = $stripe->prices->create([
                'unit_amount_decimal' => $shipment_value_string,
                'currency' => 'usd',
                'product' => $shipment_product->id
            ]);
            $items[] = [
                'price' => $shipment_product_price->id,
                'quantity' => '1',
            ];
        }


        if (!empty($buyListDiscount) && floatval($buyListDiscount) > 0) {

            // Convert to float to avoid issues with any string representation
            $buyListDiscount = floatval($buyListDiscount);
            
            // Check if the discount type is percentage
            $buyListDiscount = round($buyListDiscount, 2);
            $discount_value = (int) round($buyListDiscount * 100); // Convert to cents
            
            // Create Stripe coupon for fixed amount off
            $coupon = $stripe->coupons->create([
                'amount_off' => $discount_value,
                'currency' => 'usd',  // Consider making this dynamic based on user
                'duration' => 'once'
            ]);
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
            'discounts' => !empty($coupon) ? [['coupon' => $coupon->id]] : [],
            'customer' => $customer->id,
            // 'customer_email' => auth()->user()->email,
        ]);

        return $checkout_session;
    }


    //cin 7 wholesale payments
    public function cin7_payments($order_reference) {

        $wholesale_stripe_settings = SettingHelper::getSetting('enable_wholesale_stripe_checkout');

        if (empty($wholesale_stripe_settings) || strtolower($wholesale_stripe_settings) != 'yes') {
            return abort(404)->with('error', 'Please contact the administrator');
        }


        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');


        $cin7api_key_for_other_jobs =  ApiKeys::where('password', $cin7_auth_password)
        ->where('is_active', 1)
        ->where('is_stop' , 0)
        ->first();

        $api_key_id = null;
        
        if (!empty($cin7api_key_for_other_jobs)) {
            $cin7_auth_username = $cin7api_key_for_other_jobs->username;
            $cin7_auth_password = $cin7api_key_for_other_jobs->password;
            $thresold = $cin7api_key_for_other_jobs->threshold;
            $request_count = !empty($cin7api_key_for_other_jobs->request_count) ? $cin7api_key_for_other_jobs->request_count : 0;
            $api_key_id = $cin7api_key_for_other_jobs->id;
        } else {
            $this->error('Cin7 API Key not found or inactive');
            return false;
        }


        $calculate_tax = 0;
        $delivery_cost = 0;
        $client = new \GuzzleHttp\Client();

        $res = $client->request(
            'GET', 
            'https://api.cin7.com/api/v1/SalesOrders/'. $order_reference,
            [
                'auth' => [
                    $cin7_auth_username,
                    $cin7_auth_password
                ]                    
            ]
        );

        UtilHelper::saveEndpointRequestLog('Get Sales Order','https://api.cin7.com/api/v1/SalesOrders', $api_key_id);
        
        $order = $res->getBody()->getContents();
        $order = json_decode($order);
        if (empty($order) || empty($order->lineItems)) {
            return abort(404);
        }

        $first_name = !empty($order->firstName) ? $order->firstName : '';
        $last_name = !empty($order->lastName) ? $order->lastName : '';
        $email = !empty($order->email) ? $order->email : '';
        $company = !empty($order->company) ? $order->company : '';
        $order_id = $order->id;
        $line_items = $order->lineItems;
        $currency_rate = $order->currencyRate;
        $orderTax = !empty($order->taxRate) ? ($order->taxRate * 100) : 0;
        if (!empty($orderTax)) {
            $calculate_tax = $order->productTotal * ($orderTax / 100);
        } else {
            $calculate_tax = 0;
        }
        $freightTotal = !empty($order->freightTotal) ? $order->freightTotal: 0;
        $product_prices = [];
        
        $stripe = new \Stripe\StripeClient(config('services.stripe.wholesale_secret'));
        $customer = $stripe->customers->create([
            'name' => $first_name . ' ' . $last_name,
            'email' => $email,
            'metadata' => [
                'order_id' => $order->id, 
                'reference' => $order->reference,
            ]
        ]);
        
        foreach ($line_items as $line_item) {
            // dd(round(($line_item->unitPrice * $currency_rate) * 100, 2));
            $products = $stripe->products->create([
                'name' => $line_item->name
            ]);
            $productPrice = $stripe->prices->create([
                'unit_amount' => round(($line_item->unitPrice * $currency_rate) * 100),
                'currency' => 'usd',
                'product' => $products->id,
                'metadata' => [
                    'quantity'=> $line_item->qty
                ]
            ]);
            array_push($product_prices, $productPrice);
        }
        // if (!empty($calculate_tax) && floatval($calculate_tax) > 0) {
        //     $formatted_tax = number_format($calculate_tax, 2);
        //     $formatted_tax_rate = str_replace(',', '', $formatted_tax);
        //     $formatted_tax_value = number_format(($formatted_tax_rate * 100) , 2);
        //     $tax_value = str_replace(',', '', $formatted_tax_value);
        //     $products_tax= $stripe->products->create([
        //         'name' => 'Tax',
        //     ]);

        //     $taxproductPrice = $stripe->prices->create([
        //         'unit_amount_decimal' => $tax_value,
        //         'currency' => 'usd',
        //         'product' => $products_tax->id
        //     ]);
        // }

        if (!empty($calculate_tax) && floatval($calculate_tax) > 0) {
            $tax_value = (int)round($calculate_tax * 100); // Convert to cents and integer
            $products_tax = $stripe->products->create([
                'name' => 'Tax',
            ]);
        
            $taxproductPrice = $stripe->prices->create([
                'unit_amount' => $tax_value, // Use integer value
                'currency' => 'usd',
                'product' => $products_tax->id,
            ]);
        }
        
        

        for ($i = 0; $i <= count($product_prices) - 1; $i++){
            $items[] = [
                'price' => $product_prices[$i]->id,
                'quantity' => $product_prices[$i]['metadata']['quantity'],
            ];  
        }
        if (!empty($orderTax) && floatval($orderTax) > 0) {
            $items[] = [
                'price' => $taxproductPrice->id,
                'quantity' => '1',
            ];
        }


        // if (!empty($freightTotal) && $freightTotal > 0) {
        //     $shipment_price = number_format(($freightTotal * 100) , 2);
        //     $shipment_value = str_replace(',', '', $shipment_price);
        //     $shipment_product = $stripe->products->create([
        //         'name' => 'Shipment',
        //     ]);
        //     $shipment_product_price = $stripe->prices->create([
        //         'unit_amount_decimal' => $shipment_value,
        //         'currency' => 'usd',
        //         'product' => $shipment_product->id
        //     ]);
        //     $items[] = [
        //         'price' => $shipment_product_price->id,
        //         'quantity' => '1',
        //     ];
        // }

        // Freight/shipping calculation
        if (!empty($freightTotal) && $freightTotal > 0) {
            $shipment_value = (int)round($freightTotal * 100); // Convert to cents and integer
            $shipment_product = $stripe->products->create([
                'name' => 'Shipment',
            ]);
        
            $shipment_product_price = $stripe->prices->create([
                'unit_amount' => $shipment_value, // Use integer value
                'currency' => 'usd',
                'product' => $shipment_product->id,
            ]);
            $items[] = [
                'price' => $shipment_product_price->id,
                'quantity' => 1, // Ensure quantity is an integer
            ];
        }

        

        $line_items = [
            'line_items' => 
            [
                $items
            ]
        ];
        

        try {
            $checkout_session = $stripe->checkout->sessions->create([
                'success_url' => url('/cin7/payment/success/'. $order_id ) . '?session_id={CHECKOUT_SESSION_ID}',
                'line_items' => $items,
                'mode' => 'payment',
                'customer' => $customer->id,
                'payment_intent_data'=> [
                    'metadata' => [
                        'order_id'=> $order->reference,
                    ]
                ],

                // 'payment_method_configuration' => config('services.cin7.wholesale_payment_configuration'), 
                'payment_method_types'=> ['us_bank_account']
            ]);
            return redirect($checkout_session->url);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    public function cin7_payments_success(Request $request , $order_id) {

        $session_id = $request->query('session_id');
        $stripe = new \Stripe\StripeClient(config('services.stripe.wholesale_secret'));
        $get_line_items = [];
    
        try {

            $session = $stripe->checkout->sessions->retrieve($session_id);
            $line_items  = $stripe->checkout->sessions->allLineItems(
                $session_id,
                []
            );

            if (!empty($line_items->data)) {
                foreach ($line_items->data as $line_item) {
                    $get_line_items[] = $line_item;
                }
            }
            $customer = $stripe->customers->retrieve($session->customer);
            $order_id = $customer->metadata->order_id;
            $order_reference = $customer->metadata->reference;
            $email = !empty($customer->email) ? $customer->email : '';
            $name = !empty($customer->name) ? $customer->name : '';
            $total_amount = 0;
            foreach ($get_line_items as $order_item) {
                $total_amount += $order_item->amount_total / 100;
                $items[] = [
                    'name' => $order_item->description,
                    'price' => $order_item->amount_total / 100,
                    'quantity' => $order_item->quantity,
                ];
            }

            $wholesale_invoice_email_1 = SettingHelper::getSetting('wholesale_invoice_email_1'); 
            $wholesale_invoice_email_2 = SettingHelper::getSetting('wholesale_invoice_email_2');
            $wholesale_invoice_email_3 = SettingHelper::getSetting('wholesale_invoice_email_3');


            $invoice = $session;
            
            $payment_intent = $stripe->paymentIntents->retrieve($session->payment_intent);
            
            $charge = $stripe->charges->retrieve($payment_intent->latest_charge);
            
            $balance_transaction = $stripe->balanceTransactions->retrieve($charge->balance_transaction);

            // Step 4: Extract the processing fee from the Balance Transaction's fee_details
            $processing_fee = 0;
            foreach ($balance_transaction->fee_details as $fee_detail) {
                if ($fee_detail->type === 'stripe_fee') {
                    $processing_fee = $fee_detail->amount; // Amount is in cents
                    break;
                }
            }

            // Convert the processing fee from cents to dollars
            $processing_fee_in_dollars = $processing_fee / 100;


            $customer_email = $email;
            $order_id = $order_id;
            $customer_name = $name;

            if (!empty($wholesale_invoice_email_1)) {
                MailHelper::sendWholesalePaymentInvoice('emails.wholesale_stripe_invoice', $session, $order_id, $customer_name, $customer_email, $wholesale_invoice_email_1, $processing_fee_in_dollars, $get_line_items , $order_reference);
            }
    
            if (!empty($wholesale_invoice_email_2)) {
                MailHelper::sendWholesalePaymentInvoice('emails.wholesale_stripe_invoice', $session, $order_id, $customer_name, $customer_email, $wholesale_invoice_email_2, $processing_fee_in_dollars, $get_line_items , $order_reference);
            }
    
            if (!empty($wholesale_invoice_email_3)) {
                MailHelper::sendWholesalePaymentInvoice('emails.wholesale_stripe_invoice', $session, $order_id, $customer_name, $customer_email, $wholesale_invoice_email_3, $processing_fee_in_dollars, $get_line_items , $order_reference);
            }


            // Add payment to Cin7
            
            // try {
            //     // Get authentication credentials
            //     $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
            //     $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');


            //     $createdTimestamp = $session->created;

            //     // Convert the timestamp to a Carbon instance
            //     $createdDate = Carbon::createFromTimestamp($createdTimestamp);

            //     // Format the date to ISO 8601
            //     $api_order_sync_date = $createdDate->format('Y-m-d\TH:i:s\Z');
            
            //     // API URL
            //     $url = 'https://api.cin7.com/api/v1/Payments';
            
            //     // Request payload
            //     $update_array = [
            //         [
            //             'orderId' => $order_id,
            //             'method' => 'Credit Card CPP',
            //             'paymentDate' => $api_order_sync_date,
            //             'amount' => floatval($total_amount),
            //         ]
            //     ];
            
            //     // Guzzle client
            //     $client = new \GuzzleHttp\Client();
            
            //     // Prepare the request
            //     $response = $client->post($url, [
            //         'auth' => [$cin7_auth_username, $cin7_auth_password],
            //         'json' => $update_array,
            //         'headers' => [
            //             'Content-Type' => 'application/json',
            //         ],
            //     ]);
            
            //     // Parse and handle the response
            //     $responseBody = json_decode($response->getBody()->getContents(), true);
            
            //     if ($response->getStatusCode() === 200) {
            //         // Log successful response
            //         Log::info('Payment synced successfully', ['response' => $responseBody]);
            //     } else {
            //         // Log non-200 responses
            //         Log::error('Payment API response error', [
            //             'status' => $response->getStatusCode(),
            //             'response' => $responseBody,
            //         ]);
            //     }
            // } catch (\Exception $e) {
            //     // Log general exceptions
            //     Log::error('Cin7 Payment API Error: ' . $e->getMessage(), [
            //         'order_id' => $order_id,
            //     ]);
            // }

            return view('cin7.invoice', [
                'order_reference' => $order_reference,
                'customer_name' => $name,
                'customer_email' => $email,
                'items' => $items,
                'total' => $total_amount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showcin7Invoice($order_id) {
        $order = ApiOrder::with('apiOrderItem', 'apiOrderItem.product')->where('id', $order_id)->first();
        if (empty($order)) {
            return abort(404);
        }
        $order_reference = $order->reference;

        $order_contact = Contact::where('contact_id', $order->memberId)->orWhere('parent_id', $order->memberId)->first();
        $first_name = $order_contact->firstName ?? '';
        $last_name = $order_contact->lastName ?? '';
        $email = $order_contact->email ?? '';

        $items = [];
        $subtotal = 0;

        foreach ($order->apiOrderItem as $order_item) {
            $unit_price = $order_item->price * 100;
            $total = $unit_price * $order_item->quantity;
            $subtotal += $total;

            $items[] = [
                'name' => $order_item->product->name,
                'quantity' => $order_item->quantity,
                'unit_price' => $unit_price,
                'total' => $total,
            ];
        }

        $tax = $order->tax_rate * 100;
        $shipment = $order->shipment_price * 100;
        $total = $subtotal + $tax + $shipment;


        return view('cin7.invoice', [
            'order_reference' => $order_reference,
            'customer_name' => trim($first_name . ' ' . $last_name),
            'customer_email' => $email,
            'items' => $items,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipment' => $shipment,
            'total' => $total,
        ]);
    }


    public function mark_order_shipped(Request $request) {
        $order_id = $request->order_id;
        $order = ApiOrder::where('id', $order_id)->first();
        if (empty($order)) {
            return redirect()->back()->with('error', 'Order not found');
        }

        $order->is_shipped = 1;
        $order->label_created = 1;
        $order->save();

        return redirect()->back()->with('success', 'Order marked as shipped');
    }


    public function payouts(Request $request)
    {
        $payouts_query = Payout::with('payoutBalances')->orderBy('arrive_date', 'desc');

        // Search by payout ID or destination name
        if ($request->has('search') && !empty($request->search)) {
            $payouts_query->where(function ($query) use ($request) {
                $query->where('amount', 'like', '%' . $request->search . '%');
            });
        }

        // Date filtering
        if ($request->has('last_14_days')) {
            $payouts_query->where('arrive_date', '>=', now()->subDays(14));
        } elseif ($request->has('this_month')) {
            $payouts_query->whereYear('arrive_date', now()->year)
                        ->whereMonth('arrive_date', now()->month);
        } elseif ($request->has('last_month')) {
            $payouts_query->whereYear('arrive_date', now()->subMonth()->year)
                        ->whereMonth('arrive_date', now()->subMonth()->month);
        } elseif ($request->has('all_time')) {
            // No filter needed, show all records
        }

        // Paginate results
        $payouts = $payouts_query->get();

        return view('admin.payouts.index', compact('payouts'));
    }



    public function payouts_details($id) {
        $payout_balances = PayoutBalance::where('payout_id', $id)->get(); 

        return view('admin.payouts.details', compact('payout_balances' , 'id'));
    }


    public function transactions_export(Request $request,  $id) {
        $payout = Payout::findOrFail($id);

        $hide_radar = $request->boolean('hide_radar', false);
        $hide_chargebacks = $request->boolean('hide_Chargeback', false);

        // Set file name dynamically
        $fileName = "Payout_Transactions_{$id}.xlsx";

        // Return the Excel file
        return Excel::download(new PayoutBalanceExport($id, $hide_radar, $hide_chargebacks), $fileName);


    }

    



    // public function payout_details(Request $request)
    // {
    //     $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
    //     $amount = $request->amount;

    //     if (empty($amount)) {
    //         return redirect()->back()->with('error', 'Amount is required');
    //     }

    //     try {
    //         // Convert amount to cents for the query
    //         $amountInCents = $amount * 100;

    //         // Fetch all payouts (filter by amount if required)
    //         $allPayouts = $stripe->payouts->all([
    //             'amount' => $amountInCents, // Convert to cents
    //         ]);

    //         // Prepare an array to hold the payout and transaction details
    //         $csvData = [];

    //         // Header row
    //         $csvData[] = [
    //             'Type', 'ID', 'Created', 'Description', 'Amount', 'Currency',
    //             'Converted Amount', 'Fees', 'Net', 'Converted Currency', 'Details',
    //             'Customer ID', 'Customer Email', 'Customer Name', 'order_id',
    //         ];

    //         // Loop through all the payouts to find the one that matches the amount
    //         foreach ($allPayouts as $payout) {
    //             // If the payout amount doesn't match, skip it
    //             if (abs($payout->amount / 100 - $amount) > 0.01) {
    //                 continue;
    //             }

    //             // Retrieve the payout details
    //             $payout = $stripe->payouts->retrieve($payout->id);

    //             $allBalanceTransactions = $stripe->balanceTransactions->all([
    //                 'payout' => $payout->id,
    //             ]);

    //             foreach ($allBalanceTransactions->autoPagingIterator() as $bt) {
    //                 // Skip if type is 'payout'
    //                 if ($bt->type === 'payout') {
    //                     continue;
    //                 }

    //                 // Retrieve transaction details related to the payout
    //                 $type        = $bt->type;
    //                 $id          = $bt->id;
    //                 $created     = date('m/d/Y H:i', $bt->created);
    //                 $description = $bt->description ?? '';
    //                 $amount      = $bt->amount / 100; // Convert from cents
    //                 $currency    = $bt->currency;
    //                 $fees        = $bt->fee / 100;   // Total fees
    //                 $net         = $bt->net / 100;   // Net amount
    //                 $convertedAmount   = $amount;   // If you need conversion logic, add it
    //                 $convertedCurrency = $currency;

    //                 // Initialize variables for customer and order details
    //                 $customerId    = '';
    //                 $customerEmail = '';
    //                 $customerName  = '';
    //                 $orderId       = '';

    //                 if ($bt->source) {
    //                     // Switch based on $type
    //                     switch ($type) {
    //                         case 'charge':
    //                             $charge = $stripe->charges->retrieve($bt->source);
    //                             if ($charge->customer) {
    //                                 $customerId = $charge->customer;
    //                                 $customer = $stripe->customers->retrieve($charge->customer);
    //                                 $customerEmail = $customer->email ?? '';
    //                                 $customerName = $customer->name ?? '';
    //                             }
    //                             $orderId = $charge->metadata->order_id ?? '';
    //                             break;
    //                     }
    //                 }

    //                 // Build each CSV row for the payout
    //                 $csvData[] = [
    //                     'Type' => ucfirst($type),
    //                     'ID' => $id,
    //                     'Created' => $created,
    //                     'Description' => $description,
    //                     'Amount' => $amount,
    //                     'Currency' => strtoupper($currency),
    //                     'Converted Amount' => $convertedAmount,
    //                     'Fees' => $fees,
    //                     'Net' => $net,
    //                     'Converted Currency' => strtoupper($convertedCurrency),
    //                     'Details' => $customerEmail,
    //                     'Customer ID' => $customerId,
    //                     'Customer Email' => $customerEmail,
    //                     'Customer Name' => $customerName,
    //                     'order_id (metadata)' => $orderId,
    //                 ];
    //             }
    //         }

    //         // Convert $csvData to CSV format
    //         $filename = 'stripe_payout_'.$amount.'_'.date('Y-m-d').'.csv';
    //         $handle = fopen($filename, 'w');

    //         // Put each row into the CSV
    //         foreach ($csvData as $row) {
    //             fputcsv($handle, $row);
    //         }
    //         fclose($handle);

    //         // Store the file and return as download
    //         Storage::disk('local')->put($filename, file_get_contents($filename));

    //         return response()->download($filename)->deleteFileAfterSend(true);

    //     } catch (\Exception $e) {
    //         // Handle error if payout ID is not found
    //         return response()->json(['error' => 'Payout not found or invalid: '.$e->getMessage()], 400);
    //     }
    // }




    
}
