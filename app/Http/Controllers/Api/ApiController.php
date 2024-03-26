<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use \App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\Category;
use App\Models\Pricingnew;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Support\Facades\Auth ;

class ApiController extends Controller
{
    public function products() {
        // $price_column = null;
        // $default_price_column = AdminSetting::where('option_name', 'default_price_column')->first();
        // if (!empty($default_price_column)) {
        //     $price_column = $default_price_column->option_value;
        // }
        // else {
        //     $price_column = 'retailUSD';
        // }
        // $product_categories = Category::where('is_active', 1)->pluck('category_id')->toArray();
        // $all_products_ids = Product::whereIn('category_id' , $product_categories)
        // ->pluck('product_id')->toArray();
        // $product_options_ids = ProductOption::whereIn('product_id' , $all_products_ids)
        // ->where('status', '!=', 'Disabled')
        // // ->where('optionWeight', '>', 0)
        // ->pluck('option_id')->toArray();
        // $product_pricing_option_ids = Pricingnew::whereIn('option_id' , $product_options_ids)
        // ->where($price_column , '>' , 0)
        // ->pluck('option_id')
        // ->toArray();
        // $products_ids = ProductOption::whereIn('option_id' , $product_pricing_option_ids)
        // ->pluck('product_id')->toArray();
        $products = Product::with('options','options.defaultPrice','product_brand','product_image','categories')
        // ->whereIn('product_id' , $porducts_ids)
        // ->where('status' , '!=' , 'Inactive')
        // ->where('barcode' , '!=' , '')
        ->get();
        return response()->json([
            'products' => $products
        ]);
    }
}
