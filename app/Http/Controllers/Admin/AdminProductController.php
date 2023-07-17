<?php

namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Pricing;


class AdminProductController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:Admin']);

    }
    
    public function index(Request $request) {
        $search = $request->get('search');
        $products = Product::with('categories', 'options')->paginate(10);
        if(isset($search)) {
            $products = Product::with('categories', 'options')->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('code', 'like', '%' . $search . '%')
            ->orWhere('status', 'like', '%' . $search . '%')
            ->orWhere('retail_price', 'like', '%' . $search . '%')
            ->paginate(10);
        }
        
         return view('admin/products', compact('products' , 'search'));

    }

    public function show($id) {
        $product = Product::where('id', $id)->with('options.price','categories','brand')->first();
         //dd($product);
        $parent_category_name = '';
        if (!empty($product->categories->category_id)) {
            $product_category = $product->categories;
            //dd($product_category);exit;

            $parent_id = $product_category->parent_id;


            $category = $product_category->category_id;
            $parent_category = Category::where('category_id', $parent_id)->first();
            $parent_category_name = $parent_category->name;
        }
        return view('admin/product-detail', compact('product', 'parent_category_name'));
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