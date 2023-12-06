<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $products = Product::with('options', 'options.defaultPrice','brand', 'options.products','categories' ,'apiorderItem' , 'product_stock')
        ->whereHas('product_stock', function ($query) {
            $query->where('stockAvailable', '>=', 5);
        })
        ->where('status' , '!=' , 'Inactive')
        ->orderby('created_at', 'desc')
        ->take(4)
        ->get();
        return view('landing_page',compact('products'));
    }
}
