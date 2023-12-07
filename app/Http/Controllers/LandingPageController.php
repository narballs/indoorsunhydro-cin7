<?php

namespace App\Http\Controllers;

use App\Models\ApiOrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function index()
    {
        $products = Product::with('options', 'options.defaultPrice','brand', 'options.products','categories' ,'apiorderItem' , 'product_stock')
        // ->whereHas('product_stock', function ($query) {
        //     $query->where('stockAvailable', '>=', 5);
        // })
        ->where('stockAvailable' ,'>=', 5)
        ->where('status' , '!=' , 'Inactive')
        ->orderby('created_at', 'desc')
        ->take(4)
        ->get();

        $top_sellers = ApiOrderItem::with('product', 'product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as selling_count'))
            ->orderBy('selling_count' , 'DESC')
            ->groupBy('product_id')
            ->take(4)
            ->get();
        return view('landing_page',compact('products' , 'top_sellers'));
    }
}
