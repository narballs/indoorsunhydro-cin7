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
use Session;

class CreateCartController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create_cart($id)
    {
        $list = BuyList::where('id', $id)->with('list_products.product.options')->first();

        foreach ($list->list_products as $key => $list_product) {
            $cart[$list_product->id] = [
                "product_id" => $list_product->product_id,
                "name" => $list_product->product->name,
                "quantity" => $list_product->quantity,
                "price" => $list_product->product->retail_price,
                "code" => $list_product->product->code,
                "image" => $list_product->product->images,
                'option_id' => $list_product->option_id,
                "slug" => $list_product->product->slug,
            ];
            session()->put('cart', $cart);
        }
        return redirect()->route('cart');
    }
}
