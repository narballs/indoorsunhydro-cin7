<?php

namespace App\Http\Controllers;

use App\Helpers\DimensionHelper;
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
use App\Helpers\DistanceCalculator;
use App\Helpers\LabelHelper;
use App\Helpers\ShippingHelper;
use App\Helpers\UtilHelper;
use App\Models\ApiErrorLog;
use App\Models\ApiKeys;
use App\Models\BuyList;
use App\Models\BuyListShippingAndDiscount;
use App\Models\ContactsAddress;
use App\Models\NewsletterSubscription;
use App\Models\OrderReminder;
use App\Models\ShippingQuoteSetting;
use App\Models\SpecificAdminNotification;
use App\Services\FacebookConversionService;
use Illuminate\Support\Str;

use function PHPSTORM_META\type;

class CheckoutController extends Controller
{
    
    public function index(Request $request) {
        // $api_key = env('Address_validator_api_key');
        // $calculator = new DistanceCalculator($api_key);
        // $distance = $calculator->calculate_distance('95826', '94102');
        // if ($distance == 'Error') {
        //     echo "Error in calculating distance";exit;
        // } else {
        //     echo "Distance between ZIPCODE1 and ZIPCODE2 is {$distance} kilometers.";exit;
        // }
        // echo "Distance between ZIPCODE1 and ZIPCODE2 is {$distance} kilometers.";exit;
        $out_of_stock_items = [];
        $original_items_quantity = [];

        $cart_items = UserHelper::switch_price_tier($request);

        if (empty($cart_items) || count($cart_items) == 0) {
            return redirect('/')->with('error', 'Your cart for the selected company is empty! Please add some items to your cart before proceeding to checkout.');
        }


        $user_id = Auth::id();
        $get_wholesale_contact_id = null;
        $get_wholesale_terms = null;
        $session_contact = Session::get('contact_id') != null ? Session::get('contact_id') : null;
        
            
        // Get wholesale_contact
        if (!empty($user_id)) {
            $wholesale_contact = Contact::where('user_id', auth()->user()->id)
            ->where('contact_id', $session_contact)
            ->orWhere('secondary_id', $session_contact)
            ->first();

            if (!empty($wholesale_contact)) {
                if ($wholesale_contact->is_parent == 1 && !empty($wholesale_contact->contact_id)) {
                    $get_wholesale_contact_id = $wholesale_contact->contact_id;
                    $get_wholesale_terms = $wholesale_contact->paymentTerms;
                } else {
                    $wholesale_contact_child = Contact::where('user_id', $user_id)
                        ->whereNull('contact_id')
                        ->where('is_parent', 0)
                        ->where('secondary_id', $session_contact)
                        ->first();
                    
                    // Ensure $wholesale_contact_child is not null before accessing parent_id
                    $get_wholesale_contact_id = $wholesale_contact_child ? $wholesale_contact_child->parent_id : null;
                    $get_wholesale_terms = $wholesale_contact_child->paymentTerms;
                }
            }
        } else {
            $wholesale_contact = null;
        }

        foreach ($cart_items as $cart_item) {
            $product_options = ProductOption::with('products')
                ->where('product_id', $cart_item['product_id'])
                ->where('option_id', $cart_item['option_id'])
                ->get();

            foreach ($product_options as $product_option) {
                // Check if the product is out of stock
                if ($product_option->stockAvailable < 1) {
                    $out_of_stock_items[] = [  // Append to the array
                        'product_primary_id' => $cart_item['qoute_id'],
                        'product_id' => $cart_item['product_id'],
                        'option_id' => $cart_item['option_id'],
                        'product_name' => $cart_item['name'],
                        'sku' => $cart_item['code'],
                        'quantity' => $cart_item['quantity'],
                        'stock_available' => $product_option->stockAvailable,
                    ];
                }

                // Check if the available stock is less than the required quantity
                if ($product_option->stockAvailable < $cart_item['quantity'] && $product_option->stockAvailable > 0) {
                    $original_items_quantity[] = [  // Append to the array
                        'product_primary_id' => $cart_item['qoute_id'],
                        'product_id' => $cart_item['product_id'],
                        'option_id' => $cart_item['option_id'],
                        'product_name' => $cart_item['name'],
                        'sku' => $cart_item['code'],
                        'quantity' => $cart_item['quantity'],
                        'stock_available' => $product_option->stockAvailable,
                    ];
                }
            }
        }

        // dd($get_wholesale_terms);

        if (strtolower($get_wholesale_terms) === 'pay in advanced') {
            if (!empty($out_of_stock_items) || (!empty($original_items_quantity))) {
                return redirect()->route('cart')
                    ->with('error', 'Some item(s) in your cart have insufficient stock. Please update or remove it from your cart to proceed.');
            }
        }

        // if (!empty($out_of_stock_items) || (!empty($original_items_quantity) && (strtolower($get_wholesale_terms) === 'pay in advanced'))) {
        //     return redirect()->route('cart')
        //         // ->with([
        //         //     'out_of_stock_items' => $out_of_stock_items,
        //         //     'original_items_quantity' => $original_items_quantity
        //         // ]);
        //         ->with('error', 'Some item(s) in your cart have insufficient stock. Please update or remove it from your cart to proceed.');
        // }

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
        $selected_company = Session::get('contact_id');
        if (!$selected_company) {
            Session::flash('message', "Please select a company for which you want to make an order for");
            return redirect('/cart/');
        }
        $contact = Contact::where('user_id', $user_id)
            ->where('status', 1)
            ->where('contact_id', $selected_company)
            ->orWhere('secondary_id', $selected_company)
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
        $shipment_error = 0;
        $re_order_id = Session::get('re_order_id');
        $enable_free_shipping_banner = AdminSetting::where('option_name' , 'enable_free_shipping_banner')->first();
        $enable_free_shipping_banner_text = AdminSetting::where('option_name' , 'enable_free_shipping_banner_text')->first();
        $enable_extra_shipping_value = false;
        $add_extra_70_to_shipping = AdminSetting::where('option_name', 'add_extra_70_to_shipping')->first();
        if (!empty($add_extra_70_to_shipping) && strtolower($add_extra_70_to_shipping->option_value) == 'yes') {
            $enable_extra_shipping_value = true;
        } else {
            $enable_extra_shipping_value = false;
        }
        $discount_code = null;
        $states = UsState::all();
        $cart_items = UserHelper::switch_price_tier($request);
        $cart_total = 0;
        $charge_shipment_to_customer = 0;
        $shipment_for_selected_category  = false;
        if (!empty($cart_items)) {
            foreach ($cart_items as $cart_item) {
                $product = Product::where('product_id' , $cart_item['product_id'])->first();
                if (!empty($product) && !empty($product->categories) && $product->category_id != 0) {
                    if (strtolower($product->categories->name) === 'grow medium') {
                        $shipment_for_selected_category = true;
                    }
                    elseif (!empty($product->categories->parent) && !empty($product->categories->parent->name) && strtolower($product->categories->parent->name) === 'grow medium')  {
                        $shipment_for_selected_category = true;
                    }
                    else {
                        $shipment_for_selected_category = false;
                    }
                } else {
                    $shipment_for_selected_category = false;
                }
                $row_price = $cart_item['quantity'] * $cart_item['price'];
                $cart_total = $row_price + $cart_total;
            }
        }
        
        if (!auth()->user()) {
            $tax_class = TaxClass::where('is_default', 1)->first();
            $buy_list_id = session()->get('buy_list_id');
            $buyList = BuyList::with('shipping_and_discount')->where('id', $buy_list_id)->first();
            $shipping_cost = $buyList->shipping_and_discount->shipping_cost ?? 0;
            $shipment_price = $shipping_cost;
            $discount = $buyList->shipping_and_discount->discount ?? 0;
            $discount_type = $buyList->shipping_and_discount->discount_type ?? null;
            $buy_list_discount_calculated = $buyList->shipping_and_discount->discount_calculated ?? 0;
            if (!empty($buy_list_id)) {
                $buyListData = true;
            } else {
                $buyListData = false;
            }
            return view ('checkout.checkout_without_login' ,compact('states','cart_total','re_order_id','buy_list_discount_calculated','buyListData', 'cart_items' , 'tax_class' , 'shipment_price' , 'shipping_cost' , 'discount' , 'discount_type'));
        }
        $user_id = auth()->user()->id;
        $selected_company = Session::get('contact_id');
        if (!$selected_company) {
            Session::flash('message', "Please select a company for which you want to make an order for");
            return redirect('/cart');
        }
        $contact = Contact::where('user_id', $user_id)
            ->where('status', 1)
            ->where('contact_id', $selected_company)
            ->orWhere('secondary_id', $selected_company)
            ->with('states')
            ->with('cities')
            ->first();
        // $cart_items = session()->get('cart');
        $current_date = Carbon::now()->format('Y-m-d');
        
        $products_weight = 0;
        $product_width = 0;
        $product_height = 0;
        $product_length = 0;
        $sub_total_of_cart = 0;
        $products_lengths = [];
        $products_widths = [];
        $products_heights = [];
        $sum_of_length = 0;
        $sum_of_width = 0;
        $productTotal = 0;
        $total_height = 0.0;
        $pot_category_flag = false;
        $HEIGHT_CAP = 30; // 24–36" is typical; tune per your ship boxes

        // Accumulators for COMPRESSED items ONLY (they will grow footprint)
        // $comp_box_L = 0.0; $comp_box_W = 0.0; $comp_box_H = 0.0;      // final compressed footprint+height
        // $comp_layer_L = 0.0; $comp_layer_W = 0.0; $comp_layer_H = 0.0; // current compressed layer 
        // foreach ($cart_items as $cart_item) {
        //     $product = Product::where('product_id' , $cart_item['product_id'])->first();
        //     if (!empty($product) && !empty($product->categories) && $product->category_id != 0) {
        //         if (strtolower($product->categories->name) === 'grow medium') {
        //             $shipment_for_selected_category = true;
        //         }
        //         elseif (!empty($product->categories->parent) && !empty($product->categories->parent->name) && strtolower($product->categories->parent->name) === 'grow medium')  {
        //             $shipment_for_selected_category = true;
        //         } 
        //         else {
        //             $shipment_for_selected_category = false;
        //         }
        //     } else {
        //         $shipment_for_selected_category = false;
        //     }
        //     $sub_total_of_cart += $cart_item['quantity'] * $cart_item['price'];
        //     $productTotal += $cart_item['quantity'] * $cart_item['price'];
        //     $product_options = ProductOption::with('products')->where('product_id', $cart_item['product_id'])->where('option_id' , $cart_item['option_id'])->get();
        //     $pots_category = 'pots & containers';
            
        //     foreach ($product_options as $product_option) {

        //         if (!empty($product_option->products) && !empty($product_option->products->categories) && strtolower($product_option->products->categories->name) === $pots_category) {
        //             $pot_category_flag = true; // KEEP true once set

        //             // keep your existing helper call; we'll fix its internals below
        //             $get_pot_category_dimensions = UserHelper::calculateNestedItemDimensions(
        //                 $product_option,
        //                 $product_option->products,
        //                 $cart_item['quantity'],
        //                 $products_lengths,
        //                 $products_widths,
        //                 $products_heights,
        //                 $product_height,
        //                 $product_width,
        //                 $product_length,
        //                 $products_weight = 0
        //             );

        //         } 
        //         else {
        //             if (!empty($product_option->products)) {
        //                 $qty = (int)$cart_item['quantity'];

        //                 // Prefer option weight; fallback to product weight
        //                 $unitWt = (float)($product_option->optionWeight ?? 0);
        //                 if ($unitWt <= 0 && isset($product_option->products->weight)) {
        //                     $unitWt = (float)$product_option->products->weight;
        //                 }

        //                 // Rotate so L ≥ W ≥ H
        //                 $pLen = (float)($product_option->products->length ?? 0);
        //                 $pWid = (float)($product_option->products->width  ?? 0);
        //                 $pHei = (float)($product_option->products->height ?? 0);
        //                 $dims = [$pLen, $pWid, $pHei];
        //                 rsort($dims, SORT_NUMERIC);
        //                 $L = $dims[0]; $W = $dims[1]; $H = $dims[2];

        //                 // If compressed -> use compressed, else normal stack
        //                 $isCompressed = (bool) ($product_option->products->is_compressed ?? false);
        //                 if ($isCompressed) {
        //                     ShippingHelper::accumulateCompressedItem(
        //                         $qty, $L, $W, $H,
        //                         $comp_layer_L, $comp_layer_W, $comp_layer_H,
        //                         $comp_box_L,   $comp_box_W,   $comp_box_H,
        //                         30.0,  // $heightCap (tune if needed)
        //                         0.6,   // $ratio
        //                         0.25,  // $floor
        //                         12     // $searchCap
        //                     );
                            

        //                 } else {
        //                     // normal (non-compressed) stacking: stack smallest edge
        //                     $products_lengths[] = $L;
        //                     $products_widths[]  = $W;
        //                     $total_height      += $H * $qty;
        //                 }

        //                 // add weight ONCE
        //                 $products_weight += $unitWt * $qty;
        //             }
        //         }
        //     }

        // }

        // ShippingHelper::finalizeCompressedBox(
        //     $comp_layer_L, $comp_layer_W, $comp_layer_H,
        //     $comp_box_L,   $comp_box_W,   $comp_box_H
        // );

       

        // // Non-compressed footprint/height from your accumulators
        // $noncomp_L = !empty($products_lengths) ? max($products_lengths) : 0.0;
        // $noncomp_W = !empty($products_widths)  ? max($products_widths)  : 0.0;
        // $noncomp_H = (float)$total_height;

        // // Merge with pots (if any) AND compressed box
        // if (!empty($pot_category_flag) && !empty($get_pot_category_dimensions)) {
        //     $potL  = (float)($get_pot_category_dimensions['products_lengths'] ?? 0);
        //     $potW  = (float)($get_pot_category_dimensions['products_widths']  ?? 0);
        //     $potH  = (float)($get_pot_category_dimensions['product_height']   ?? 0);
        //     $potWT = (float)($get_pot_category_dimensions['products_weight']  ?? 0);

        //     // Footprint is the max across compressed, non-compressed, pots
        //     $product_length = max($comp_box_L, $noncomp_L, $potL);
        //     $product_width  = max($comp_box_W, $noncomp_W, $potW);

        //     // Heights stack
        //     $product_height = $comp_box_H + $noncomp_H + $potH;

        //     $actual_total   = $products_weight + $potWT; // add pots' actual weight
        // } else {
        //     $product_length = max($comp_box_L, $noncomp_L);
        //     $product_width  = max($comp_box_W, $noncomp_W);
        //     $product_height = $comp_box_H + $noncomp_H;

        //     $actual_total   = $products_weight; // already summed in loop
        // }


        // $DIM_DIVISOR = 166; // change if your carrier uses a different divisor
        // $dim_weight = ($product_length > 0 && $product_width > 0 && $product_height > 0)
        //     ? (($product_length * $product_width * $product_height) / $DIM_DIVISOR)
        //     : 0.0;

        // $billable = $actual_total;

        // // ----- Oversize clamp (keep your policy) -----
        // $girth = 2 * ($product_width + $product_height);
        // if ($girth > 165 && $billable < 150) {
        //     $billable = 151;
        // }

        // // This is the weight you should send to ShipStation
        // $products_weight = $billable;



        $comp_box_L = 0.0; $comp_box_W = 0.0; $comp_box_H = 0.0;
        $comp_layer_L = 0.0; $comp_layer_W = 0.0; $comp_layer_H = 0.0;
        $main_product_weight = 0;

        $allDims = []; 
        $get_pot_category_dimensions = null;

        
        foreach ($cart_items as $cart_item) {
            $product = Product::where('product_id' , $cart_item['product_id'])->first();

            // check grow medium category
            if (!empty($product) && !empty($product->categories) && $product->category_id != 0) {
                if (strtolower($product->categories->name) === 'grow medium') {
                    $shipment_for_selected_category = true;
                } elseif (!empty($product->categories->parent) && strtolower($product->categories->parent->name) === 'grow medium') {
                    $shipment_for_selected_category = true;
                } else {
                    $shipment_for_selected_category = false;
                }
            } else {
                $shipment_for_selected_category = false;
            }

            $sub_total_of_cart += $cart_item['quantity'] * $cart_item['price'];
            $productTotal      += $cart_item['quantity'] * $cart_item['price'];

            $product_options = ProductOption::with('products')
                ->where('product_id', $cart_item['product_id'])
                ->where('option_id' , $cart_item['option_id'])
                ->get();

            $pots_category = 'pots & containers';

            foreach ($product_options as $product_option) {
                $main_qty = (int)$cart_item['quantity'];
                $main_option_weight = (float)$product_option->optionWeight;

                // 🚨 Validate this product immediately
                if ($main_option_weight <= 0) {
                    return redirect()->back()->with(
                        'error',
                        "Product \"{$product_option->products->name}\" is missing a weight value. 
                        Shipping cannot be exactly calculated. Please contact support to update the product details."
                    );
                }

                if (!empty($product_option->products) && !empty($product_option->products->categories) 
                    && strtolower($product_option->products->categories->name) === $pots_category) {

                    $pot_category_flag = true;

                    $get_pot_category_dimensions = UserHelper::calculateNestedItemDimensions(
                        $product_option,
                        $product_option->products,
                        $cart_item['quantity'],
                        $products_lengths,
                        $products_widths,
                        $products_heights,
                        $product_height,
                        $product_width,
                        $product_length,
                        $products_weight = 0
                    );

                } else {
                    if (!empty($product_option->products)) {
                        [$L, $W, $H, $Wt] = DimensionHelper::resolve($product_option, $main_qty);

                        $isCompressed = (bool)($product_option->products->is_compressed ?? false);

                        if ($isCompressed) {
                            ShippingHelper::accumulateCompressedItem(
                                $main_qty, $L, $W, $H,
                                $comp_layer_L, $comp_layer_W, $comp_layer_H,
                                $comp_box_L,   $comp_box_W,   $comp_box_H,
                                30.0, 0.6, 0.25, 12
                            );
                        } else {
                            $allDims[] = [$L, $W, $H, $Wt];
                        }
                    }
                }
            }
        }

        // finalize compressed box
        ShippingHelper::finalizeCompressedBox(
            $comp_layer_L, $comp_layer_W, $comp_layer_H,
            $comp_box_L,   $comp_box_W,   $comp_box_H
        );

        // Non-compressed footprint/height
        // merge non-compressed SKUs
        [$noncomp_L, $noncomp_W, $noncomp_H, $noncomp_Wt] = DimensionHelper::mergeCarton($allDims);

        // Merge with pots (if any) AND compressed box
        if (!empty($pot_category_flag) && !empty($get_pot_category_dimensions)) {
            $potL  = (float)($get_pot_category_dimensions['products_lengths'] ?? 0);
            $potW  = (float)($get_pot_category_dimensions['products_widths']  ?? 0);
            $potH  = (float)($get_pot_category_dimensions['product_height']   ?? 0);
            $potWT = (float)($get_pot_category_dimensions['products_weight']  ?? 0);

            $product_length = max($comp_box_L, $noncomp_L, $potL);
            $product_width  = max($comp_box_W, $noncomp_W, $potW);
            $product_height = $comp_box_H + $noncomp_H + $potH;

            // ✅ total physical weight always includes qty
            $physical_weight = $noncomp_Wt + $potWT;

        } else {
            $product_length = max($comp_box_L, $noncomp_L);
            $product_width  = max($comp_box_W, $noncomp_W);
            $product_height = $comp_box_H + $noncomp_H;

            // ✅ weight according to qty
            $physical_weight = $noncomp_Wt;
        }

        // dimensional weight
        $DIM_DIVISOR = 166; // use 139 for UPS/FedEx
        $dim_weight = ($product_length > 0 && $product_width > 0 && $product_height > 0)
            ? (($product_length * $product_width * $product_height) / $DIM_DIVISOR)
            : 0.0;

        // billable weight (max of actual vs dim)
        $billable = max($physical_weight, $dim_weight);

        // oversize rule
        $girth = 2 * ($product_width + $product_height);
        if ($girth > 165 && $billable < 150) {
            $billable = 151;
        }

        // final weight for ShipStation
        $products_weight = $billable;


        

        $extra_shipping_value = AdminSetting::where('option_name', 'extra_shipping_value')->first();
        if ($enable_extra_shipping_value == true && !empty($extra_shipping_value) &&  $products_weight > 150) {
            if ($product_width > 40 || $product_height > 40 || $product_length > 40) {
                $extra_shipping_value = !empty($extra_shipping_value) ? floatval($extra_shipping_value->option_value) : 0;
            } else {
                $extra_shipping_value = 0;
            }
        } else {
            $extra_shipping_value = 0;
        }
        
        if ($contact) {
            $isApproved = $contact->contact_id;
        }
        $zip_code_is_valid = true;

        if (Auth::check() && (!empty($contact->contact_id) || !empty($contact->secondary_id)) && $contact->status == 1) {
            // $tax_class = TaxClass::where('is_default', 1)->first();
            $extra_charges_for_total_over_499 = 0;
            $extra_charges_for_total_over_499_value_setting = AdminSetting::where('option_name', 'extra_charges_for_total_over_499')->first();
            if (!empty($extra_charges_for_total_over_499_value_setting)) {
                $extra_charges_for_total_over_499 = floatval($extra_charges_for_total_over_499_value_setting->option_value);
            } else {
                $extra_charges_for_total_over_499 = 0;
            }
            $allow_discount_for_new_user = false;
            $allow_discount_for_all_customers  =  false;
            $allow_discount_for_specific_customers = false;
            $shipment_prices = [];
            $user_address = null;
            $payment_methods = PaymentMethod::with('options')->get();
            $contact_id = session()->get('contact_id');

            $user = User::where('id', $user_id)->first();
            $all_ids = UserHelper::getAllMemberIds($user);
            $pluck_default_user = Contact::whereIn('id', $all_ids)->where('contact_id' , $contact_id)->first();
            $check_new_user_orders = null;
            $shipping_quotes = ShippingQuote::with('selected_shipping_quote')->get();
            $selected_shipment_quotes = SelectedShippingQuote::with('shipping_quote')->get();
            $admin_area_for_shipping = AdminSetting::where('option_name', 'admin_area_for_shipping')->first();
            // old code
            // $surcharge_settings = SurchargeSetting::where('apply_surcharge', 1)->first(); 
            // new code
            $surcharge_settings = AdminSetting::where('option_name', 'apply_extra_surcharge')->first();
            $surcharge_settings_for_weight_greater_then_150 = AdminSetting::where('option_name', 'surcharge_value_greater_weight')->first();
            $surcharge_type_settings_for_weight_greater_then_150 = AdminSetting::where('option_name', 'surcharge_type_greater_weight')->first();
            if (!empty($contact->contact_id)) {
                $user_address = Contact::where('user_id', $user_id)->where('contact_id' , $contact->contact_id)->first();
                $check_new_user_orders = ApiOrder::where('memberId' , $contact->contact_id)->first();
            } else {
                if (!empty($contact->secondary_id)) {
                    $parent = Contact::where('secondary_id', $contact->secondary_id)->first();
                    $user_address = Contact::where('contact_id', $parent->parent_id)->first();
                    $check_new_user_orders = ApiOrder::where('memberId' , $parent->parent_id)->first();
                }
            }

            //adding first entry for users in contact addresses



            if (empty($user_address) && ($user_address->postalAddress1 == null  && $user_address->postalPostCode == null)) {
                return redirect()->back()->with('address_message', "Please contact support to update your billing address" );
            }



            // adding address from Contacts Address table

            // $check_billing_address = ContactsAddress::where('contact_id', $contact->contact_id)->where('address_type', 'Billing')->first();
            $check_billing_address = ContactsAddress::where('contact_id', $user_address->contact_id)->where('address_type', 'Billing')->first();

            if (empty($check_billing_address)) {
                $create_billing_address = new ContactsAddress([
                    'contact_id' => $user_address->contact_id,
                    'BillingFirstName' => $user_address->firstName,
                    'BillingLastName' => $user_address->lastName,
                    'BillingCompany' => $user_address->company,
                    'BillingAddress1' => !empty($user_address->postalAddress1) ? $user_address->postalAddress1 : $user_address->address1,
                    'BillingAddress2' => !empty($user_address->postalAddress2) ? $user_address->postalAddress2 : $user_address->address2,
                    'BillingCity' => !empty($user_address->postalCity) ? $user_address->postalCity : $user_address->city,
                    'BillingState' => !empty($user_address->postalState) ? $user_address->postalState : $user_address->state,
                    'BillingZip' => !empty($user_address->postalPostCode) ? $user_address->postalPostCode : $user_address->postCode,
                    'BillingCountry' => $user_address->country,
                    'BillingPhone' => $user_address->phone,
                    'is_default' => 1,
                    'address_type' => 'Billing',
                ]);
                $create_billing_address->save();
            }

            

            // $check_shipping_address = ContactsAddress::where('contact_id', $contact->contact_id)->where('address_type', 'Shipping')->first();
            $check_shipping_address = ContactsAddress::where('contact_id', $user_address->contact_id)->where('address_type', 'Shipping')->first();

            if (empty($check_shipping_address)) {
                $create_shipping_address = new ContactsAddress([
                    'contact_id' => $user_address->contact_id,
                    'DeliveryFirstName' => $user_address->firstName,
                    'DeliveryLastName' => $user_address->lastName,
                    'DeliveryCompany' => $user_address->company,
                    'DeliveryAddress1' => !empty($user_address->address1) ? $user_address->address1 : $user_address->postalAddress1,
                    'DeliveryAddress2' => !empty($user_address->address2) ? $user_address->address2 : $user_address->postalAddress2,
                    'DeliveryCity' =>   !empty($user_address->city) ? $user_address->city : $user_address->postalCity,
                    'DeliveryState' => !empty($user_address->state) ? $user_address->state : $user_address->postalState,
                    'DeliveryZip' => !empty($user_address->postCode) ? $user_address->postCode : $user_address->postalPostCode,
                    'DeliveryCountry' => $user_address->country,
                    'DeliveryPhone' => $user_address->phone,
                    'is_default' => 1,
                    'address_type' => 'Shipping',
                ]);
                $create_shipping_address->save();
            }

            $get_user_default_billing_address = ContactsAddress::where('contact_id', $user_address->contact_id)
            ->where('address_type', 'Billing')
            ->where('is_default', 1)
            ->first();

            $get_user_default_shipping_address = ContactsAddress::where('contact_id', $user_address->contact_id)
            ->where('address_type', 'Shipping')
            ->where('is_default', 1)
            ->first();

            $get_all_user_addresses = ContactsAddress::where('contact_id', $user_address->contact_id)->where('address_type', 'Shipping')->where('is_default' , 0)->get();
            $get_all_user_billing_addresses_all = ContactsAddress::where('contact_id', $user_address->contact_id)->where('address_type', 'Billing')->where('is_default' , 0)->get();
            

            $charge_shipment_fee = false;
            if (!empty($user_address) && $user_address->charge_shipping == 1) {
                $charge_shipment_fee = true;
                $charge_shipment_to_customer = 1;
            }

            $custom_tax_rate = AdminSetting::where('option_name'  , 'custom_tax_rate')->first();
            if (!empty($custom_tax_rate) && (strtolower($custom_tax_rate->option_value) == 'yes')) {
                $tax_class = UserHelper::ApplyCustomTaxCheckout($get_user_default_shipping_address);
            } 
            else {

                $tax_class = TaxClass::where('name', $user_address->tax_class)->first();
            }

            $tax_class_none = TaxClass::where('name', 'none')->first();
            $get_tax_rate = 0;  
            if (!empty($tax_class)) {
                $get_tax_rate = $tax_class->rate;
            }
            $matchZipCode = null;
            if (empty($user_address) && ($get_user_default_shipping_address->DeliveryZip != null || $get_user_default_billing_address->BillingZip != null)) {
                $matchZipCode = OperationalZipCode::where('status' , 'active')
                ->where('zip_code', $get_user_default_shipping_address->DeliveryZip)
                ->orWhere('zip_code' , $get_user_default_billing_address->BillingZip)
                ->first();
            }
            
            $check_zip_code_setting = AdminSetting::where('option_name', 'check_zipcode')->where('option_value' , 'Yes')->first();

            if (!empty($check_zip_code_setting) && strtolower($check_zip_code_setting->option_value) == 'yes') {
                $zip_code_is_valid = false;
                $operational_zip_code = OperationalZipCode::where('status' , 'active')
                ->where('zip_code', $get_user_default_shipping_address->DeliveryZip)
                ->orWhere('zip_code' , $get_user_default_billing_address->BillingZip)
                ->first();
                if (!empty($operational_zip_code)) {
                    $zip_code_is_valid = true;
                }
            }
            $shipment_price = 0;
            $shipping_free_over_1000 = 0;
            $calculator = new DistanceCalculator();
            $allow_pickup = 0;
            if (!empty( $get_user_default_shipping_address->DeliveryZip)) {

                $distance = $calculator->calculate_distance('95826', $get_user_default_shipping_address->DeliveryZip);
            } else {
                $distance = null;
            }

            if (empty($distance)) {
                $allow_pickup = 0;
            }
            else {
                if ($distance == 'Error') {
                    $allow_pickup = 0;
                } else {
                    if ($distance <= 200) {
                        $allow_pickup = 1;
                    }
                }
            }

            $sub_total_of_cart = $sub_total_of_cart + ($sub_total_of_cart * $get_tax_rate / 100);
            $free_shipping_state = AdminSetting::where('option_name', 'free_shipping_state')->first();
            if ($shipment_for_selected_category == true) {
                $shipping_free_over_1000 = 0;
            } 
            else {
                if (!empty($free_shipping_state) && !empty($get_user_default_shipping_address->DeliveryState)) {
                    if (
                        ($free_shipping_state->option_value == $get_user_default_shipping_address->DeliveryState 
                        || $get_user_default_shipping_address->DeliveryState == 'CA') 
                        && (strtolower($user_address->paymentTerms ?? '') == 'pay in advanced')
                    ) {
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
            }
            $admin_selected_shipping_quote = [];
            $upgrade_admin_selected_shipping_quote = [];
            $shipstation_shipment_prices = [];
            $surcharge_value = 0;
            $allow_upgrade = false;
            $upgrade_shipment_price = 0;
            $shipping_carrier_code = null;
            $shipping_service_code = null;
            $upgrade_shipping_carrier_code = null;
            $upgrade_shipping_service_code  = null;
            $carrier_code = AdminSetting::where('option_name', 'shipping_carrier_code')->first();
            $service_code = AdminSetting::where('option_name', 'shipping_service_code')->first();
            $carrier_code_2 = AdminSetting::where('option_name', 'shipping_carrier_code_2')->first();
            $service_code_2 = AdminSetting::where('option_name', 'shipping_service_code_2')->first();
            $buy_list_id = session()->get('buy_list_id');
            $buyList = BuyList::with('shipping_and_discount')->where('id', $buy_list_id)->first();
            $shipping_cost = $buyList->shipping_and_discount->shipping_cost ?? 0;
            $discount = $buyList->shipping_and_discount->discount ?? 0;
            $discount_type = $buyList->shipping_and_discount->discount_type ?? null;
            $buyListData = false;   
            // adding shipment rates

            if (!empty($buy_list_id)) {
                $buyListData = true;
            } else {
                $buyListData = false;
            }

            $buyLIst_shipping_cost = 0;
            $buyListdiscount =0;
            $buyListdiscount_type = null;
            $buy_list_discount_calculated =0;


            if ($buyListData == true && !empty($shipping_cost) && floatval($shipping_cost) > 0) {
                $buyLIst_shipping_cost = $buyList->shipping_and_discount->shipping_cost ?? 0;
                $buyListdiscount = $discount;
                $buyListdiscount_type = $discount_type;
                $shipment_price  =  $buyLIst_shipping_cost ;
                $buy_list_discount_calculated = $buyList->shipping_and_discount->discount_calculated ?? 0;
                $shipping_free_over_1000 = 0;              
            }
            
            elseif ($buyListData == true && floatval($shipping_cost) == 0) {
                $buyListdiscount = $buyList->shipping_and_discount->discount ?? 0;
                $buyListdiscount_type = $discount_type;
                $buy_list_discount_calculated = $buyList->shipping_and_discount->discount_calculated ?? 0;
                if ($charge_shipment_fee == true) {
                    $buyListData = true;
                    $buy_list_discount_calculated = $buyList->shipping_and_discount->discount_calculated ?? 0;
                    if ($shipping_free_over_1000 == 1) {
                        $shipment_price = 0;
                        $allow_upgrade = true;
                        $buyListData = true;
                        $buy_list_discount_calculated = $buyList->shipping_and_discount->discount_calculated ?? 0;
                        if (!empty($admin_area_for_shipping) && strtolower($admin_area_for_shipping->option_value) == 'yes' && $allow_upgrade = true) {
                            if ($products_weight > 150) {
                                $upgrade_shipping_carrier_code = $carrier_code_2->option_value;
                                $upgrade_shipping_service_code = $service_code_2->option_value;
                                $get_shipping_rates_greater = $this->get_shipping_rate_greater($products_weight, $user_address , $selected_shipment_quotes ,$shipping_quotes, $shipment_prices, $shipment_price, $product_width, $product_height, $product_length , $get_user_default_shipping_address , $get_user_default_billing_address , $productTotal);
                                
                                if (($get_shipping_rates_greater['shipment_prices'] == null) && $get_shipping_rates_greater['shipment_price'] == 0) {
                                    $shipment_error = 1;
                                    $upgrade_shipping_carrier_code = $get_shipping_rates_greater['shipping_carrier_code'];
                                    
                                } else {
                                    $upgrade_shipment_price = 0;
                                    $upgrade_shipping_carrier_code = $get_shipping_rates_greater['shipping_carrier_code'];
                                    $upgrade_shipstation_shipment_prices = $get_shipping_rates_greater['shipment_prices'];
                                }
                                
                            } else {
                                $upgrade_shipping_methods = $this->upgrade_shipment_prices($products_weight, $user_address , $selected_shipment_quotes ,$shipping_quotes, $shipment_prices, $upgrade_shipment_price, $product_width, $product_height, $product_length , $get_user_default_shipping_address , $get_user_default_billing_address , $productTotal);
                                if (($upgrade_shipping_methods['shipment_prices'] === null)) {
                                    $shipment_error = 1;
                                }
                                else {
                                    $upgrade_shipment_price = $upgrade_shipping_methods['shipment_price'];
                                    $upgrade_shipping_carrier_code = $upgrade_shipping_methods['shipping_carrier_code'];
                                    $upgrade_shipstation_shipment_prices = $upgrade_shipping_methods['shipment_prices'];
                                    $upgrade_admin_selected_shipping_quote = $upgrade_shipstation_shipment_prices;
                                }
                            }
                        }
                        
                        
                    } 
                    else {
                        if (!empty($admin_area_for_shipping) && strtolower($admin_area_for_shipping->option_value) == 'yes') {
                            if ($products_weight > 150) {
                                $shipping_carrier_code = $carrier_code_2->option_value;
                                $shipping_service_code = $service_code_2->option_value;
                                $get_shipping_rates_greater = $this->get_shipping_rate_greater($products_weight, $user_address , $selected_shipment_quotes ,$shipping_quotes, $shipment_prices, $shipment_price, $product_width, $product_height, $product_length , $get_user_default_shipping_address , $get_user_default_billing_address , $productTotal);
                                
                                if (($get_shipping_rates_greater['shipment_prices'] == null) && $get_shipping_rates_greater['shipment_price'] == 0) {
                                    $shipment_error = 1;
                                    $shipping_carrier_code = $get_shipping_rates_greater['shipping_carrier_code'];
                                    
                                } else {
                                    $shipment_price = $get_shipping_rates_greater['shipment_price'];
                                    $shipping_carrier_code = $get_shipping_rates_greater['shipping_carrier_code'];
                                    $shipstation_shipment_prices = $get_shipping_rates_greater['shipment_prices'];
                                }
                                
                            }
                            else {
                                $shipping_carrier_code = null;
                                $shipping_service_code = null;
                                $get_shipping_rates_new = $this->get_shipping_rate_new($products_weight, $user_address , $selected_shipment_quotes ,$shipping_quotes, $shipment_prices, $shipment_price, $product_width, $product_height, $product_length , $get_user_default_shipping_address , $get_user_default_billing_address , $productTotal);
                                
                                
                                if (($get_shipping_rates_new['shipment_prices'] === null)) {
                                    $shipment_error = 1;
                                }
                                else {
                                    $shipment_price = $get_shipping_rates_new['shipment_price'];
                                    $shipping_carrier_code = $get_shipping_rates_new['shipping_carrier_code'];
                                    $shipstation_shipment_prices = $get_shipping_rates_new['shipment_prices'];
                                    $admin_selected_shipping_quote = $shipstation_shipment_prices;
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
                                'toPostalCode' => $get_user_default_shipping_address->DeliveryState ? $get_user_default_shipping_address->DeliveryState : $get_user_default_billing_address->BillingState,
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

                    if ($buyListData == true) {
                        $buyListData = true;
                        $shipment_price = 0;
                        $buy_list_discount_calculated = $buyList->shipping_and_discount->discount_calculated ?? 0;
                    } else {
                        $buyListData = false;
                        $shipment_price = 0;
                        $buy_list_discount_calculated = 0;
                    }
                }
            }
            else {
                if ($charge_shipment_fee == true) {
                    $buyListData = false;
                    if ($shipping_free_over_1000 == 1) {
                        $shipment_price = 0;
                        $allow_upgrade = true;
    
    
                        if (!empty($admin_area_for_shipping) && strtolower($admin_area_for_shipping->option_value) == 'yes' && $allow_upgrade = true) {
                            if ($products_weight > 150) {
                                $upgrade_shipping_carrier_code = $carrier_code_2->option_value;
                                $upgrade_shipping_service_code = $service_code_2->option_value;
                                $get_shipping_rates_greater = $this->get_shipping_rate_greater($products_weight, $user_address , $selected_shipment_quotes ,$shipping_quotes, $shipment_prices, $shipment_price, $product_width, $product_height, $product_length , $get_user_default_shipping_address , $get_user_default_billing_address , $productTotal);
                                
                                if (($get_shipping_rates_greater['shipment_prices'] == null) && $get_shipping_rates_greater['shipment_price'] == 0) {
                                    $shipment_error = 1;
                                    $upgrade_shipping_carrier_code = $get_shipping_rates_greater['shipping_carrier_code'];
                                    
                                } else {
                                    $upgrade_shipment_price = 0;
                                    $upgrade_shipping_carrier_code = $get_shipping_rates_greater['shipping_carrier_code'];
                                    $upgrade_shipstation_shipment_prices = $get_shipping_rates_greater['shipment_prices'];
                                }
                                
                            } else {
                                $upgrade_shipping_methods = $this->upgrade_shipment_prices($products_weight, $user_address , $selected_shipment_quotes ,$shipping_quotes, $shipment_prices, $upgrade_shipment_price, $product_width, $product_height, $product_length , $get_user_default_shipping_address , $get_user_default_billing_address , $productTotal);
                                if (($upgrade_shipping_methods['shipment_prices'] === null)) {
                                    $shipment_error = 1;
                                }
                                else {
                                    $upgrade_shipment_price = $upgrade_shipping_methods['shipment_price'];
                                    $upgrade_shipping_carrier_code = $upgrade_shipping_methods['shipping_carrier_code'];
                                    $upgrade_shipstation_shipment_prices = $upgrade_shipping_methods['shipment_prices'];
                                    $upgrade_admin_selected_shipping_quote = $upgrade_shipstation_shipment_prices;
                                }
                            }
                        }
                        
                        
                    } 
                    else {
                        if (!empty($admin_area_for_shipping) && strtolower($admin_area_for_shipping->option_value) == 'yes') {
                            if ($products_weight > 150) {
                                $shipping_carrier_code = $carrier_code_2->option_value;
                                $shipping_service_code = $service_code_2->option_value;
                                $get_shipping_rates_greater = $this->get_shipping_rate_greater($products_weight, $user_address , $selected_shipment_quotes ,$shipping_quotes, $shipment_prices, $shipment_price, $product_width, $product_height, $product_length , $get_user_default_shipping_address , $get_user_default_billing_address , $productTotal);
                                
                                if (($get_shipping_rates_greater['shipment_prices'] == null) && $get_shipping_rates_greater['shipment_price'] == 0) {
                                    $shipment_error = 1;
                                    $shipping_carrier_code = $get_shipping_rates_greater['shipping_carrier_code'];
                                    
                                } else {
                                    $shipment_price = $get_shipping_rates_greater['shipment_price'];
                                    $shipping_carrier_code = $get_shipping_rates_greater['shipping_carrier_code'];
                                    $shipstation_shipment_prices = $get_shipping_rates_greater['shipment_prices'];
                                }
                                
                            }
                            else {
                                $shipping_carrier_code = null;
                                $shipping_service_code = null;
                                $get_shipping_rates_new = $this->get_shipping_rate_new($products_weight, $user_address , $selected_shipment_quotes ,$shipping_quotes, $shipment_prices, $shipment_price, $product_width, $product_height, $product_length , $get_user_default_shipping_address , $get_user_default_billing_address , $productTotal);
                                
                                
                                if (($get_shipping_rates_new['shipment_prices'] === null)) {
                                    $shipment_error = 1;
                                }
                                else {
                                    $shipment_price = $get_shipping_rates_new['shipment_price'];
                                    $shipping_carrier_code = $get_shipping_rates_new['shipping_carrier_code'];
                                    $shipstation_shipment_prices = $get_shipping_rates_new['shipment_prices'];
                                    $admin_selected_shipping_quote = $shipstation_shipment_prices;
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
                                'toPostalCode' => $get_user_default_shipping_address->DeliveryState ? $get_user_default_shipping_address->DeliveryState : $get_user_default_billing_address->BillingState,
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
                    $buyListData = false;
                    $shipment_price = 0;
                }
            }

            $enable_discount_setting = AdminSetting::where('option_name', 'enable_discount')->first();
            if (!empty($enable_discount_setting) && strtolower($enable_discount_setting->option_value) == 'yes') {
                $discount_codes = Discount::where('end_date', '>=', $current_date)->where('status', 1)->get();
                foreach($discount_codes as $discount_code) {
                    if (!empty($discount_code)) {
                        if ($discount_code->customer_eligibility === 'All Customers') {
                            $allow_discount_for_new_user = false;
                            $allow_discount_for_all_customers = true;
                            $allow_discount_for_specific_customers = false;
                        } 
                        elseif($discount_code->customer_eligibility === 'Specific Customers') {
                            $allow_discount_for_new_user = false;
                            $allow_discount_for_all_customers = false;
                            $allow_discount_for_specific_customers = true;
                        } 
                        elseif(($discount_code->customer_eligibility === 'New User') && (empty($check_new_user_orders))) {
                            $allow_discount_for_new_user = true;
                            $allow_discount_for_all_customers = false;
                            $allow_discount_for_specific_customers = false;
                        }
                        else {
                            $discount_code = null;
                        }
                    }
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
                }
            } else {
                $discount_code = null;
            }
            $parcel_guard = 0.00;
            // $toggle_shipment_insurance = AdminSetting::where('option_name', 'toggle_shipment_insurance')->first();
            // $shipment_insurance_fee = AdminSetting::where('option_name', 'shipment_insurance_fee')->first();
            // if (!empty($toggle_shipment_insurance) && strtolower($toggle_shipment_insurance->option_value) == 'yes') {
            //     $parcel_guard = 0.00;
            // } else {
            //     $parcel_guard = 0.00;
            // }
            // dd($allow_discount_for_new_user, $allow_discount_for_specific_customers, $allow_discount_for_all_customers);
            $upgrade_shipping = AdminSetting::where('option_name', 'enable_upgrade_shipping')->first();
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
                'surcharge_settings_for_weight_greater_then_150',
                'shipping_carrier_code' , 'shipping_service_code', 'shipstation_shipment_prices' , 'charge_shipment_to_customer', 'shipping_free_over_1000','shipment_error',
                'allow_discount_for_new_user',
                'allow_discount_for_specific_customers',
                'allow_discount_for_all_customers',
                'parcel_guard',
                // 'shipping_quotes_settings',
                // 'allow_pickup','
                'extra_charges_for_total_over_499',
                'distance',
                'extra_shipping_value',
                'enable_free_shipping_banner',
                'enable_free_shipping_banner_text',
                'get_user_default_billing_address',
                'get_user_default_shipping_address',
                'get_all_user_addresses',
                'get_all_user_billing_addresses_all',
                'surcharge_type_settings_for_weight_greater_then_150',
                'upgrade_shipping',
                'upgrade_admin_selected_shipping_quote',
                'upgrade_shipping_carrier_code',
                'upgrade_shipment_price',
                'upgrade_shipping_service_code',
                'buyListData',
                'buyLIst_shipping_cost',
                'buyListdiscount',
                'buyListdiscount_type',
                'buy_list_discount_calculated',
                'buy_list_id',
                're_order_id',
                'product_width',
                'product_length',
                'product_height',


                // 'toggle_shipment_insurance'
            ));
        } else {
            return redirect()->back()->with('message', 'Your account is disabled. You can not proceed with checkout. Please contact us.');
        }
    }

    public function thankyou(Request $request , $id)
    {
        $user_id = Auth::id();
        // $session_contact_id = Session::get('contact_id');
        $order = ApiOrder::where('id', $id)
            ->with(
                'user.contact',
                'apiOrderItem.product.options',
                'texClasses',
                'discount',
                'OrderReminder'
            )
            ->first();
        $user = User::where('id', $user_id)->first();
        $all_ids = UserHelper::getAllMemberIds($user);
        $order_contact_query = Contact::whereIn('id', $all_ids)->first();
        
        $order_contact = Contact::where('contact_id', $order->memberId)->first();
        if (empty($order_contact) && $order_contact->is_parent == 0) {
            $order_contact = Contact::where('contact_id', $order_contact->parent_id)->first();
        }
         
        $createdDate = $order->created_at;
        $formatedDate = $createdDate->format('F  j, Y h:i:s A');
        $orderitems = ApiOrderItem::where('order_id', $id)->with('product','product_option')->get();
        $count = $orderitems->count();
        $best_products = Product::where('status', '!=', 'Inactive')->orderBy('views', 'DESC')->limit(4)->get();
        
        
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
        
        

        if ($order->is_stripe == 1) {
                FacebookConversionService::sendPurchaseEvent(
                $order_contact->email,
                $order->DeliveryPhone,
                $order->DeliveryFirstName,
                $order->DeliveryLastName,
                $order->DeliveryCity,
                $order->DeliveryState,
                $order->DeliveryZip,
                $order->total_including_tax,
                $order->reference
            );
        }

        $enable_reminders = AdminSetting::where('option_name', 'enable_order_reminder')->first();

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
                'tax',
                'enable_reminders'
            )
        );
    }

    public function store_order_reminder(Request $request)
    {
        $user_id = $request->input('user_id');
        $contact_id = $request->input('contact_id');
        $order_id = $request->input('order_id');
        $reminder_date = Carbon::parse($request->input('reminder_date'))->format('Y-m-d');


         // Prevent duplicate reminder for the same order_id
        $exists = OrderReminder::where('order_id', $order_id)->where('reminder_date' , $reminder_date)->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Reminder for this order already exists for the selected date.');
        }

        $reminder = new OrderReminder();
        $reminder->user_id = $user_id;
        $reminder->contact_id = $contact_id;
        $reminder->order_id = $order_id;
        $reminder->reminder_date = $reminder_date;
        $reminder->is_sent = 0; // Default to not sent
        $reminder->save();


        return redirect()->back()->with('success', 'Order reminder has been set successfully.');
    }



    public function re_order(Request $request, $id)
    {
        $re_order = ApiOrder::where('id', $id)
            ->with('apiOrderItem', 'apiOrderItem.product', 'apiOrderItem.product.options')
            ->first();

        if (!$re_order) {
            return redirect()->route('index')->with('error', 'Order Not Found');
        }

        $order_reminder = OrderReminder::where('order_id', $id)
            ->whereNotNull('is_expired')
            ->first();

        if (!empty($order_reminder)) {
            return redirect()->route('index')->with('error', 'Re-order Notification Expired');
        }

        // Ensure cart_hash exists
        if (!session()->has('cart_hash')) {
            session()->put('cart_hash', Str::random(10));
        }

        $cartHash = session()->get('cart_hash');
        $cart = session()->get('cart', []);
        $userPriceColumn = UserHelper::getUserPriceColumn();
        $userId = auth()->id() ?? 0;
        $contactId = session()->get('contact_id') ?? null;
        $assigned_contact = UserHelper::assign_contact(session()->get('contact_id')); // Assign contact

        foreach ($re_order->apiOrderItem as $apiOrderItem) {
            $product = $apiOrderItem->product;
            $option = $product->options->first();
            $retailPrice = 0;

            if ($option && $option->price) {
                foreach ($option->price as $price) {
                    $retailPrice = $price->$userPriceColumn ?? 0;
                    $retailPrice = $retailPrice ?: ($price->sacramentoUSD ?? $price->retailUSD ?? 0);
                }
            }


            if (auth()->user()) {
                $product_in_active_cart = Cart::where('qoute_id',$product->id)->where('contact_id', $assigned_contact)->first();
                if ($product_in_active_cart) {
                    $product_in_active_cart->quantity += $apiOrderItem->quantity;
                    $product_in_active_cart->updated_at = now();
                    $product_in_active_cart->save();

                    
                    $cart[$product_in_active_cart->qoute_id] = [
                        'qoute_id'   => $product->id,
                        'product_id' => $product->id,
                        'option_id'  => $apiOrderItem->option_id,
                        'quantity'   => $product_in_active_cart->quantity,
                        'name'       => $product->name,
                        'price'      => $retailPrice,
                        'image'      => $product->images,
                        'slug'       => $product->slug,
                        'code'     => $product->code,
                        'user_id'    => $userId,
                        'contact_id' => $contactId,
                        'is_active'  => 1,
                        'updated_at' => now(),
                    ];
                    // session()->put('cart', $cart);
                } else {
                    $cart[$product->id] = $this->reOrderEntry($assigned_contact, $retailPrice, $apiOrderItem, $cartHash, $userId, $product);
                    Cart::create($cart[$product->id]); // Store the cart entry in the database
                }
            } 
            else {
                // Match by product_id + option_id to avoid duplicates
                $existingCartItem = Cart::where('cart_hash', $cartHash)
                    ->where('qoute_id', $product->id)
                    ->first();

                if ($existingCartItem) {
                    $existingCartItem->quantity += $apiOrderItem->quantity;
                    $existingCartItem->updated_at = now();
                    $existingCartItem->save();

                    
                    $cart[$existingCartItem->qoute_id] = [
                        'qoute_id'   => $product->id,
                        'product_id' => $product->id,
                        'option_id'  => $apiOrderItem->option_id,
                        'quantity'   => $existingCartItem->quantity,
                        'name'       => $product->name,
                        'price'      => $retailPrice,
                        'image'      => $product->images,
                        'slug'       => $product->slug,
                        'code'     => $product->code,
                        'user_id'    => $userId,
                        'contact_id' => $contactId,
                        'is_active'  => 1,
                        'updated_at' => now(),
                    ];
                    // session()->put('cart', $cart);
                } else {
                    $cart[$product->id] = $this->reOrderEntry($assigned_contact, $retailPrice, $apiOrderItem, $cartHash, $userId, $product);
                    Cart::create($cart[$product->id]); // Store the cart entry in the database
                }
            }

            
        }

        session()->put('re_order_id' , $id);
        session()->put('cart', $cart);

        return redirect()->route('cart');
    }

    private function reOrderEntry($contactId, $retailPrice, $apiOrderItem, $cartHash, $userId, $product)
    {
        return [
            'qoute_id'   => $product->id,
            'product_id' => $product->product_id,
            'name'       => $product->name,
            'quantity'   => $apiOrderItem->quantity,
            'price'      => $retailPrice,
            'code'       => $product->code,
            'image'      => $product->images,
            'option_id'  => $apiOrderItem->option_id,
            'slug'       => $product->slug,
            'cart_hash'  => $cartHash,
            'user_id'    => $userId,
            'contact_id' => $contactId,
            'is_active'  => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
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
                $last4 = isset($charge->payment_method_details->card) ? $charge->payment_method_details->card->last4 : null;
                $card_brand = isset($charge->payment_method_details->card) ? $charge->payment_method_details->card->brand : null;

                $brand = $charge->payment_method_details->card->brand ?? null;
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

                    // update buy list order status for discount count

                    if (!empty($currentOrder->buylist_id) && !empty($currentOrder->buylist_discount)) {
                        $update_buy_list_shipping_and_discount = BuyListShippingAndDiscount::where('buylist_id', $currentOrder->buylist_id)->first();
                        if (!empty($update_buy_list_shipping_and_discount)) {
                            $update_buy_list_shipping_and_discount->discount_count = !empty($update_buy_list_shipping_and_discount->discount_count) ? $update_buy_list_shipping_and_discount->discount_count + 1 : 1;
                            $update_buy_list_shipping_and_discount->save();
                        }
                    }


                    if ($payment_succeeded->data->object->paid == true) {
                        $currentOrder->payment_status = 'paid';
                        $currentOrder->isApproved = $currentOrder->isApproved == 2 || $currentOrder->isApproved == 5 ? 0 :  $currentOrder->isApproved;
                        $currentOrder->charge_id = $chargeId;
                        $currentOrder->card_number = $card_brand . ' '. $last4;
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
    
                  
                    // $order_items = ApiOrderItem::with('order.texClasses','product','product.options')
                    // ->where('order_id', $order_id)
                    // ->get();

                    $option_ids = ApiOrderItem::where('order_id', $order_id)->pluck('option_id')->toArray();
                    $order_items = ApiOrderItem::with(['product.options' => function ($q) use ($option_ids) {
                        $q->whereIn('option_id', $option_ids);
                    }])->where('order_id', $order_id)->get();
                    
                    $check_shipstation_create_order_status = AdminSetting::where('option_name', 'create_order_in_shipstation')->first();
                    if (!empty($check_shipstation_create_order_status) && strtolower($check_shipstation_create_order_status->option_value) == 'yes' && (strtolower($currentOrder->logisticsCarrier) !== 'pickup order' && empty($currentOrder->buylist_id))) {
                        $order_contact = Contact::where('contact_id', $currentOrder->memberId)->orWhere('parent_id' , $currentOrder->memberId)->first();
                        if (!empty($order_contact)) {
                            $shipstation_order_status = 'create_order';
                            if (
                                (!empty($currentOrder->DeliveryAddress1) || !empty($currentOrder->DeliveryAddress2)) &&
                                (!SettingHelper::startsWithPOBox($currentOrder->DeliveryAddress1) && !SettingHelper::startsWithPOBox($currentOrder->DeliveryAddress2))
                            ) 
                            {
                                $shiping_order = UserHelper::shipping_order($order_id , $currentOrder , $order_contact, $shipstation_order_status);
                                if ($shiping_order['statusCode'] == 200) {
                                    $orderUpdate = ApiOrder::where('id', $order_id)->update([
                                        'shipstation_orderId' => $shiping_order['responseBody']->orderId,
                                        'shipstation_orderKey' => $shiping_order['responseBody']->orderKey,
                                        'shipstation_orderNumber' => $shiping_order['responseBody']->orderNumber,
                                    ]);
                                }
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
    

                    // $specific_admin_notifications = SpecificAdminNotification::all();
                    // if (count($specific_admin_notifications) > 0) {
                    //     foreach ($specific_admin_notifications as $specific_admin_notification) {
                    //         $subject = 'New order received';
                    //         $adminTemplate = 'emails.admin-order-received';
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

                            $subject = 'New order received';

                            $data['subject'] = $subject;
                            $data['email'] = $specific_admin_notification->email;

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
            case 'payout.paid':
                $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
                $payment_succeeded = $stripe->events->retrieve(
                    $event->id,
                    []
                );
                $charge = $event->data->object;
                $chargeId = $charge->id;
                $last4 = isset($charge->payment_method_details->card) ? $charge->payment_method_details->card->last4 : null;
                $card_brand = isset($charge->payment_method_details->card) ? $charge->payment_method_details->card->brand : null;
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

                    if (!empty($currentOrder->buylist_id) && !empty($currentOrder->buylist_discount)) {
                        $update_buy_list_shipping_and_discount = BuyListShippingAndDiscount::where('buylist_id', $currentOrder->buylist_id)->first();
                        if (!empty($update_buy_list_shipping_and_discount)) {
                            $update_buy_list_shipping_and_discount->discount_count = !empty($update_buy_list_shipping_and_discount->discount_count) ? $update_buy_list_shipping_and_discount->discount_count + 1 : 1;
                            $update_buy_list_shipping_and_discount->save();
                        }
                    }

                    if ($payment_succeeded->data->object->paid == true) {
                        $currentOrder->payment_status = 'paid';
                        $currentOrder->isApproved = $currentOrder->isApproved == 2 || $currentOrder->isApproved == 5 ? 0 :  $currentOrder->isApproved;
                        $currentOrder->charge_id = $chargeId;
                        $currentOrder->card_number = $card_brand . ' '. $last4;
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
    
                  
                    // $order_items = ApiOrderItem::with('order.texClasses', 'product.options')
                    // ->where('order_id', $order_id)
                    // ->get();

                    $option_ids = ApiOrderItem::where('order_id', $order_id)->pluck('option_id')->toArray();
                    $order_items = ApiOrderItem::with(['product.options' => function ($q) use ($option_ids) {
                        $q->whereIn('option_id', $option_ids);
                    }])->where('order_id', $order_id)->get();

                    $pickup = !empty($currentOrder->logisticsCarrier) && strtolower($currentOrder->logisticsCarrier) === 'pickup order' ? true : false;
                    
                    $check_shipstation_create_order_status = AdminSetting::where('option_name', 'create_order_in_shipstation')->first();
                    if (!empty($check_shipstation_create_order_status) && strtolower($check_shipstation_create_order_status->option_value) == 'yes' && empty($currentOrder->buylist_id)) {
                        $order_contact = Contact::where('contact_id', $currentOrder->memberId)->orWhere('parent_id' , $currentOrder->memberId)->first();
                        if (!empty($order_contact) && $pickup == false) {
                            $shipstation_order_status = 'create_order';
                            if (
                                (!empty($currentOrder->DeliveryAddress1) || !empty($currentOrder->DeliveryAddress2)) &&
                                (!SettingHelper::startsWithPOBox($currentOrder->DeliveryAddress1) && !SettingHelper::startsWithPOBox($currentOrder->DeliveryAddress2))
                            ) 
                            {
                                $shiping_order = UserHelper::shipping_order($order_id , $currentOrder , $order_contact , $shipstation_order_status);
                                if ($shiping_order['statusCode'] == 200) {
                                    $orderUpdate = ApiOrder::where('id', $order_id)->update([
                                        'shipstation_orderId' => $shiping_order['responseBody']->orderId,
                                        'shipstation_orderKey' => $shiping_order['responseBody']->orderKey,
                                        'shipstation_orderNumber' => $shiping_order['responseBody']->orderNumber,
                                    ]);
                                }
                            }
                        }
                    }
                    $customer_email = Contact::where('contact_id', $currentOrder->memberId)->first();
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

                    // $specific_admin_notifications = SpecificAdminNotification::all();
                    // if (count($specific_admin_notifications) > 0) {
                    //     foreach ($specific_admin_notifications as $specific_admin_notification) {
                    //         $subject = 'New order received';
                    //         $adminTemplate = 'emails.admin-order-received';
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

                            $subject = 'New order received';
                            
                            $data['subject'] = $subject;
                            $data['email'] = $specific_admin_notification->email;

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
            case 'payment_intent.processing':
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $payment_event = $stripe->events->retrieve($event->id, []);

            $intent = $event->data->object;
            $charge = null;
            $chargeId = null;
            $bank_details = null;
            $last4 = null;
            $card_brand = null;

            // Extract the charge object from PaymentIntent, if available
            if (!empty($intent->charges->data) && count($intent->charges->data) > 0) {
                $charge = $intent->charges->data[0];
                $chargeId = $charge->id ?? null;

                // Card (Visa, MC, Amex, Apple Pay, Google Pay, etc.)
                if (isset($charge->payment_method_details->card)) {
                    $last4 = $charge->payment_method_details->card->last4 ?? '';
                    $card_brand = $charge->payment_method_details->card->brand ?? '';
                    $bank_details = trim($card_brand . ' ' . $last4);
                }
                // US Bank Account (ACH debit, Plaid)
                elseif (isset($charge->payment_method_details->us_bank_account)) {
                    $bank_name = $charge->payment_method_details->us_bank_account->bank_name ?? '';
                    $last4 = $charge->payment_method_details->us_bank_account->last4 ?? '';
                    $bank_details = trim($bank_name . ' ' . $last4);
                }
                // ACH Credit Transfer
                elseif (isset($charge->payment_method_details->ach_credit_transfer)) {
                    $bank_name = $charge->payment_method_details->ach_credit_transfer->bank_name ?? '';
                    $routing_number = $charge->payment_method_details->ach_credit_transfer->routing_number ?? '';
                    $account_number = $charge->payment_method_details->ach_credit_transfer->account_number ?? '';
                    $bank_details = trim($bank_name . ' ' . $routing_number . ' ' . $account_number);
                }
                // Add more payment methods here if needed
            }

            $order_id = $intent->metadata->order_id ?? null;

            if ($order_id) {
                $currentOrder = ApiOrder::where('id', $order_id)->first();

                if ($currentOrder) {
                    // Mark order as pending payment (waiting on bank funds)
                    $currentOrder->payment_status = 'pending';
                    $currentOrder->isApproved = $currentOrder->isApproved == 0 ? 5 : $currentOrder->isApproved;
                    $currentOrder->charge_id = $chargeId;
                    $currentOrder->card_number = $bank_details;
                    $currentOrder->save();

                    // Add comment to order history
                    $order_comment = new OrderComment;
                    $order_comment->order_id = $order_id;
                    $order_comment->comment = 'Order payment is pending — awaiting funds from bank (payment_intent.processing webhook).';
                    $order_comment->save();

                    // Optional: add email notification to customer/admin
                }
            }
            break;

            case 'charge.pending':
                $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
                $payment_event = $stripe->events->retrieve($event->id, []);

                $charge = $event->data->object;
                $chargeId = $charge->id;
                $last4 = $charge->payment_method_details->card->last4 ?? null;
                $card_brand = $charge->payment_method_details->card->brand ?? null;

                $order_id = $payment_event->data->object->metadata->order_id ?? null;

                if ($order_id) {
                    $currentOrder = ApiOrder::where('id', $order_id)->first();

                    if ($currentOrder) {
                        // Mark order as pending payment (waiting on bank funds)
                        $currentOrder->payment_status = 'pending';
                        $currentOrder->isApproved = $currentOrder->isApproved == 2 ? 5 :  $currentOrder->isApproved; 
                        $currentOrder->charge_id = $chargeId;
                        $currentOrder->card_number = $card_brand . ' ' . $last4;
                        $currentOrder->save();

                        // Add comment to order history
                        $order_comment = new OrderComment;
                        $order_comment->order_id = $order_id;
                        $order_comment->comment = 'Order payment is pending — awaiting funds from bank (charge.pending webhook).';
                        $order_comment->save();

                        // You can add email notification to customer/admin here if needed
                    }
                }
            break;


            // Add more cases for other event types you want to handle
        }
        
        return response()->json(['status' => 'success']);
    }

    public function check_existing_email(Request $request)
    {
        $is_guest = false;
        $email = $request->email;
        $user = User::with('guest_contact')
        ->where('email', $email)
        ->first();

        if (!empty($user)) {
            $is_guest = !empty($user->guest_contact) && $user->guest_contact->is_guest == 1 ? true : false;
            return response()->json(
                [
                    'status' => 'success',
                    'is_guest' => $is_guest,
                    'user_status' => 'Existed',
                    'message' => 'Please enter your password to continue.'
                ]
            );
        } 
        else {
            return response()->json(
                [
                    'status' => 'error',
                    'is_guest' => $is_guest,
                    'user_status' => 'Not Exists', 
                    'message' => 'Please enter your complete details to continue.'
                ]
            );
        }
    }

    // new authenticate user
    public function authenticate_user(Request $request) {

        $is_guest_user = !empty($request->is_guest) && $request->is_guest == 1 ? 1 : 0;  

        if ($is_guest_user == 1) {
            $request->validate([
                'email' => 'required',
                // 'password' => 'required'
            ]);
        } else {
            $request->validate([
                'email' => 'required',
                'password' => 'required'
            ]);
        }

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
        $address_validator = true;
        $content = null;
       
        if (auth()->attempt($credentials)) {
            if (auth()->user()->allow_access == 0) {
                Session::flush();
                Auth::logout();
                $access = false;
                $message = 'Your account has been disabled.';
                // session()->flash('message', 'Your account has been disabled.');
                // return redirect()->back();
            } 
            else {
                $user_id = auth()->user()->id;
                if ($user->hasRole(['Newsletter']) || $user->hasRole(['Sale Payments']) || $user->hasRole(['Payouts'])) {
                    session()->flash('message', 'Successfully Logged in');
                    return redirect()->route('newsletter_dashboard');
                }


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
                    if ($companies->count() > 1) {
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
                    if ($companies->count() > 1) {
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

                    
                    if ($user->is_updated == 1) {

                        $companies = Contact::where('user_id', auth()->user()->id)->get();
                        if ($companies->count() == 1) {
                            if ($companies[0]->contact_id == null) {
                                UserHelper::switch_company($companies[0]->secondary_id);
                            } else {
                                UserHelper::switch_company($companies[0]->contact_id);
                            }
                        }

                        if ($companies->count() > 1) {
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

                        $previousUrl = session('previous_url', '/'); 
                    } else {
                        $session_contact_id = null;
                        $companies = Contact::where('user_id', auth()->user()->id)->get();
                        if ($companies->count() == 1) {
                            if ($companies[0]->contact_id == null) {
                                UserHelper::switch_company($companies[0]->secondary_id);
                            } else {
                                UserHelper::switch_company($companies[0]->contact_id);
                            }
                        }
                        if ($companies->count() > 1) {
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
                    }

                    
                    Session::put('companies', $companies);
                    $admin = false;
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
                    if ( $is_guest_user == 1) {
                        $request->validate(
                            [
                                'email' => 'required|email',
                                // 'password' =>'required',
                                'first_name' => 'required',
                                // 'address' => 'required',
                                'address' => [
                                    'required',
                                    // function ($attribute, $value, $fail) {
                                    //     if (preg_match('/^(P\.?\s*O\.?\s*Box)/i', trim($value))) {
                                    //         $fail('Invalid address: PO Boxes are not allowed at the start.');
                                    //     }
                                    // },
                                ],
                                'state' => 'required',
                                'city' => 'required',
                                'zip_code' => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
                                'phone' => ['required', 'alpha_num', 'size:10'], // exactly 10 alphanumeric chars
                                'postal_address1' => [
                                    'required',
                                    // function ($attribute, $value, $fail) {
                                    //     if (preg_match('/^(P\.?\s*O\.?\s*Box)/i', trim($value))) {
                                    //         $fail('Invalid address: PO Boxes are not allowed at the start.');
                                    //     }
                                    // },
                                ],
                                'postal_state' => 'required',
                                'postal_zip_code' => 'required',
                                'postal_city' => 'required',
                            ],
                            [
                                'postal_address1.required' => 'Shipping Address is required.',
                                'postal_state.required' => 'Shipping State is required.',
                                'postal_zip_code.required' => 'Shipping Zip Code is required.',
                                'postal_city.required' => 'Shipping City is required.',
                                'phone.required' => 'Phone is required',
                                'phone.alpha_num' => 'Phone must only contain letters and numbers',
                                'phone.size' => 'Phone must be exactly 10 characters',
                            ]
                        );
                    }
                    else {
                        $request->validate(
                            [
                                'email' => 'required|email',
                                'password' =>'required',
                                'first_name' => 'required',
                                // 'address' => 'required',
                                'address' => [
                                    'required',
                                    // function ($attribute, $value, $fail) {
                                    //     if (preg_match('/^(P\.?\s*O\.?\s*Box)/i', trim($value))) {
                                    //         $fail('Invalid address: PO Boxes are not allowed at the start.');
                                    //     }
                                    // },
                                ],
                                'state' => 'required',
                                'city' => 'required',
                                'zip_code' => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
                                'phone' => ['required', 'alpha_num', 'size:10'], // exactly 10 alphanumeric chars
                                'postal_address1' => [
                                    'required',
                                    // function ($attribute, $value, $fail) {
                                    //     if (preg_match('/^(P\.?\s*O\.?\s*Box)/i', trim($value))) {
                                    //         $fail('Invalid address: PO Boxes are not allowed at the start.');
                                    //     }
                                    // },
                                ],
                                'postal_state' => 'required',
                                'postal_zip_code' => 'required',
                                'postal_city' => 'required',
                                
                            ] , 

                            [
                                'postal_address1.required' => 'Shipping Address is required.',
                                'postal_state.required' => 'Shipping State is required.',
                                'postal_zip_code.required' => 'Shipping Zip Code is required.',
                                'postal_city.required' => 'Shipping City is required.',
                                'phone.required' => 'Phone is required',
                                'phone.alpha_num' => 'Phone must only contain letters and numbers',
                                'phone.size' => 'Phone must be exactly 10 characters',
                            ]
                        );
                    }
                   
                } else {
                    if ($is_guest_user == 1) {
                        $request->validate([
                            'email' => 'required|email',
                            'first_name' => 'required',
                            // 'address' => 'required',
                            'address' => [
                                'required',
                                // function ($attribute, $value, $fail) {
                                //     if (preg_match('/^(P\.?\s*O\.?\s*Box)/i', trim($value))) {
                                //         $fail('Invalid address: PO Boxes are not allowed at the start.');
                                //     }
                                // },
                            ],
                            'state' => 'required',
                            'zip_code' => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
                            'phone' => 'required',
                            'city' => 'required',
                            'phone' => ['required', 'alpha_num', 'size:10'], // exactly 10 alphanumeric chars
                        ]);
                    }
                    else {
                        $request->validate([
                            'email' => 'required|email',
                            'password' =>'required',
                            'first_name' => 'required',
                            // 'address' => 'required',
                            'address' => [
                                'required',
                                // function ($attribute, $value, $fail) {
                                //     if (preg_match('/^(P\.?\s*O\.?\s*Box)/i', trim($value))) {
                                //         $fail('Invalid address: PO Boxes are not allowed at the start.');
                                //     }
                                // },
                            ],
                            'state' => 'required',
                            'zip_code' => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
                            'phone' => 'required',
                            'city' => 'required',
                            'phone' => ['required', 'alpha_num', 'size:10'], // exactly 10 alphanumeric chars
                        ]);
                    }
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
                    $postalCity     = $request->postal_city;
                    $postalState    = $postal_state_name;
                    $postalPostCode = $request->postal_zip_code;
                    $selectedChoice = $request->selected_shipping_choice;

                    if (!$selectedChoice || $selectedChoice !== 'entered') {
                        $validate_address_1 = UserHelper::validateFullAddress($postalAddress1, $postalAddress2, $postalCity, $postalState, $postalPostCode, $country = 'USA');

                        if ($validate_address_1['valid'] == false) {
                            return response()->json([
                                'different_shipping_address' => 1,
                                'status'                   => 'address_error',
                                'address_validator'       => false,
                                'validator_message'       => $validate_address_1['message'] ?? 'Address validation failed.',
                                'suggested_address'       => $validate_address_1['suggested_address'] ?? '',
                                'formatted_address'       => $validate_address_1['formatted_address'] ?? '',
                            ], 400);
                        }

                        // return response()->json([
                        //     'different_shipping_address' => 1,
                        //     'status'                   => 'address_success',
                        //     'address_validator'       => true,
                        //     'validator_message'       => $validate_address_1['message'] ?? 'Address validated successfully.',
                        //     'formatted_address'       => $validate_address_1['formatted_address'] ?? '',
                        // ]);
                    }

                } else {
                    $postalAddress1 = $address1;
                    $postalAddress2 = $address2;
                    $postalCity     = $city;
                    $postalState    = $state_name;
                    $postalPostCode = $postCode;
                    $selectedChoice = $request->selected_shipping_choice;

                    if (!$selectedChoice || $selectedChoice !== 'entered') {
                        $validate_address_2 = UserHelper::validateFullAddress($postalAddress1, $postalAddress2, $postalCity, $postalState, $postalPostCode, $country = 'USA');

                        if ($validate_address_2['valid'] == false) {
                            return response()->json([
                                'different_shipping_address' => 0,
                                'status'                   => 'address_error',
                                'address_validator'       => false,
                                'validator_message'       => $validate_address_2['message'] ?? 'Address validation failed.',
                                'suggested_address'       => $validate_address_2['suggested_address'] ?? '',
                                'formatted_address'       => $validate_address_2['formatted_address'] ?? '',
                            ], 400);
                        }

                        // return response()->json([
                        //     'different_shipping_address' => 0,
                        //     'status'                   => 'address_success',
                        //     'address_validator'       => true,
                        //     'validator_message'       => $validate_address_2['message'] ?? 'Address validated successfully.',
                        //     'formatted_address'       => $validate_address_2['formatted_address'] ?? '',
                        // ]);
                    }
                }


                
                DB::beginTransaction();
                try {
                    $price_column = null;
                    $default_price_column = AdminSetting::where('option_name', 'default_price_column')->first();
                    if (!empty($default_price_column)) {
                        $price_column = ucfirst($default_price_column->option_value);
                    }
                    else {
                        $price_column = 'SacramentoUSD';
                    }
                    
            
                    if (empty($price_column)) {
                        $price_column = 'RetailUSD';
                    }

                    $user = User::create([
                        'email' => strtolower($request->get('email')),
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "password" => !empty($is_guest_user)  && $is_guest_user == 1 ? bcrypt('123456') : bcrypt($request->get('password'))
                    ]);
                    $user_id = $user->id;
                    $contact = new Contact([
                        // 'website' => $request->input('company_website'),
                        'company' => !empty($company) ? $company : '',
                        'phone' => $phone,
                        'status' => !empty($toggle_registration) && strtolower($toggle_registration->option_value) == 'yes' ? 1 : 0,
                        'priceColumn' => $price_column,
                        'user_id' => $user_id,
                        'is_guest' => $is_guest_user,
                        'firstName' => $user->first_name,
                        'type' => 'Customer',
                        'lastName' => $user->last_name,
                        'email' => $user->email,
                        'is_parent' => 1,
                        'tax_class' => strtolower($state_name) == strtolower('California') ? '8.75%' : 'Out of State',
                        'paymentTerms' => 'Pay in Advanced',
                        'charge_shipping' => 1,


                        'address1' => $postalAddress1,
                        'address2' => $postalAddress2,
                        'city' => $postalCity,
                        'state' => $postalState,
                        'postCode' => $postalPostCode,
                        'postalAddress1' => $address1,
                        'postalAddress2' => $address2,
                        'postalCity' => $city,
                        'postalState' => $state_name,
                        'postalPostCode' => $postCode,
                        'accountsFirstName' => $user->first_name,
                        'accountsLastName' => $user->last_name,
                        'billingEmail' => SettingHelper::getSetting('noreply_email_address'),                                
                    ]);

                    if (!empty($toggle_registration) && strtolower($toggle_registration->option_value) == 'yes') {
                        $api_contact = $contact->toArray();
                        $client = new \GuzzleHttp\Client();

                        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
                        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password_2');

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
                            Log::info('Cin7 API Key not found or inactive');
                            return false;
                        }


                        if ($request_count >= $threshold) {
                            $message = 'Cin7 API Key threshold reached. Please contact support.';
                            $registration_status = false;
                        }

                        $url = "https://api.cin7.com/api/v1/Contacts/";
                        $response = $client->post($url, [
                            'headers' => ['Content-type' => 'application/json'],
                            'auth' => [
                                $cin7_auth_username,
                                $cin7_auth_password
                            ],
                            'json' => [
                                $api_contact
                            ],
                        ]);

                        UtilHelper::saveEndpointRequestLog('Sync Contacts',$url , $api_key_id);

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

                            $user_log = new UserLog();
                            $user_log->user_id = $created_contact->user_id;
                            $user_log->contact_id = !empty($created_contact->contact_id) ? $created_contact->contact_id : $created_contact->id;
                            $user_log->secondary_id = !empty($created_contact->secondary_id) ? $created_contact->secondary_id : $created_contact->id;
                            $user_log->action = 'Creation';
                            $user_log->user_notes = 'Created Through QCOM Checkcout Registration Form. '. Carbon::now()->toDateTimeString();
                            $user_log->save();
                            // $content = 'Your account has been created successfully and approved by admin.';
                            $content = '';
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

                        $user_log = new UserLog();
                        $user_log->user_id = $created_contact->user_id;
                        $user_log->contact_id = !empty($created_contact->contact_id) ? $created_contact->contact_id : $created_contact->id;
                        $user_log->secondary_id = !empty($created_contact->secondary_id) ? $created_contact->secondary_id : $created_contact->id;
                        $user_log->action = 'Creation';
                        $user_log->user_notes = 'Created Through QCOM Checkcout Registration Form. '. Carbon::now()->toDateTimeString();
                        $user_log->save();

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
                        if ($is_guest_user == 0) {
                            

                            // $specific_admin_notifications = SpecificAdminNotification::all();
                            // if (count($specific_admin_notifications) > 0) {
                            //     foreach ($specific_admin_notifications as $specific_admin_notification) {
                            //         $subject = 'New Register User';
                            //         $data['email'] = $specific_admin_notification->email;
                            //         MailHelper::sendMailNotification('emails.admin_notification', $data);
                            //     }
                            // }

                            $specific_admin_notifications = SpecificAdminNotification::all();
                            if ($specific_admin_notifications->isNotEmpty()) {
                                foreach ($specific_admin_notifications as $specific_admin_notification) {
                                    // Check if this admin should receive order notifications
                                    if (!$specific_admin_notification->receive_order_notifications) {
                                        continue;
                                    }

                                    $subject = 'New Register User';
                                    $data['email'] = $specific_admin_notification->email;

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
                        
                    DB::commit();
                    
                } catch (\Exception $e) {
                    DB::rollback();
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
                $new_user = true;
                $all_customers = false;
                $customer_discount = CustomerDiscount::where('contact_id', $contact_id)->where('discount_id', $discount->id)->first();
                if (empty($customer_discount)) {
                    $eligible = false;
                    $message = 'You are not eligible for this discount';
                } else {
                    $eligible = true;
                    if (!empty($discount->limit_per_user) && strtolower($discount->max_discount_uses) == 'limit for user') {
                        $discount_per_user = true;
                        $customer_discount_count = CustomerDiscount::where('contact_id', $contact_id)->where('discount_id', $discount->id)->count();
                        if ($customer_discount_count >= $discount->limit_per_user) {
                            $max_uses = false;
                            $message = 'You have reached the maximum usage of this discount';   
                        } else {
                            $max_uses = true;
                            $message = 'Discount applied successfully';
                        }
                    } elseif(!empty($discount->max_usage_count) && strtolower($discount->max_discount_uses) == 'limit max times') {
                        $discount_max_times = true;
                        $max_discont_count_in_total = $discount->usage_count == 'Null' ? 0 : $discount->usage_count;
                        if ($max_discont_count_in_total >= $discount->max_usage_count) {
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
            }
            elseif (strtolower($discount->customer_eligibility) == 'new user')  {
                $all_customers = false;
                $new_user = true;
                $specific_customers = false;
                $api_orders = ApiOrder::where('memberId', $contact_id)->first();
                if (!empty($api_orders)) {
                    $eligible = false;
                    $message = 'You are not eligible for this discount';
                } else {
                    $eligible = true;
                    if (!empty($discount->limit_per_user) && strtolower($discount->max_discount_uses) == 'limit for user') {
                        $discount_per_user = true;
                        $customer_discount_count = CustomerDiscountUses::where('contact_id', $contact_id)->where('discount_id', $discount->id)->count();
                        if ($customer_discount_count >= $discount->limit_per_user) {
                            $max_uses = false;
                            $message = 'You have reached the maximum usage of this discount';   
                        } else {
                            $max_uses = true;
                            $message = 'Discount applied successfully';
                        }
                    } elseif(!empty($discount->max_usage_count) && strtolower($discount->max_discount_uses) == 'limit max times') {
                        $discount_max_times = true;
                        $max_discont_count_in_total = $discount->usage_count == 'Null' ? 0 : $discount->usage_count;
                        if ($max_discont_count_in_total >= $discount->max_usage_count) {
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
            } 
            else {
                $all_customers = true;
                $new_user = false;
                $specific_customers = false;
                $eligible = true;
                if (!empty($discount->limit_per_user) && strtolower($discount->max_discount_uses) == 'limit for user') {
                    $discount_per_user = true;
                    $customer_discount_count = CustomerDiscountUses::where('contact_id', $contact_id)->where('discount_id', $discount->id)->count();
                    if ($customer_discount_count >= $discount->limit_per_user) {
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
            'new_user' => $new_user,
            'all_customers'=> $all_customers,
            'eligible' => $eligible,
            'max_uses' => $max_uses,
            'discount_per_user' => $discount_per_user,
            'discount_max_times' => $discount_max_times,
            'message' => $message,
            'discount_variation' => $discount_variation,
            'discount_variation_value' => $discount_variation_value,
            'max_discount_uses_none' => $max_discount_uses_none,
            'discount' => $discount
        ]);
    }

    public function get_shipping_rate($products_weight, $user_address, $selected_shipment_quotes,$shipping_quotes,$shipment_prices ,$shipment_price , $product_width , $product_height , $product_length , $get_user_default_shipping_address , $get_user_default_billing_address) {
        $client = new \GuzzleHttp\Client();
        $ship_station_host_url = config('services.shipstation.host_url');
        $ship_station_api_key = config('services.shipstation.key');
        $ship_station_api_secret = config('services.shipstation.secret');
        $carrier_code_2 = AdminSetting::where('option_name', 'shipping_carrier_code_2')->first();
        $service_code_2 = AdminSetting::where('option_name', 'shipping_service_code_2')->first();
        $shipping_carrier_code = null;
        $error = false;
        foreach ($shipping_quotes as $quote) {
            if (!empty($quote->selected_shipping_quote)) {
                $shipping_carrier_code = $quote->carrier_code;
                $data = [
                    'carrierCode' => $products_weight > 150 ? $carrier_code_2->option_value : $quote->carrier_code,
                    'serviceCode' => $products_weight > 150 ? $service_code_2->option_value : null,
                    'fromPostalCode' => '95826',
                    'toCountry' => 'US',
                    'toPostalCode' => '95899',
                    'toState' => !empty($get_user_default_shipping_address->DeliveryState) ? $get_user_default_shipping_address->DeliveryState : $get_user_default_billing_address->BillingState,
                    // 'toCity' => !empty($get_user_default_shipping_address->DeliveryCity) ? $get_user_default_shipping_address->DeliveryCity : $get_user_default_billing_address->BillingCity,
                    'weight' => [
                        'value' => $products_weight,
                        'units' => 'pounds'
                    ],
                    'dimensions' => [
                        'units' => 'inches',
                        'length' => $product_length,
                        'width' => $product_width,
                        'height' => $product_height,
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
                    // $error = true;
                    $shipment_prices[] = null;
                    $e->getMessage();
                }
            } 
        }
        // if ($shipment_prices == null) {
        //     return [
        //         'shipment_prices' => null,
        //         'shipment_price' => 0,
        //         'shipping_carrier_code' => $carrier_code_2->option_value,    
        //     ];
        // }
        return [
            'shipment_prices' => !empty($shipment_prices) ? $shipment_prices[0] : null,
            'shipment_price' => $shipment_price,
            'shipping_carrier_code' => $products_weight > 150 ? $carrier_code_2->option_value : $shipping_carrier_code,    
        ];
    }

    public function get_shipping_rate_greater($products_weight, $user_address , $selected_shipment_quotes ,$shipping_quotes, $shipment_prices, $shipment_price ,$product_width , $product_height , $product_length ,$get_user_default_shipping_address , $get_user_default_billing_address) {
        $client = new \GuzzleHttp\Client();
        $ship_station_host_url = config('services.shipstation.host_url');
        $ship_station_api_key = config('services.shipstation.key');
        $ship_station_api_secret = config('services.shipstation.secret');
        $carrier_code_2 = AdminSetting::where('option_name', 'shipping_carrier_code_2')->first();
        $service_code_2 = AdminSetting::where('option_name', 'shipping_service_code_2')->first();
        $data = [
            'carrierCode' => $carrier_code_2->option_value,
            'serviceCode' => $service_code_2->option_value,
            'fromPostalCode' => '95826',
            'toCountry' => 'US',
            'toPostalCode' => !empty($get_user_default_shipping_address->DeliveryZip) ? $get_user_default_shipping_address->DeliveryZip :  $get_user_default_billing_address->BillingZip,
            'toState' => !empty($get_user_default_shipping_address->DeliveryState) ? $get_user_default_shipping_address->DeliveryState : $get_user_default_billing_address->BillingState,
            // 'toCity' => !empty($get_user_default_shipping_address->DeliveryCity) ? $get_user_default_shipping_address->DeliveryCity : $get_user_default_billing_address->BillingCity,
            'weight' => [
                'value' => $products_weight,
                'units' => 'pounds'
            ],
            'dimensions' => [
                'units' => 'inches',
                'length' => $product_length,
                'width' => $product_width,
                'height' => $product_height,
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
            $shipment_prices = !empty($shipping_response) ? $shipping_response : null;
            $shipment_price = !empty($shipment_prices) ? $shipment_prices[0]->shipmentCost + $shipment_prices[0]->otherCost : 0;
            return [
                'shipment_prices' => !empty($shipment_prices) ? $shipment_prices : null,
                'shipment_price' => $shipment_price,
                'shipping_carrier_code' => $carrier_code_2->option_value,    
            ];
        } catch (\Exception $e) {
            $e->getMessage();
            return [
                'shipment_prices' => null,
                'shipment_price' => 0,
                'shipping_carrier_code' => $carrier_code_2->option_value,    
            ];
        }
        
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
                $total_amount = $charge->amount / 100; // Convert amount from cents to dollars
                $refundAmount = $charge->amount_refunded / 100; // Convert amount from cents to dollars
                $order_id = $charge->metadata->order_id;
                $currentOrder = ApiOrder::find($order_id);
                $order_contact = Contact::where('contact_id', $currentOrder->memberId)->first();

                if (!empty($currentOrder) && ($refundAmount < $total_amount)) {
                    $currentOrder->payment_status = 'partially refunded';
                    $currentOrder->is_refunded = 2;
                    $currentOrder->isApproved = 4;
                    $currentOrder->save();


                    $order_comment = new OrderComment;
                    $order_comment->order_id = $order_id;
                    $order_comment->comment = 'Order marked as partially refunded through webhook. (charge.partaillyRefunded)';
                    $order_comment->save();

                    Log::info('Refund processed for order ID: ' . $order_id . ', Amount: $' . $refundAmount);
                    
                } else {
                    
                    $main_order = ApiOrder::find($order_id);
                    $main_order->payment_status = 'refunded';
                    $main_order->is_refunded = 1;
                    $main_order->isApproved = 3;
                    $main_order->save();


                    $order_comment = new OrderComment;
                    $order_comment->order_id = $order_id;
                    $order_comment->comment = 'Order marked as refunded through webhook. (charge.refunded)';
                    $order_comment->save();


                    
                    
                    if (!empty($main_order) && $main_order->is_shipped == 1 && $main_order->label_created == 1) {
                        $void_shipment = LabelHelper::void_label($main_order);

                        $order_comment = new OrderComment;
                        $order_comment->order_id = $order_id;
                        $order_comment->comment = 'Shipment label voided in ShipStation. (void_shipment)';
                        $order_comment->save();
                    }


                    $shipstation_order_status = 'update_order';
                    $shiping_order = UserHelper::shipping_order($order_id , $currentOrder , $order_contact , $shipstation_order_status);

                    $order_comment = new OrderComment;
                    $order_comment->order_id = $order_id;
                    $order_comment->comment = 'Order status cancelled in ShipStation. (cancel_order)';
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


    public function select_default_shipping_address(Request $request) {
        $contact_id = $request->contact_id;
        $address_id = $request->address_id;
        if (!empty($contact_id) && !empty($address_id)) {
            $contact_addresses = ContactsAddress::where('contact_id', $contact_id)->where('address_type' , 'Shipping')->get();
            if (count($contact_addresses) == 0) {
                return response()->json(['status' => 400, 'message' => 'No address found']);
            } else {
                foreach ($contact_addresses as $contact_address) {
                    $contact_address->is_default = 0;
                    $contact_address->save();
                }

                $selected_address = ContactsAddress::where('id', $address_id)->first();
                $selected_address->is_default = 1;
                $selected_address->save();
                return response()->json(['status' => 200, 'message' => 'Shipping address selected successfully']);
            }
        } else {
            return response()->json(['status' => 400, 'message' => 'Contact  is required']);
        }
        
    }
    

    public function select_default_billing_address(Request $request) {
        $contact_id = $request->contact_id;
        $address_id = $request->address_id;
        if (!empty($contact_id) && !empty($address_id)) {
            $contact_addresses = ContactsAddress::where('contact_id', $contact_id)->where('address_type' , 'Billing')->get();
            if (count($contact_addresses) == 0) {
                return response()->json(['status' => 400, 'message' => 'No address found']);
            } else {
                foreach ($contact_addresses as $contact_address) {
                    $contact_address->is_default = 0;
                    $contact_address->save();
                }

                $selected_address = ContactsAddress::where('id', $address_id)->first();
                $selected_address->is_default = 1;
                $selected_address->save();
                return response()->json(['status' => 200, 'message' => 'Billing address selected successfully']);
            }
        } else {
            return response()->json(['status' => 400, 'message' => 'Contact  is required']);
        }
        
    }



    public function get_shipping_rate_new($products_weight, $user_address, $selected_shipment_quotes,$shipping_quotes,$shipment_prices ,$shipment_price , $product_width , $product_height , $product_length , $get_user_default_shipping_address , $get_user_default_billing_address ,$productTotal) {
        
        $shipment_prices = [];
        $client = new \GuzzleHttp\Client();
        $ship_station_host_url = config('services.shipstation.host_url');
        $ship_station_api_key = config('services.shipstation.key');
        $ship_station_api_secret = config('services.shipstation.secret');
        $carrier_code_2 = AdminSetting::where('option_name', 'shipping_carrier_code_2')->first();
        $service_code_2 = AdminSetting::where('option_name', 'shipping_service_code_2')->first();
        $shipping_carrier_code = null;
        $error = false;
        $selected_shipping_methods = [];
        $get_shipping_api_response = [];
        $shipping_quotes_settings = ShippingQuoteSetting::where('status', 1)->get();
        if (count($shipping_quotes_settings) == 0) {
            return [
                'shipment_prices' => null,
                'shipment_price' => 0,
                'shipping_carrier_code' => $carrier_code_2->option_value,    
            ];
        }

        foreach ($shipping_quotes_settings as $quote) {
            if (!empty($quote->service_code)) {
                // Prepare data for ShipStation request
                $shipping_carrier_code = $quote->carrier_code;
                $data = [
                    'carrierCode' => $products_weight > 150 ? $carrier_code_2->option_value : $quote->carrier_code,
                    'serviceCode' => $products_weight > 150 ? $service_code_2->option_value : $quote->service_code,
                    'fromPostalCode' => '95826', // Default sender postal code
                    'toCountry' => 'US',
                    'toPostalCode' => $get_user_default_shipping_address->DeliveryZip ?? $get_user_default_billing_address->BillingZip,
                    'toState' => $get_user_default_shipping_address->DeliveryState ?? $get_user_default_billing_address->BillingState,
                    'weight' => [
                        'value' => $products_weight,
                        'units' => 'pounds',
                    ],
                    'dimensions' => [
                        'units' => 'inches',
                        'length' => $product_length,
                        'width' => $product_width,
                        'height' => $product_height,
                    ],
                    // 'confirmation' => floatval($productTotal) > floatval(499) ? 'signature' : 'delivery',
                ];
    
                $headers = [
                    'Authorization' => 'Basic ' . base64_encode($ship_station_api_key . ':' . $ship_station_api_secret),
                    'Content-Type' => 'application/json',
                ];
    
                try {
                    // Send the request to ShipStation API
                    $response = $client->post($ship_station_host_url, [
                        'headers' => $headers,
                        'json' => $data,
                    ]);
    
                    $statusCode = $response->getStatusCode();
                    $responseBody = $response->getBody()->getContents();
                    $shipping_response = json_decode($responseBody);
    
                    // Store the response if it's valid
                    if (!empty($shipping_response)) {
                        $get_shipping_api_response[] = $shipping_response;
                        $shipment_price = !empty($shipment_prices) ? $shipment_prices[0]->shipmentCost + $shipment_prices[0]->otherCost : 0;
                    }
                } catch (\Exception $e) {
                    // Handle error, log it
                    Log::error('Shipping API Error: ' . $e->getMessage());
                    // return [
                    //     'shipment_prices' => null,
                    //     'shipment_price' => 0,
                    //     'shipping_carrier_code' => $carrier_code_2->option_value,    
                    // ];
                    continue; // Skip this quote and proceed with the next one
                }
            }
        }


        if (!empty($get_shipping_api_response)) {
            // dd($get_shipping_api_response);
            // Merge the shipping responses from ShipStation
            // $mergedArray = count($get_shipping_api_response) > 1 ?  array_merge($get_shipping_api_response[0], $get_shipping_api_response[1] , $get_shipping_api_response[2]) : $get_shipping_api_response[0];
            if (count($get_shipping_api_response) > 1) {
                $mergedArray = array_merge(...array_slice($get_shipping_api_response, 0, 3));
            } else {
                $mergedArray = $get_shipping_api_response[0] ?? [];
            }
            $selected_shipping_methods = []; // Initialize the selected shipping methods array
            
            // Populate selected shipping methods based on the settings
            if (count($shipping_quotes_settings) > 0) {
                foreach ($shipping_quotes_settings as $quote) {
                    if (!empty($quote->service_code)) {
                        $selected_shipping_methods[] = [
                            'carrier_code' => $quote->carrier_code,
                            'service_code' => $quote->service_code,
                            'surcharge_type' => $quote->surcharge_type,
                            'surcharge_amount' => $quote->surcharge_value,
                        ];
                    }
                }
            }
        
            // Add surcharge values to the merged array based on carrier code
            foreach ($mergedArray as $key => $shipping_info) {
                foreach ($selected_shipping_methods as $method) {
                    if ($shipping_info->serviceCode === $method['service_code']) {
                        // Add surcharge information
                        $mergedArray[$key]->surcharge_type = $method['surcharge_type'];
                        $mergedArray[$key]->surcharge_amount = $method['surcharge_amount'];

                        break; // No need to check other methods once a match is found
                    }
                }
            }
            return [
                'shipment_prices' => $mergedArray,
                'shipment_price' => $shipment_price,
                'shipping_carrier_code' => $products_weight > 150 ? $carrier_code_2->option_value : $shipping_carrier_code,    
                'selected_shipping_methods' => $selected_shipping_methods,
            ];
        }
        
        else {
            return [
                'shipment_prices' => null,
                'shipment_price' => 0,
                'shipping_carrier_code' => null,    
            ];
        }

        
    }


    public function upgrade_shipment_prices($products_weight, $user_address, $selected_shipment_quotes,$shipping_quotes,$shipment_prices ,$shipment_price , $product_width , $product_height , $product_length , $get_user_default_shipping_address , $get_user_default_billing_address ,$productTotal) {
        
        $shipment_prices = [];
        $client = new \GuzzleHttp\Client();
        $ship_station_host_url = config('services.shipstation.host_url');
        $ship_station_api_key = config('services.shipstation.key');
        $ship_station_api_secret = config('services.shipstation.secret');
        $carrier_code_2 = AdminSetting::where('option_name', 'shipping_carrier_code_2')->first();
        $service_code_2 = AdminSetting::where('option_name', 'shipping_service_code_2')->first();
        $shipping_carrier_code = null;
        $error = false;
        $selected_shipping_methods = [];
        $get_shipping_api_response = [];
        $shipping_quotes_settings = ShippingQuoteSetting::where('status', 1)->get();
        if (count($shipping_quotes_settings) == 0) {
            return [
                'shipment_prices' => null,
                'shipment_price' => 0,
                'shipping_carrier_code' => $carrier_code_2->option_value,    
            ];
        }

        foreach ($shipping_quotes_settings as $quote) {
            if (!empty($quote->service_code)) {
                // Prepare data for ShipStation request
                $shipping_carrier_code = $quote->carrier_code;
                $data = [
                    'carrierCode' => $products_weight > 150 ? $carrier_code_2->option_value : $quote->carrier_code,
                    'serviceCode' => $products_weight > 150 ? $service_code_2->option_value : $quote->service_code,
                    'fromPostalCode' => '95826', // Default sender postal code
                    'toCountry' => 'US',
                    'toPostalCode' => $get_user_default_shipping_address->DeliveryZip ?? $get_user_default_billing_address->BillingZip,
                    'toState' => $get_user_default_shipping_address->DeliveryState ?? $get_user_default_billing_address->BillingState,
                    'weight' => [
                        'value' => $products_weight,
                        'units' => 'pounds',
                    ],
                    'dimensions' => [
                        'units' => 'inches',
                        'length' => $product_length,
                        'width' => $product_width,
                        'height' => $product_height,
                    ],
                    // 'confirmation' => floatval($productTotal) > floatval(499) ? 'signature' : 'delivery',
                ];
    
                $headers = [
                    'Authorization' => 'Basic ' . base64_encode($ship_station_api_key . ':' . $ship_station_api_secret),
                    'Content-Type' => 'application/json',
                ];
    
                try {
                    // Send the request to ShipStation API
                    $response = $client->post($ship_station_host_url, [
                        'headers' => $headers,
                        'json' => $data,
                    ]);
    
                    $statusCode = $response->getStatusCode();
                    $responseBody = $response->getBody()->getContents();
                    $shipping_response = json_decode($responseBody);
    
                    // Store the response if it's valid
                    if (!empty($shipping_response)) {
                        $get_shipping_api_response[] = $shipping_response;
                        $shipment_price = !empty($shipment_prices) ? $shipment_prices[0]->shipmentCost + $shipment_prices[0]->otherCost : 0;
                    }
                } catch (\Exception $e) {
                    // Handle error, log it
                    Log::error('Shipping API Error: ' . $e->getMessage());
                    // return [
                    //     'shipment_prices' => null,
                    //     'shipment_price' => 0,
                    //     'shipping_carrier_code' => $carrier_code_2->option_value,    
                    // ];
                    continue; // Skip this quote and proceed with the next one
                }
            }
        }


        if (!empty($get_shipping_api_response)) {
            // dd($get_shipping_api_response);
            // Merge the shipping responses from ShipStation
            // $mergedArray = count($get_shipping_api_response) > 1 ?  array_merge($get_shipping_api_response[0], $get_shipping_api_response[1] , $get_shipping_api_response[2]) : $get_shipping_api_response[0];
            if (count($get_shipping_api_response) > 1) {
                $mergedArray = array_merge(...array_slice($get_shipping_api_response, 0, 3));
            } else {
                $mergedArray = $get_shipping_api_response[0] ?? [];
            }
            $selected_shipping_methods = []; // Initialize the selected shipping methods array
            
            // Populate selected shipping methods based on the settings
            if (count($shipping_quotes_settings) > 0) {
                foreach ($shipping_quotes_settings as $quote) {
                    if (!empty($quote->service_code)) {
                        $selected_shipping_methods[] = [
                            'carrier_code' => $quote->carrier_code,
                            'service_code' => $quote->service_code,
                            'surcharge_type' => $quote->surcharge_type,
                            'surcharge_amount' => $quote->surcharge_value,
                        ];
                    }
                }
            }
        
            // Add surcharge values to the merged array based on carrier code
            foreach ($mergedArray as $key => $shipping_info) {
                foreach ($selected_shipping_methods as $method) {
                    if ($shipping_info->serviceCode === $method['service_code']) {
                        // Add surcharge information
                        $mergedArray[$key]->surcharge_type = $method['surcharge_type'];
                        $mergedArray[$key]->surcharge_amount = $method['surcharge_amount'];

                        break; // No need to check other methods once a match is found
                    }
                }
            }
            return [
                'shipment_prices' => $mergedArray,
                'shipment_price' => $shipment_price,
                'shipping_carrier_code' => $products_weight > 150 ? $carrier_code_2->option_value : $shipping_carrier_code,    
                'selected_shipping_methods' => $selected_shipping_methods,
            ];
        }
        
        else {
            return [
                'shipment_prices' => null,
                'shipment_price' => 0,
                'shipping_carrier_code' => null,    
            ];
        }

        
    }
    
}
