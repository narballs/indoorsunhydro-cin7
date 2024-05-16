<?php

namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Pricing;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminProductController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:Admin']);

    }

    public function index(Request $request) {
        
        $price_column = null;
        $default_price_column = AdminSetting::where('option_name', 'default_price_column')->first();
        if (!empty($default_price_column)) {
            $price_column = $default_price_column->option_value;
        }
        else {
            $price_column = 'retailUSD';
        }
        $search = $request->get('search');
        $do_search = $request->get('do_search');
        $stock_filter_type = $request->get('stock_filter_type');
        $stock_value = $request->get('stock_value');
        $weight_filter_type = $request->get('weight_filter_type');
        $weight_value = $request->get('weight_value');
        $price_filter_type = $request->get('price_filter_type');
        $price_value = $request->get('price_value');
        $product_status = $request->get('product_status');
        $products_query = Product::with('categories', 'options' , 'options.defaultPrice');
        
        if(isset($search)) {
            $products_query
            ->where('status', '!=', 'Inactive')
            ->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('code', 'like', '%' . $search . '%');

            // ->orWhere('status', 'like', '%' . $search . '%')
            // ->orWhere('retail_price', 'like', '%' . $search . '%');
        }
        if (($weight_filter_type != null) && (($weight_value != null) && (($price_filter_type == null) && ($price_value == null))) && ($product_status == null) && ($stock_filter_type == null && $stock_value == null)) {
            if (isset($weight_filter_type) && isset($weight_value)) {
                $operation = '';

                if ($weight_filter_type == 'equal_to') {
                    $operation = '=';
                } elseif($weight_filter_type == 'less_than') {
                    $operation = '<';
                } elseif($weight_filter_type == 'greater_than') {
                    $operation = '>';
                }

                $products_query
                // ->where('status', '!=', 'Inactive')
                ->whereHas('options', function ($query) use ($weight_value, $operation) {
                    $query->where(function ($query) use ($weight_value, $operation) {
                        $query->whereRaw("CONVERT(optionWeight, SIGNED) $operation ?", [$weight_value])
                            ->orWhereRaw("CONVERT(optionWeight, DECIMAL(10,2)) $operation ?", [$weight_value]);
                    })
                    ->where('status', '!=', 'disabled');
                });
            }
        } 
        elseif ((($price_filter_type != null) && ($price_value != null)) && (($weight_filter_type == null) && ($weight_value == null)) && ($product_status == null)  && ($stock_filter_type == null && $stock_value == null)) {
            if (isset($price_filter_type) && isset($price_value)) {
                $operation = '';

                if ($price_filter_type == 'equal_to') {
                    $operation = '=';
                } elseif($price_filter_type == 'less_than') {
                    $operation = '<';
                } elseif($price_filter_type == 'greater_than') {
                    $operation = '>';
                }

                $products_query
                // ->where('status', '!=', 'Inactive')
                ->whereHas('options' , function($query){
                    $query->where('status', '!=', 'disabled');
                })
                ->whereHas('options.defaultPrice' , function($query) use ($price_value, $operation , $price_column){
                    $query->where($price_column, $operation, $price_value);
                });
            }
        }
        elseif (($product_status != null) && (($weight_filter_type == null) && ($weight_value == null)) && (($price_filter_type == null) && ($price_value == null))  && ($stock_filter_type == null && $stock_value == null)) {
            if (isset($product_status)) {
                if ($product_status == 'Public') {
                    $products_query->where('status', '!=', 'Inactive')
                    ->whereHas('options' , function($query){
                        $query->where('status', '!=', 'disabled');
                    });
                } elseif($product_status == 'Inactive') {
                    $products_query->where('status', '=', 'Inactive')
                    ->whereHas('options' , function($query){
                        $query->where('status', '!=', 'disabled');
                    });
                }
                
            }
        }
        elseif (($product_status != null) && (($weight_filter_type != null) && ($weight_value != null)) && (($price_filter_type == null) && ($price_value == null))  && ($stock_filter_type == null && $stock_value == null)) {
            if (isset($product_status) && (isset($weight_filter_type) && isset($weight_value))) {
                $operation = '';

                if ($price_filter_type == 'equal_to') {
                    $operation = '=';
                } elseif($price_filter_type == 'less_than') {
                    $operation = '<';
                } elseif($price_filter_type == 'greater_than') {
                    $operation = '>';
                }
                if ($product_status == 'Public') {
                    $products_query->where('status', '!=', 'Inactive')
                    ->whereHas('options', function ($query) use ($weight_value, $operation) {
                        $query->where(function ($query) use ($weight_value, $operation) {
                            $query->whereRaw("CONVERT(optionWeight, SIGNED) $operation ?", [$weight_value])
                                ->orWhereRaw("CONVERT(optionWeight, DECIMAL(10,2)) $operation ?", [$weight_value]);
                        })
                        ->where('status', '!=', 'disabled');
                    });
                } elseif($product_status == 'Inactive') {
                    $products_query->where('status', '=', 'Inactive')
                    ->whereHas('options', function ($query) use ($weight_value, $operation) {
                        $query->where(function ($query) use ($weight_value, $operation) {
                            $query->whereRaw("CONVERT(optionWeight, SIGNED) $operation ?", [$weight_value])
                                ->orWhereRaw("CONVERT(optionWeight, DECIMAL(10,2)) $operation ?", [$weight_value]);
                        })
                        ->where('status', '!=', 'disabled');
                    });
                }
                
            }
        }
        elseif (($product_status != null) && (($weight_filter_type == null) && ($weight_value == null)) && (($price_filter_type != null) && ($price_value != null))  && ($stock_filter_type == null && $stock_value == null)) {
            if (isset($product_status) && (isset($price_filter_type) && isset($price_value))) {
                $operation = '';

                if ($price_filter_type == 'equal_to') {
                    $operation = '=';
                } elseif($price_filter_type == 'less_than') {
                    $operation = '<';
                } elseif($price_filter_type == 'greater_than') {
                    $operation = '>';
                }
                if ($product_status == 'Public') {
                    $products_query->where('status', '!=', 'Inactive')
                    ->whereHas('options' , function($query){
                        $query->where('status', '!=', 'disabled');
                    })
                    ->whereHas('options.defaultPrice' , function($query) use ($price_value, $operation , $price_column){
                        $query->where($price_column, $operation, $price_value);
                    });
                } elseif($product_status == 'Inactive') {
                    $products_query->where('status', '=', 'Inactive')
                    ->whereHas('options' , function($query){
                        $query->where('status', '!=', 'disabled');
                    })
                    ->whereHas('options.defaultPrice' , function($query) use ($price_value, $operation , $price_column){
                        $query->where($price_column, $operation, $price_value);
                    });
                }
                
            }
        }
        elseif ((($price_filter_type != null) && ($price_value != null)) && (($weight_filter_type != null) && ($weight_value != null)) && ($product_status == null)  && ($stock_filter_type == null && $stock_value == null)) {
            if ((isset($price_filter_type) && isset($price_value)) && (isset($weight_filter_type) && isset($weight_value)))  {

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

                $products_query->whereHas('options', function ($query) use ($weight_value, $weight_operation) {
                        $query->where(function ($query) use ($weight_value, $weight_operation) {
                        $query->whereRaw("CONVERT(optionWeight, SIGNED) $weight_operation ?", [$weight_value])
                        ->orWhereRaw("CONVERT(optionWeight, DECIMAL(10,2)) $weight_operation ?", [$weight_value]);
                    })
                    ->where('status', '!=', 'disabled');
                })
                ->whereHas('options.defaultPrice' , function($query) use ($price_value, $operation, $price_column){
                    $query->where($price_column, $operation, $price_value);
                });
            }
        }
        // stock filter
        elseif(($stock_filter_type != null && $stock_value != null) && (($weight_filter_type == null) && ($weight_value == null)) && (($price_filter_type == null) && ($price_value == null)) && ($product_status == null)) {
            if (isset($stock_filter_type) && isset($stock_value)) {
                $operation = '';

                if ($stock_filter_type == 'equal_to') {
                    $operation = '=';
                } elseif($stock_filter_type == 'less_than') {
                    $operation = '<';
                } elseif($stock_filter_type == 'greater_than') {
                    $operation = '>';
                }

                $products_query->whereHas('options', function ($query) use ($stock_value, $operation) {
                    $query->where('status', '!=', 'disabled')
                    ->whereRaw("CAST(stockAvailable AS SIGNED) $operation ?", [$stock_value]);
                });
            }

        }
        elseif ((($price_filter_type != null) && ($price_value != null)) && (($weight_filter_type != null) && ($weight_value != null)) && ($product_status != null)  && ($stock_filter_type != null && $stock_value != null)) {
            if ((isset($price_filter_type) && isset($price_value)) && (isset($weight_filter_type) && isset($weight_value)) && (isset($product_status)) && (isset($stock_filter_type) && isset($stock_value))) {

                $status_operation  = '';
                $status = '';
                if ($product_status == 'Public') {
                    $status_operation = '!=';
                    $status = 'Inactive';
                } elseif($product_status == 'Inactive') {
                    $status_operation = '=';
                    $status = 'Inactive';
                }

                $operation = '';
                $weight_operation = '';
                $stock_operation = '';

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

                if ($stock_filter_type == 'equal_to') {
                    $stock_operation = '=';
                } elseif($stock_filter_type == 'less_than') {
                    $stock_operation = '<';
                } elseif($stock_filter_type == 'greater_than') {
                    $stock_operation = '>';
                }


                $products_query->where('status', $status_operation , $status)
                ->whereHas('options', function ($query) use ($weight_value, $weight_operation) {
                        $query->where(function ($query) use ($weight_value, $weight_operation) {
                        $query->whereRaw("CONVERT(optionWeight, SIGNED) $weight_operation ?", [$weight_value])
                        ->orWhereRaw("CONVERT(optionWeight, DECIMAL(10,2)) $weight_operation ?", [$weight_value]);
                    })
                    ->where('status', '!=', 'disabled');
                })
                ->whereHas('options', function ($query) use ($stock_value, $stock_operation) {
                    $query->whereRaw("CAST(stockAvailable AS SIGNED) $stock_operation ?", [$stock_value]);
                })
                ->whereHas('options.defaultPrice' , function($query) use ($price_value, $operation , $price_column){
                    $query->where($price_column, $operation, $price_value);
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
                    'Product Barcode',
                    'Product Status',
                    'Product Retail Price',
                    'Product Weight',
                    'Product Stock'
                ];

                foreach ($products as $product) {
                    $retail_price = 0;
                    if (!empty($product->options[0]->defaultPrice->$price_column)) {
                        $retail_price = $product->options[0]->defaultPrice->$price_column;
                    }   
                    $csv_data[] = [
                        $product->name,
                        $product->code,
                        $product->barcode,
                        $product->status,
                        $retail_price,
                        isset($product->options[0]) ? $product->options[0]->optionWeight : '',
                        isset($product->options[0]) ? $product->options[0]->stockAvailable : ''
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
            'price_value',
            'product_status',
            'price_column'
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