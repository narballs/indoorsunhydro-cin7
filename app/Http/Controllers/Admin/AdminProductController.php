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
        $do_search = $request->get('do_search');
        $weight_filter_type = $request->get('weight_filter_type');
        $weight_value = $request->get('weight_value');
        $price_filter_type = $request->get('price_filter_type');
        $price_value = $request->get('price_value');
        $products_query = Product::with('categories', 'options' , 'options.defaultPrice');
        
        if(isset($search)) {
            $products_query
            ->where('status', '!=', 'Inactive')
            ->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('code', 'like', '%' . $search . '%');

            // ->orWhere('status', 'like', '%' . $search . '%')
            // ->orWhere('retail_price', 'like', '%' . $search . '%');
        }
        if (($weight_filter_type != null) && (($weight_value != null) && (($price_filter_type == null) && ($price_value == null)))) {
            if (isset($weight_filter_type) && isset($weight_value)) {
                $operation = '';

                if ($weight_filter_type == 'equal_to') {
                    $operation = '=';
                } elseif($weight_filter_type == 'less_than') {
                    $operation = '<';
                } elseif($weight_filter_type == 'greater_than') {
                    $operation = '>';
                }

                $products_query->where('status', '!=', 'Inactive')
                ->whereHas('options', function ($query) use ($weight_value, $operation) {
                    $query->where(function ($query) use ($weight_value, $operation) {
                        $query->whereRaw("CONVERT(optionWeight, SIGNED) $operation ?", [$weight_value])
                            ->orWhereRaw("CONVERT(optionWeight, DECIMAL(10,2)) $operation ?", [$weight_value]);
                    })
                    ->where('status', '!=', 'disabled');
                });
            }
        } 

        elseif(($price_filter_type != null) && ($price_value != null) && (($weight_filter_type == null) && ($weight_value == null))) {
            if (isset($price_filter_type) && isset($price_value)) {
                $operation = '';

                if ($price_filter_type == 'equal_to') {
                    $operation = '=';
                } elseif($price_filter_type == 'less_than') {
                    $operation = '<';
                } elseif($price_filter_type == 'greater_than') {
                    $operation = '>';
                }

                $products_query->where('status', '!=', 'Inactive')
                ->whereHas('options' , function($query){
                    $query->where('status', '!=', 'disabled');
                })
                ->whereHas('options.defaultPrice' , function($query) use ($price_value, $operation){
                    $query->where('retailUSD', $operation, $price_value);
                });
            }
        }
        elseif ((($price_filter_type != null) && ($price_value != null)) && (($weight_filter_type != null) && ($weight_value != null))) {
            if ((isset($price_filter_type) && isset($price_value)) && (isset($weight_filter_type) && isset($weight_value))) {
                $operation = '';
                $weight_operation = '';

                if ($price_filter_type == 'equal_to') {
                    $operation = '=';
                } elseif($price_filter_type == 'less_than') {
                    $operation = '<';
                } elseif($price_filter_type == 'greater_than') {
                    $operation = '>';
                }

                if ($weight_filter_type == 'equal_to') {
                    $weight_operation = '=';
                } elseif($weight_filter_type == 'less_than') {
                    $weight_operation = '<';
                } elseif($weight_filter_type == 'greater_than') {
                    $weight_operation = '>';
                }

                $products_query->where('status', '!=', 'Inactive')
                ->whereHas('options', function ($query) use ($weight_value, $weight_operation) {
                    $query->where(function ($query) use ($weight_value, $weight_operation) {
                        $query->whereRaw("CONVERT(optionWeight, SIGNED) $weight_operation ?", [$weight_value])
                            ->orWhereRaw("CONVERT(optionWeight, DECIMAL(10,2)) $weight_operation ?", [$weight_value]);
                    })
                    ->where('status', '!=', 'disabled');
                })
                ->whereHas('options.defaultPrice' , function($query) use ($price_value, $operation){
                    $query->where('retailUSD', $operation, $price_value);
                });
            }
        }
        
        $download_csv = $request->get('download_csv');
        if ($download_csv == '1') {
            $products = $products_query->get();
            if (!empty($products)) {

                $csv_data = [];
                $csv_data[] = [
                    'Product Name',
                    'Product Code',
                    'Product Status',
                    'Product Retail Price',
                    'Product Weight',
                ];

                foreach ($products as $product) {
                    $retail_price = 0;
                    if (!empty($product->options[0]->defaultPrice->retailUSD)) {
                        $retail_price = $product->options[0]->defaultPrice->retailUSD;
                    }   
                    $csv_data[] = [
                        $product->name,
                        $product->code,
                        $product->status,
                        $retail_price,
                        isset($product->options[0]) ? $product->options[0]->optionWeight : '',
                    ];
                }

                $csv_file_name = 'products.csv';
                $file_path = public_path($csv_file_name);
                $file = fopen($file_path, 'w');
                foreach ($csv_data as $line) {
                    fputcsv($file, $line);
                }
                fclose($file);

                $headers = array(
                    'Content-Type' => 'text/csv',
                );

                return response()->download($file_path, $csv_file_name, $headers);
            }

        }
        else {
            $products = $products_query->paginate(10)->appends(request()->query());
        }
       
        
        return view('admin/products', compact(
            'products', 
            'search',
            'do_search',
            'weight_filter_type',
            'weight_value',
            'price_filter_type',
            'price_value'
        ));
    }

    public function show($id) {
        $product = Product::where('id', $id)->with('options.price','categories','brand')->first();
        $parent_category_name = '';
        if (!empty($product->categories->category_id)) {
            $product_category = $product->categories;
            $parent_id = $product_category->parent_id;

            $category = $product_category->category_id;
            $parent_category = Category::where('category_id', $parent_id)->first();

            $parent_category_name = !empty($parent_category->name) ? $parent_category->name : '';
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