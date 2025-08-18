<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\SettingHelper;
use \App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Pricing;
use App\Models\Pricingnew;
use App\Models\ProductOption;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            'price_column',
            'default_price_column'
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


    public function update_product_price(Request $request) {
        $option_id = $request->option_id;
        $product_id = $request->product_id;
        $client = new \GuzzleHttp\Client();

        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');

        $url = 'https://api.cin7.com/api/v1/ProductOptions/' . $option_id;
        $product_url = 'https://api.cin7.com/api/v1/Products/';

        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request(
                'GET', 
                $url,
                [
                    'auth' => [
                        $cin7_auth_username,
                        $cin7_auth_password
                    ]                    
                ]
            );
    
            $product_option_prices = $res->getBody()->getContents();
            $get_product_prices = json_decode($product_option_prices);

            if (!empty($get_product_prices) &&  !empty($option_id)) {
                $update_product_option = ProductOption::where('option_id', $option_id)->first();
                $update_product_option->optionWeight = $get_product_prices->optionWeight;
                $update_product_option->status = $get_product_prices->status;
                $update_product_option->save();


                $productId = $update_product_option->product_id;


                $client = new \GuzzleHttp\Client();
                $product_response = $client->request(
                    'GET', 
                    $product_url . $productId,
                    [
                        'auth' => [
                            $cin7_auth_username,
                            $cin7_auth_password
                        ]                    
                    ]
                );

                $cin7_product = $product_response->getBody()->getContents();
                $get_product = json_decode($cin7_product);

                if (!empty($get_product)) {
                    $update_product = Product::where('product_id', $productId)->first();
                    $update_product->status = $get_product->status;
                    $update_product->name = $get_product->name;
                    $update_product->description = $get_product->description;
                    $update_product->width = $get_product->width;
                    $update_product->height = $get_product->height;
                    $update_product->length = $get_product->length;
                    $update_product->volume = $get_product->volume;
                    $update_product->code = $get_product->productOptions[0]->code;
                    $update_product->barcode = $get_product->productOptions[0]->barcode;
                    $update_product->images = !empty($get_product->images[0]) ? $get_product->images[0]->link: '';
                    $update_product->save();
                }
                    
            }

            if (!empty($get_product_prices->priceColumns)) {
                $product_pricing = Pricingnew::where('option_id', $option_id)->first();
                if (!empty($product_pricing)) {
                    $product_pricing->retailUSD = $get_product_prices->priceColumns->retailUSD;
                    $product_pricing->wholesaleUSD = $get_product_prices->priceColumns->wholesaleUSD;
                    $product_pricing->oklahomaUSD = $get_product_prices->priceColumns->oklahomaUSD;
                    $product_pricing->calaverasUSD = $get_product_prices->priceColumns->calaverasUSD;
                    $product_pricing->tier1USD = $get_product_prices->priceColumns->tier1USD;
                    $product_pricing->tier2USD = $get_product_prices->priceColumns->tier2USD;
                    $product_pricing->tier3USD = $get_product_prices->priceColumns->tier3USD;
                    $product_pricing->tier0USD = $get_product_prices->priceColumns->tier0USD;
                    $product_pricing->commercialOKUSD = $get_product_prices->priceColumns->commercialOKUSD;;
                    $product_pricing->costUSD = $get_product_prices->priceColumns->costUSD;
                    $product_pricing->specialPrice = $get_product_prices->priceColumns->specialPrice;
                    $product_pricing->webPriceUSD = $get_product_prices->priceColumns->webPriceUSD;
                    $product_pricing->sacramentoUSD = $get_product_prices->priceColumns->sacramentoUSD;;

                    $product_pricing->save();

                    return redirect()->back()->with('success', 'Price updated successfully');
                    
                } else {
                    return redirect()->back()->with('error', 'Price already exists for this product');
                }
            } else {
                return redirect()->back()->with('error', 'Error with Api while updating price');
            }
            
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error', 'Error with Api while updating price');
        }


    }


    
    public function toggleCompressed(Request $request, $id)
    {
        $request->validate([
            'is_compressed' => 'required|boolean',
        ]);

        $product = Product::findOrFail($id);
        $product->is_compressed = (bool)$request->is_compressed;
        $product->save();

        return response()->json([
            'success' => true,
            'is_compressed' => $product->is_compressed,
        ]);
    }
}