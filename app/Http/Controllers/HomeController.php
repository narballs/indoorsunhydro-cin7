<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Category;
use App\Models\Product;
use Auth;
use App\User;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    //   session()->forget('cart');
       
    //     session()->flush();
       
        //dd($user_id);
        $categories = Category::orderBy('name', 'ASC')
            ->with('products')->where('is_active', 1)
            ->get();
        // $product_brands = Product::select('brand')
        // ->groupBy('brand')
        // ->orderBy('brand', 'desc')
        // ->take(9)
        // ->get();
        
        // dd($product_brands->getProductsByBrand());

        // foreach($product_brands as $brand) {
        //     echo $brand->brand;
        // }
        
        //echo '<pre>';var_export($product_brands);exit;
            // foreach($categories as $category) {
            //     foreach($category->products as $p) {
            //         echo $p['images'];
            //     }
            // }
            // exit;
            //echo '<pre>';var_export($categories);exit;

            
        
        
        return view('index', compact('categories'));
    }
}
