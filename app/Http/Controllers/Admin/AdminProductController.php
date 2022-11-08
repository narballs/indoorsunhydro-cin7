<?php

namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use App\Models\Product;


class AdminProductController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('admin');
    // }
    
    public function index() {
        $products = Product::with('categories', 'options')->paginate(10);
         return view('admin/products', compact('products'));

    }

    public function show($id) {
        $product = Product::where('id', $id)->with('options')->first();
        dd($product);
    }

    public function addComments(Request $request)
    {
   
    }

    public function updateStatus(Request $request) {

    }

    public function create() {
  

    }

    public function show_api_order($id) {
  
    }


    public function order_full_fill(Request $request) {

    }
}