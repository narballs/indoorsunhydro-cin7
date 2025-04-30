<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Category;
use App\Models\Product;
use App\Models\BuyList;
use App\Models\ProductBuyList;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Helpers\MailHelper;
use App\Helpers\UserHelper;
use App\Models\Cart;
use Session;
use Illuminate\Support\Str;

class CreateCartController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create_cart(Request $request , $id)
    {
        // $list = BuyList::where('id', $id)->with('list_products.product.options')->first();
        $list = BuyList::where('id', $id)
        ->with('list_products.product.options', 'shipping_and_discount')
        ->whereHas('shipping_and_discount', function ($query) {
            $query->where(function ($q) {
                $q->where('expiry_date', '>=', now());
                // ->orWhereNull('expiry_date');
            })->where(function ($q) {
                $q->whereColumn('discount_limit', '>', 'discount_count');
                // ->orWhereNull('discount_limit');
            });
        })
        ->first();


        if (!$list) {
            return redirect()->back()->with('error', 'Buy list not found or expired.');
        }


        $hash_cart = $request->session()->get('cart_hash');
        $cart_hash_exist = session()->has('cart_hash');


        if ($cart_hash_exist == false) {
            $request->session()->put('cart_hash', Str::random(10));
        }

        foreach ($list->list_products as $key => $list_product) {
            foreach ($list_product->product->options as $option) {
                $retail_price = 0;
                $user_price_column = UserHelper::getUserPriceColumn();
                foreach ($option->price as $price) {
                    $retail_price = $price->$user_price_column;
                    if ($retail_price == 0) {
                        $retail_price = $price->sacramentoUSD;
                    }
                    if ($retail_price == 0) {
                        $retail_price = $price->retailUSD;
                    }
                }
            }

            $cart[$list_product->id] = [
                'qoute_id' => $list_product->id,
                "product_id" => $list_product->product_id,
                "name" => $list_product->product->name,
                "quantity" => $list_product->quantity,
                "price" => $retail_price,
                "code" => $list_product->product->code,
                "image" => $list_product->product->images,
                'option_id' => $list_product->option_id,
                "slug" => $list_product->product->slug,
                "cart_hash" => session()->get('cart_hash'),
                'user_id' => auth()->user() ? auth()->user()->id : 0,
                'contact_id' => !empty(session()->get('contact_id')) ? session()->get('contact_id') : null,
                'is_active' =>1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            Cart::create($cart[$list_product->id]); // Store the cart entry in the database
            session()->put('cart', $cart);
            session()->put('buy_list_id', $list->id);
        }

        return redirect()->route('cart');
    }
}
