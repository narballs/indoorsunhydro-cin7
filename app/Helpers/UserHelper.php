<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;

use App\Models\Contact;
use App\Models\Cart;
use App\Models\Pricingnew;
use App\Models\ApiOrder;
use App\Models\ApiOrderItem;
use App\Models\ProductOption;
use App\Models\AdminSetting;
use Google\Service\Calendar\Setting;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserHelper
{
    /**
     * get user option_name value.
     *
     * @param  method
     * @param  url
     * @param  body
     * @return extra
     */
    public static function getAllMemberIds($user) {
        $user_ids = $parent_ids = $contact_ids = [];

        $contacts_by_email = Contact::where('email', $user->email)->get();

        if (!empty($contacts_by_email)) {
            foreach ($contacts_by_email as $email_contact) {
                if (!empty($email_contact->user_id)) {
                    $user_ids[] = $email_contact->user_id;
                }

                if (!empty($email_contact->parent_id)) {
                    $parent_ids[] = $email_contact->parent_id;
                }

                if (!empty($email_contact->contact_id)) {
                    $contact_ids[] = $email_contact->contact_id;
                }
            }
        }

        $ids_array_1 = $ids_array_2 = $ids_array_3 = [];

        if (!empty($user_ids)) {
            $ids_array_1 = Contact::whereIn('user_id', $user_ids)->pluck('id')->toArray();
        }

        if (!empty($contact_ids)) {
            $ids_array_2 = Contact::whereIn('parent_id', $contact_ids)->pluck('id')->toArray();
        }

        if (!empty($parent_ids)) {
            $ids_array_3 = Contact::whereIn('contact_id', $parent_ids)->pluck('id')->toArray();
        }

        $member_ids = array_merge($ids_array_1, $ids_array_2, $ids_array_3);
        return $member_ids;
    }

    public static function switch_company($contact_id) {
        $user_id = Auth::id();
        $active_contact_id = $active_company = null;
        $new_register_contact = Contact::where('contact_id' , null)->where('user_id' , $user_id)->first();
        if (!empty($new_register_contact)) {
            $active_company = $new_register_contact->company;
            Session::put([
                'contact_id' => null,
                'company' => $active_company,
            ]);

        } else {
            $contact = Contact::where('contact_id', $contact_id)->where('status', '!=', 0)->first();
            if (!empty($contact)) {
                $active_contact_id = $contact->contact_id;
                $active_company = $contact->company;
            } 
            else {
                $contact = Contact::where('secondary_id', $contact_id)->where('status', '!=', 0)->first();
                if (!empty($contact)) {
                    $active_contact_id = $contact->secondary_id;
                    $active_company = $contact->company;
                    
                }
            }
            Session::put([
                'contact_id' => $active_contact_id,
                'company' => $active_company,
            ]);
            $getSelectedContact = Contact::where('company' , $active_company)->where('user_id' , $user_id)->first();
            $cartItems = Cart::where('user_id' , $getSelectedContact->user_id)->get();
            $all_cart_items = [];
            $getPriceColumn = UserHelper::getUserPriceColumn(false , $getSelectedContact->user_id);
            if (count($cartItems) > 0) {
                foreach ($cartItems as $cartItem) {
                    $productPricing = Pricingnew::where('option_id' , $cartItem['option_id'])->first();
                    $productPrice = $productPricing->$getPriceColumn;
                    $cart = Cart::where('user_id' , $user_id)->where('product_id' , $cartItem['product_id'])->first();
                    if (!empty($cart)) {
                        $cart->price = $productPrice;
                        $cart->save();
                    }
                    $all_cart_items[$cartItem['qoute_id']] = [
                        "product_id" => $cartItem['product_id'],
                        "name" => $cartItem['name'],
                        "quantity" => $cartItem['quantity'],
                        "price" => $cart['price'],
                        "code" => $cartItem['code'],
                        "image" => $cartItem['image'],
                        'option_id' => $cartItem['option_id'],
                        "slug" => $cartItem['slug'],
                    ];
                }
                session()->forget('cart');
                Session::put('cart', $all_cart_items);
            }
            
        }
        

        return response()->json([
            'status' => '204',
            'message' => 'Company Switch Successfully !'
        ]);
    }

    public static function getUserPriceColumn($is_admin = false , $user_id = null) {
        $price_column = null;
        $default_price_column = AdminSetting::where('option_name', 'default_price_column')->first();
        if (!empty($default_price_column)) {
            $price_column = $default_price_column->option_value;
        }
        else {
            $price_column = 'retailUSD';
        }
        if ($is_admin === false) {

            $user_id = Auth::id();
        }

        if (empty($user_id)) {
            return $price_column;
        }

        $company = Session::get('company');
        if (!empty($company)) {
            $contact = Contact::where('user_id', $user_id)
                ->where('company', $company)
                ->first();

            if (!empty($contact)) {
                // if it's parent-id and secondary-id both exists then get price column from parent contact
                if (!empty($contact->parent_id) && !empty($contact->secondary_id)) {
                    $parent_contact = Contact::where('contact_id', $contact->parent_id)
                        ->where('status', '!=', 0)
                        ->first();

                    if (!empty($parent_contact->priceColumn)) {
                        $price_column = $parent_contact->priceColumn;
                    }
                }
                else {
                    $price_column = $contact->priceColumn;
                }
            }    
        }


        return lcfirst($price_column);
    }

    public static function shipment_label() {
        $label_data = "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PDwvUHJvY1NldCBbL1BERiAvVGV4dCAvSW1hZ2VCIC9JbWFnZUMgL0ltYWdlSV0vWE9iamVjdDw8L2ltZzAgMSAwIFI+Pj4+L0NvbnRlbnRzIDIgMCBSL1BhcmVudCAzIDAgUj4+CmVuZG9iagozIDAgb2JqCjw8L1R5cGUvUGFnZXMvQ291bnQgMS9LaWRzWzQgMCBSXS9JVFhUKDUuMS4xKT4+CmVuZG9iago1IDAgb2JqCjw8L1R5cGUvQ2F0YWxvZy9QYWdlcyAzIDAgUj4+CmVuZG9iago2IDAgb2JqCjw8L1Byb2R1Y2VyKGlUZXh0U2hhcnAgNS4xLjEgXChjXCkgMVQzWFQgQlZCQSkvQ3JlYXRpb25EYXRlKEQ6MjAyMzA4MzAwMDI0NDEtMDcnMDAnKS9Nb2REYXRlKEQ6MjAyMzA4MzAwMDI0NDEtMDcnMDAnKT4+CmVuZG9iagp4cmVmCjAgNwowMDAwMDAwMDAwIDY1NTM1IGYgCjAwMDAwMDAwMTUgMDAwMDAgbiAKMDAwMDAzMDQxOCAwMDAwMCBuIAowMDAwMDMwNzEwIDAwMDAwIG4gCjAwMDAwMzA1NDggMDAwMDAgbiAKMDAwMDAzMDc3MyAwMDAwMCBuIAowMDAwMDMwODE4IDAwMDAwIG4gCnRyYWlsZXIKPDwvU2l6ZSA3L1Jvb3QgNSAwIFIvSW5mbyA2IDAgUi9JRCBbPGU1NWM3YTI5MDQ4YzVjMmZlOWVlNmFhY2E3YWUzZjcxPjwyYmVhYTI4MTU2OWQ2ZWE5N2RkMmFiNGUzMGQ4MjYwMj5dPj4Kc3RhcnR4cmVmCjMwOTUzCiUlRU9GCg==";
        return $label_data;
    }


    public static function shipping_order($order_id , $currentOrder , $order_contact) {
        $api_order = ApiOrder::where('id', $order_id)->first();
        $shipping_package = AdminSetting::where('option_name', 'shipping_package')->first();
        $order_items = ApiOrderItem::with('order.texClasses', 'product.options', 'product')->where('order_id', $order_id)->get();
        for ($i = 0; $i <= count($order_items) - 1; $i++){
            $items[] = [
                'name' => $order_items[0]->product->name,
                'sku' => $order_items[0]->product->code,
                'quantity' => $order_items[0]->quantity,
                'unitPrice' => $order_items[0]->price,
            ];  
        }

        $produts_weight = 0;
        foreach ($order_items as $order_item) {
            $product_options = ProductOption::where('product_id', $order_item['product_id'])->where('option_id' , $order_item['option_id'])->get();
            foreach ($product_options as $product_option) {
                $produts_weight += $product_option->optionWeight * $order_item['quantity'];
            }
        }
        
        $client = new \GuzzleHttp\Client();
        $shipstation_order_url = config('services.shipstation.shipment_order_url');
        $ship_station_api_key = config('services.shipstation.key');
        $ship_station_api_secret = config('services.shipstation.secret');
        $carrier_code = AdminSetting::where('option_name', 'shipping_carrier_code')->first();
        $service_code = AdminSetting::where('option_name', 'shipping_service_code')->first();
        $created_date = \Carbon\Carbon::parse($currentOrder->createdDate);
        $getDate =$created_date->format('Y-m-d');
        $getTime = date('H:i:s' ,strtotime($currentOrder->createdDate));
        $order_created_date = $getDate . 'T' . $getTime ;
        $calculate_tax = $currentOrder->total_including_tax - $currentOrder->productTotal;
        $tax = $calculate_tax - $currentOrder->shipment_price;
        $orderStatus = null;
        if ($api_order->shipstation_orderId == null) {
            if ($currentOrder->payment_status == 'paid') {
                $orderStatus = 'awaiting_shipment';
            } else {
                $orderStatus = 'awaiting_payment';
            }
        } else {
            $orderStatus = 'awaiting_shipment';
        }
        $data = [
            'orderNumber' => $order_id,
            'orderKey' => $currentOrder->reference,
            'orderDate' => $order_created_date,
            'carrierCode' => $carrier_code->option_value,
            'serviceCode' => $service_code->option_value,
            'orderStatus' => $orderStatus,
            'customerEmail'=> $order_contact->email,
            'packageCode' => !empty($shipping_package->option_value) ? $shipping_package->option_value : 'package',
            'shippingAmount' => number_format($currentOrder->shipment_price , 2),
            "amountPaid" => number_format($currentOrder->total_including_tax , 2),
            "taxAmount" => number_format($tax, 2),
            'shipTo' => [
                "name" => $order_contact->firstName .' '. $order_contact->lastName,
                "company" => $order_contact->company,
                "street1" => $order_contact->address1 ? $order_contact->address1 : $order_contact->postalAddress1,
                "street2" => $order_contact->address2 ? $order_contact->address2 : $order_contact->postalAddress2,
                "city" => $order_contact->city ? $order_contact->city : $order_contact->postalCity,
                "state" => $order_contact->state ? $order_contact->state : $order_contact->postalState,
                "postalCode" => $order_contact->postCode ? $order_contact->postCode : $order_contact->postalPostCode,
                "country"=>"US",
                "phone" => $order_contact->phone ? $order_contact->phone : $order_contact->mobile,
                // "residential"=>true
            ],
            'billTo' => [
                "name" => $order_contact->firstName . ' ' . $order_contact->lastName,
                "company" => $order_contact->company,
                "street1" => $order_contact->address1 ? $order_contact->address1 : $order_contact->postalAddress1,
                "street2" => $order_contact->address2 ? $order_contact->address2 : $order_contact->postalAddress2,
                "city" => $order_contact->city ? $order_contact->city : $order_contact->postalCity,
                "state" => $order_contact->state ? $order_contact->state : $order_contact->postalState,
                "postalCode" => $order_contact->postCode ? $order_contact->postCode : $order_contact->postalPostCode,
                "country"=>"US",
                "phone" => $order_contact->phone ? $order_contact->phone : $order_contact->mobile,
                // "residential"=>true
            ],
            'weight' => [
                'value' => $produts_weight,
                'units' => 'pounds'
            ],
            'items'=> $items
        ];
        $headers = [
            "Content-Type: application/json",
            'Authorization' => 'Basic ' . base64_encode($ship_station_api_key . ':' . $ship_station_api_secret),
        ];
        $responseBody = null;
        $response = $client->post($shipstation_order_url, [
            'headers' => $headers,
            'json' => $data,
        ]);
        $statusCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();
        return [
            'statusCode' => $statusCode,
            'responseBody' => json_decode($responseBody)
        ];
        
    }

    public static function switch_price_tier(Request $request) {
        $user_id = auth()->id();
        $company = session()->get('company');
        if (!$user_id) {
            $cart_items = $request->session()->get('cart');
            
        } elseif (empty($company) || (!$company) && (!empty($user_id))) {
            $cart_items = $request->session()->get('cart');
        } else {
            $getSelectedContact = Contact::where('company' , $company)->where('user_id' , $user_id)->first();
            $cartItems = Cart::where('user_id' , $getSelectedContact->user_id)->get();
            $cart_items = [];
            $getPriceColumn = UserHelper::getUserPriceColumn(false , $getSelectedContact->user_id);
            if (count($cartItems) > 0) {
                foreach ($cartItems as $cartItem) {
                    $productPricing = Pricingnew::where('option_id' , $cartItem['option_id'])->first();
                    $productPrice = $productPricing->$getPriceColumn;
                    $cart = Cart::where('user_id' , $user_id)->where('product_id' , $cartItem['product_id'])->first();
                    if (!empty($cart)) {
                        $cart->price = $productPrice;
                        $cart->save();
                    }
                    $cart_items[$cartItem['qoute_id']] = [
                        "product_id" => $cartItem['product_id'],
                        "name" => $cartItem['name'],
                        "quantity" => $cartItem['quantity'],
                        "price" => $cart['price'],
                        "code" => $cartItem['code'],
                        "image" => $cartItem['image'],
                        'option_id' => $cartItem['option_id'],
                        "slug" => $cartItem['slug'],
                    ];
                }
                session()->forget('cart');
                Session::put('cart', $cart_items);
            }
        }

        return $cart_items;
    }
}