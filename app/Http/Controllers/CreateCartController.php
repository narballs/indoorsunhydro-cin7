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
        // foreach($list->list_products as $list_product) {
        //dd($list->list_products);
        //$count = 0;
        foreach($list->list_products as $key=>$list_product) {
            $cart[$key+1] = [
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
         $data = [
            'name' =>  'waqas',
            'subject' => 'Share Test',
            'link' => 'http://indoorsunhydro.local/create-cart/'.$id,
            'from' => 'wqszeeshan@gmail.com'
        ];
        $subject = '';
                //$adminTemplate = 'emails.admin-share';
                $data['email'] = 'wqszeeshan@gmail.com';

                MailHelper::sendMailNotification('emails.admin-share', $data);
        // dd($cart);
      //session()->put('cart', $cart);
        // return redirect()->route('add.to.cart')->with('var', $list);


        //return view('index', compact('categories'));
    }
}