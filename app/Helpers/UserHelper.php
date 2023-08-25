<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;

use App\Models\Contact;
use App\Models\Cart;
use App\Models\Pricingnew;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

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
            $get_session_cart = Cart::where('user_id' , $getSelectedContact->user_id)->get();
            $getPriceColumn = UserHelper::getUserPriceColumn($is_admin = false , $user_id = $getSelectedContact->user_id);
            if (count($get_session_cart) > 0){
                foreach($get_session_cart as $cartItems){
                    $product_pricing = Pricingnew::where('option_id' , $cartItems['option_id'])->first();
                    $product_price = $product_pricing->$getPriceColumn;
                    $cart = Cart::where('user_id' , $user_id)->where('product_id' , $cartItems['product_id'])->first();
                    if(!empty($cart)){
                        $cart->price = $product_price;
                        $cart->save();
                    }
                    Session::forget('cart');
                    $cart = [
                        $cartItems['qoute_id'] => [
                            "product_id" => $cartItems['product_id'],
                            "name" => $cartItems['name'],
                            "quantity" => $cartItems['quantity'],
                            "price" => $cart['price'],
                            "code" => $cartItems['code'],
                            "image" => $cartItems['image'],
                            'option_id' => $cartItems['option_id'],
                            "slug" => $cartItems['slug'],
                        ]
                    ];
                    Session::put('cart', $cart);
                }
            }
            
        }
        

        return response()->json([
            'status' => '204',
            'message' => 'Company Switch Successfully !'
        ]);
    }

    public static function getUserPriceColumn($is_admin = false , $user_id = null) {
        $price_column = 'retailUSD';
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
}