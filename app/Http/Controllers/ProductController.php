<?php

namespace App\Http\Controllers;

use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\ProductOption;
use App\Models\Pricingnew;
use Illuminate\Support\Str;
use App\Models\Contact;
use App\Models\Brand;
use App\Models\BuyList;
use App\Models\ProductBuyList;
use App\Models\TaxClass;
use App\Models\ProductView;
use App\Models\AdminSetting;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\UserHelper;
use App\Models\UsState;
use App\Models\ApiOrder;
use App\Models\User;

use App\Helpers\UtilHelper;
use App\Mail\AdminBulkRequestNotification;
use App\Mail\UserBulkRequestConfirmation;
use App\Models\AiQuestion;
use App\Models\ApiOrderItem;
use App\Models\BulkQuantityDiscount;
use App\Models\ProductStock;
use Illuminate\Support\Facades\Session;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\ZendeskService;
use Zendesk\API\HttpClient as ZendeskClient;

class ProductController extends Controller
{
    public function showProductByCategory(Request $request, $category_id)
    {

        $selected_category_id = $request->get('selected_category_id');
        if (!empty($selected_category_id)) {
        }
        
        $parent_category = Category::find($category_id);
        $parent_category_slug = $parent_category->slug;
        $categories = Category::orderBy('name', 'ASC')->where('parent_id', 0)->where('is_active' , 1)->get();
        //git switch branch
        $category_ids = Category::where('parent_id', $category_id)->where('is_active' , 1)->pluck('id')->toArray();
        array_push($category_ids, $category_id);
        $products = [];

        $all_product_ids = Product::whereIn('category_id', $category_ids)->pluck('id')->toArray();
        $brand_ids = Product::whereIn('id', $all_product_ids)->pluck('brand_id')->toArray();

        $childerens  = Category::orderBy('name', 'ASC')->where('parent_id', $category_id)->where('is_active' , 1)->get();

        $brand_id = $request->get('brand_id');
        $stock = $request->get('stock');
        $products_to_hide = BuyList::with('list_products')->where('title' , 'Products_to_hide')->first();
        
        if (!empty($products_to_hide)) {
            $products_to_hide = $products_to_hide->list_products->pluck('option_id')->toArray();
        }
        if ($request->get('per_page')) {
            $per_page = $request->get('per_page');
        } else {
            $per_page = 12;
        }
        $price_creteria = $request->get('search_price');
        if (!empty($category_ids)) {
            
            $products_query = Product::where('status', '!=', 'Inactive')
                ->whereIn('category_id', $category_ids)
                ->with('options.price', 'brand')
                ->with(['product_views','apiorderItem' , 'options' => function ($q) {
                    $q->where('status', '!=', 'Disabled');
                }]);


            if (!empty($brand_id)) {
                $products_query->where('brand_id', $brand_id);
            }
            $childeren_id = $request->get('childeren_id');

            if (!empty($childeren_id)) {
                $products_query->where('category_id', $childeren_id);
            }

            if (!empty($price_creteria)) {
                if ($price_creteria == 'brand-a-to-z') {
                    $products_query->orderBy('name', 'ASC');
                } elseif ($price_creteria == 'brand-z-to-a') {
                    $products_query->orderBy('name', 'DESC');
                } elseif ($price_creteria == 'best-selling') {
                    $products_query->orderBy('views', 'DESC');
                } elseif ($price_creteria == 'price-low-to-high') {
                    $products_query->orderBy('retail_price', 'ASC');
                } elseif ($price_creteria == 'price-high-to-low') {
                    $products_query->orderBy('retail_price', 'DESC');
                }
            }

            if (empty($stock) || $stock == 'in-stock') {
                $products_query->where('stockAvailable', '>', 0);
            } elseif (!empty($stock) && $stock == 'out-of-stock') {
                $products_query->where('stockAvailable', '<', 1);
            }

            if (!empty($selected_category_id)) {
                $sub_category_ids = Category::where('parent_id', $selected_category_id)->pluck('id')->toArray();
            }

            $products = $products_query->with('options.price', 'brand')
            // ->whereHas('options.defaultPrice', function ($q) {
            //     $q->where('retailUSD', '!=', 0);
            // })
            ->paginate($per_page);

            // $queries = DB::getQueryLog();
        }

        $brands = Brand::orderBy('name', 'ASC')->whereIn('id', $brand_ids)->pluck('name', 'id')->toArray();
        $user_id = Auth::id();
        $contact = '';
        if ($user_id != null) {
            $contact = Contact::where('user_id', $user_id)->first();
        }

        if ($contact) {
            $pricing = $contact->priceColumn;
        } else {
            $pricing = 'RetailUSD';
        }

        $contact_id = session()->get('contact_id');
        $user_list = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->first();


        $user_buy_list_options = [];

        if (!empty($user_list)) {
            $user_buy_list_options = ProductBuyList::where('list_id', $user_list->id)->pluck('option_id', 'option_id')->toArray();
        }

        $lists = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->with('list_products')
            ->get();

        $product_views = null;
        if (auth()->user()) {
            $product_views = ProductView::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as entry_count'))
            ->whereNotNull('user_id')
            ->where('user_id' , auth()->id())
            ->orderBy('created_at' , 'DESC')
            ->groupBy('product_id')
            ->take(10)
            ->get();
            
        } else {
             $product_views = null;
        }

        $best_selling_products = null;

        $best_selling_products = ApiOrderItem::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as entry_count'))
            ->orderBy('created_at' , 'DESC')
            ->groupBy('product_id')
            ->take(24)
            ->get();
        $notify_user_about_product_stock = AdminSetting::where('option_name', 'notify_user_about_product_stock')->first();
        return view('categories', compact(
            'products',
            'brands',
            'category_id',
            'parent_category_slug',
            'brand_id',
            'per_page',
            'price_creteria',
            'categories',
            'stock',
            'selected_category_id',
            'lists',
            'childerens',
            'childeren_id',
            'contact_id',
            'pricing',
            'user_buy_list_options',
            'product_views',
            'best_selling_products',
            'notify_user_about_product_stock','products_to_hide'
        ));
    }

    public function showAllProducts(Request $request)
    {
        if ($request->get('per_page')) {
            $per_page = $request->get('per_page');
        } else {
            $per_page = 12;
        }

        $search_queries = $request->except('page');

        $products_query  = Product::with('options', 'brand', 'categories' , 'product_views','apiorderItem')->where('status' , '!=' , 'Inactive');
        
        $selected_category_id = $request->get('selected_category');

        $childerens = Category::orderBy('name', 'ASC')->where('parent_id', $selected_category_id)->get();

        $products_to_hide = BuyList::with('list_products')->where('title' , 'Products_to_hide')->first();
        
        if (!empty($products_to_hide)) {
            $products_to_hide = $products_to_hide->list_products->pluck('option_id')->toArray();
        }

        $category_id = $selected_category_id;
        $brand_ids = Product::where('category_id', $selected_category_id)->where('status' , '!='  ,'Inactive')->pluck('brand_id', 'brand_id')->toArray();
        if (empty($brand_ids) && !empty($search_queries)) {
            $sub_category_ids = Category::where('parent_id', $selected_category_id)->pluck('id', 'id')->toArray();
            $brand_ids = Product::whereIn('category_id', $sub_category_ids)->pluck('brand_id', 'brand_id')->toArray();
            $products_query = Product::whereIn('category_id', $sub_category_ids);
        } elseif (!empty($selected_category_id)) {
            $products_query = Product::where('category_id', $selected_category_id)->with('brand', 'options');
        }
        $brand_id = $request->get('brand_id');

        if (!empty($brand_id)) {
            $products_query->where('brand_id', $brand_id);
        }

        $childeren_id = $request->get('childeren_id');


        if (!empty($childeren_id)) {
            $products_query->where('category_id', $childeren_id);
        }

        $price_creteria = $request->get('search_price');
        if (!empty($price_creteria)) {
            if ($price_creteria == 'brand-a-to-z') {
                $products_query->orderBy('name', 'ASC');
            }
            if ($price_creteria == 'brand-z-to-a') {
                $products_query->orderBy('name', 'DESC');
            }
        }
        if (!empty($price_creteria)) {
            if ($price_creteria == 'best-selling') {
                $products_query->orderBy('views', 'DESC');
            }
        }
        if (!empty($price_creteria)) {
            if ($price_creteria == 'price-low-to-high') {
                $products_query->orderBy('retail_price', 'ASC');
            }
            if ($price_creteria == 'price-high-to-low') {
                $products_query->orderBy('retail_price', 'DESC');
            }
        }
        $stock = $request->get('stock');
        if (empty($stock) || $stock == 'in-stock') {
            $products_query->where('stockAvailable', '>', 0);
        } elseif (!empty($stock) && $stock == 'out-of-stock') {
            $products_query->where('stockAvailable', '<', 1);
        }

        if (empty($search_queries)) {
            $products = $products_query->with(['product_views','apiorderItem' , 'options' => function ($q) {
                $q->where('status', '!=', 'Disabled');
            }])
            // ->whereHas('options.defaultPrice', function ($q) {
            //     $q->where('retailUSD', '!=', 0);
            // })
            ->paginate($per_page);
            // $products = $products_query->paginate($per_page);
        } else {
            // $products = $products_query->with('options.defaultPrice', 'brand')->paginate($per_page);
            $products = $products_query->with(['product_views','apiorderItem' , 'options' => function ($q) {
                $q->where('status', '!=', 'Disabled');
            }])
            // ->whereHas('options.defaultPrice', function ($q) {
            //     $q->where('retailUSD', '!=', 0);
            // })
            ->paginate($per_page);
        }


        $brands = [];
        if (!empty($brand_ids)) {
            $brands = Brand::orderBy('name', 'ASC')->whereIn('id', $brand_ids)->pluck('name', 'id')->toArray();
        } elseif (empty($search_queries)) {
            $brands = Brand::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }
        $categories = Category::where('parent_id', 0)->where('is_active'  , 1)->orderBy('name', 'ASC')->get();
        $category_ids = Category::where('parent_id', $category_id)->pluck('id')->toArray();
        array_push($category_ids, $category_id);
        $all_product_ids = Product::whereIn('category_id', $category_ids)->pluck('id')->toArray();
        $brand_ids = Product::whereIn('id', $all_product_ids)->pluck('brand_id')->toArray();
        $brand_id = $request->get('brand_id');
        $stock = $request->get('stock');
        $price_creteria = $request->get('search_price');

        $parent_category_slug  = '';

        if (!empty($category_ids) && !empty($category_ids[0])) {
            $products_query = Product::where('status', '!=', 'Inactive')
                ->whereIn('category_id', $category_ids)
                ->with('options.price', 'brand');
            if (!empty($brand_id)) {
                $products_query->where('brand_id', $brand_id);
            }
            $childeren_id = $request->get('childeren_id');
            if (!empty($childeren_id)) {

                $products_query->where('category_id', $childeren_id);
            }
            if (!empty($price_creteria)) {
                if ($price_creteria == 'brand-a-to-z') {
                    $products_query->orderBy('name', 'ASC');
                }
                if ($price_creteria == 'brand-z-to-a') {
                    $products_query->orderBy('name', 'DESC');
                }
            }
            if (!empty($price_creteria)) {
                if ($price_creteria == 'best-selling') {
                    $products_query->orderBy('views', 'DESC');
                }
            }
            if (!empty($price_creteria)) {
                if ($price_creteria == 'price-low-to-high') {
                    $products_query->orderBy('retail_price', 'ASC');
                }
                if ($price_creteria == 'price-high-to-low') {
                    $products_query->orderBy('retail_price', 'DESC');
                }
            }
            if (empty($stock) || $stock == 'in-stock') {
                $products_query->where('stockAvailable', '>', 0);
            } elseif (!empty($stock) && $stock == 'out-of-stock') {
                $products_query->where('stockAvailable', '<', 1);
            }
            if (!empty($selected_category_id)) {
                $sub_category_ids = Category::where('parent_id', $selected_category_id)->pluck('id')->toArray();
            }

            if ($category_id = '') {
                $parent_category = Category::find($category_id);
                $parent_category_slug = $parent_category->slug;
                $products_query = $products_query->where('category_id', $category_id);
            } else {
            }
            // $products = $products_query->with('options.defaultPrice', 'brand')->paginate($per_page);
            $products = $products_query->with(['product_views','apiorderItem' , 'options' => function ($q) {
                $q->where('status', '!=', 'Disabled');
            }])
            // ->whereHas('options.defaultPrice', function ($q) {
            //     $q->where('retailUSD', '!=', 0);
            // })
            ->paginate($per_page);
        }
        $user_id = Auth::id();
        $lists = BuyList::where('user_id', $user_id)->get();
        $contact = '';
        if ($user_id != null) {
            $contact = Contact::where('user_id', $user_id)->first();
        }

        if ($contact) {
            $pricing = $contact->priceColumn;
        } else {
            $pricing = 'Retail';
        }
        $category_id = $selected_category_id;
        $contact_id = session()->get('contact_id');
        $user_list = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->first();


        $user_buy_list_options = [];

        if (!empty($user_list)) {
            $user_buy_list_options = ProductBuyList::where('list_id', $user_list->id)->pluck('option_id', 'option_id')->toArray();
        }

        $product_views = null;
        if (auth()->user()) {
            $product_views = ProductView::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as entry_count'))
            ->whereNotNull('user_id')
            ->where('user_id' , auth()->id())
            ->orderBy('created_at' , 'DESC')
            ->groupBy('product_id')
            ->take(10)
            ->get();
            
        } else {
            $product_views = null;
        }
        
        $best_selling_products = null;

        $best_selling_products = ApiOrderItem::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as entry_count'))
            ->orderBy('created_at' , 'DESC')
            ->groupBy('product_id')
            ->take(24)
            ->get();
        $notify_user_about_product_stock = AdminSetting::where('option_name', 'notify_user_about_product_stock')->first();
        return view('all_products', compact(
            'products',
            'brands',
            'price_creteria',
            'categories',
            'stock',
            'category_id',
            'parent_category_slug',
            'brand_id',
            'per_page',
            'lists',
            'pricing',
            'childerens',
            'childeren_id',
            'user_buy_list_options',
            'contact_id',
            'product_views',
            'best_selling_products',
            'notify_user_about_product_stock',
            'products_to_hide'
            // 'db_price_column'
        ));
    }

    public function buy_again(Request $request) {
        $contact_id = session()->get('contact_id');
        $user_id = auth()->id();
        $user = User::where('id', $user_id)->first();
        // $can_approve_order = $user->hasRole('Order Approver');
        $selected_company = Session::get('company');
        $all_ids = UserHelper::getAllMemberIds($user);
        $contact_ids = Contact::whereIn('id', $all_ids)
            ->pluck('contact_id')
            ->toArray();
       
        $products_ids = ApiOrderItem::with('product')
        ->whereHas('order' , function($query)use ($contact_ids) {
            $query->with(['createdby'])->whereIn('memberId', $contact_ids)
            ->whereHas('contact' , function($query) {
                $query->orderBy('company');
            });
        })->pluck('product_id')->toArray();
        $products_query  = Product::whereIn('product_id' , $products_ids)
        ->with('options', 'brand', 'categories')
        ->where('status' , '!=' , 'Inactive');
        $products = $products_query->with('options.defaultPrice', 'brand' , 'product_views','apiorderItem')->paginate(12);
        $user_list = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->first();
        $user_buy_list_options = [];

        if (!empty($user_list)) {
            $user_buy_list_options = ProductBuyList::where('list_id', $user_list->id)->pluck('option_id', 'option_id')->toArray();
        }

        $lists = BuyList::where('user_id', $user_id)->get();
        $contact = '';
        if ($user_id != null) {
            $contact = Contact::where('user_id', $user_id)->first();
        }

        if ($contact) {
            $pricing = $contact->priceColumn;
        } else {
            $pricing = 'Retail';
        }

        $products_to_hide = BuyList::with('list_products')->where('title' , 'Products_to_hide')->first();
        
        if (!empty($products_to_hide)) {
            $products_to_hide = $products_to_hide->list_products->pluck('option_id')->toArray();
        }
        $notify_user_about_product_stock = AdminSetting::where('option_name', 'notify_user_about_product_stock')->first();
        return view('buy_again', compact(
            'products',
            'lists',
            'pricing',
            'user_buy_list_options',
            'contact_id', 'products_to_hide',
            'notify_user_about_product_stock'
        ));
    }

    public function showProductDetail(Request $request ,$id, $option_id)
    {
        $similar_products = null;
       
        $product = Product::with('categories' , 'brand')
        ->where('id', $id)
        ->where('status', '!=', 'Inactive')->first();
        $stock_updation_by_visiting_detail = UtilHelper::updateProductStock($product, $option_id);
        if (empty($product)) {
            session()->flash('error', 'This product is not available! Please search another product.');
            return redirect('/products');
        }
        if (!empty($product->category_id) && !empty($product)) {
            $similar_products = $this->getSimilarProducts($request , $id, $option_id);
        }
        $request_bulk_quantity_discount = AdminSetting::where('option_name', 'request_bulk_quantity_discount')->first();
        $best_selling_products = null;
        $best_selling_products = ApiOrderItem::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as entry_count'))
            ->orderBy('created_at' , 'DESC')
            ->groupBy('product_id')
            ->take(24)
            ->get();

        $location_inventories = [];
        $available_stock = [];
        $stock = true;
        $productOption = [];
        $pname = '';
        $pricing = '';
        $user_buy_list_options = [];
        $lists = '';
        $contact_id = '';
        $locations= null;
        $stock_updated_helper = null;
        $inventory_update_time_flag = false;
        $date_difference = null;
        $threshold_minutes = 3; // Adjust this threshold as needed
        $stock_with_branches = null;
        $branch_locations = null;


        $customer_demand_inventory_number = !empty($request->latest_inventory_number) ? intval($request->latest_inventory_number) : 0;
        // Retrieve the record for the given option_id
        $product_option = ProductOption::where('option_id', $option_id)->first();

        
        // Fetch stock from API
        if ($customer_demand_inventory_number === 1) {
            // Check if a record exists
            if (!empty($product_option)) {
                $last_update_time = $product_option->inventory_update_time;

                if ($last_update_time !== null) {
                    $get_time_difference = now()->diffInMinutes($last_update_time);
                    $date_difference = $get_time_difference;
                    if ($date_difference >= $threshold_minutes) {
                        $inventory_update_time_flag = true;
                        $product_option->update(['inventory_update_time' => now()]);
                    } else {
                        $inventory_update_time_flag = false;
                    }
                } else {
                    $product_option->update(['inventory_update_time' => now()]);
                    $inventory_update_time_flag = true;
                }
            } else {
                $inventory_update_time_flag = true;
            }
            if ($inventory_update_time_flag == true) {
                $stock_updated_helper = UtilHelper::updateProductStock($product, $option_id);
                if ($stock_updated_helper != null) {
                    $stock_updated = $stock_updated_helper['stock_updated'];
                    $stock_with_branches = $stock_updated_helper['branch_with_stocks'];
                    if (!empty($stock_with_branches)) {
                        $product_stock = ProductStock::where('product_id' ,  $product->product_id)
                            ->where('option_id' , $option_id)
                            ->get();
                        if (count($product_stock) > 0) {
                            foreach ($stock_with_branches as $branch_stock) {
                                $stock_found = false;
                            
                                foreach ($product_stock as $stock) {
                                    if ($branch_stock['branch_id'] == $stock->branch_id) {
                                        $stock->available_stock = $branch_stock['available'];
                                        $stock->save();
                                        $stock_found = true;
                                        break; // Exit the inner loop since we found a matching stock
                                    }
                                }
                            
                                if (!$stock_found) {
                                    // Create a new product stock if no matching stock found for the current branch
                                    ProductStock::create([
                                        'available_stock' => $branch_stock['available'],
                                        'branch_id' => $branch_stock['branch_id'],
                                        'product_id' => $product->product_id,
                                        'branch_name' => $branch_stock['branch_name'],
                                        'option_id' => $option_id
                                    ]);
                                }
                            }
                            
                            // Delete any product stocks that are not in $stock_with_branches
                            $product_stock_ids = collect($stock_with_branches)->pluck('branch_id');
                            foreach ($product_stock as $stock) {
                                if (!$product_stock_ids->contains($stock->branch_id)) {
                                    $stock->delete();
                                }
                            }
                        } else {
                            foreach ($stock_with_branches as $branch_stock) {
                                ProductStock::create([
                                    'available_stock' => $branch_stock['available'],
                                    'branch_id' => $branch_stock['branch_id'],
                                    'product_id' => $product->product_id,
                                    'branch_name' => $branch_stock['branch_name'],
                                    'option_id' => $option_id
                                ]);
                            }
                        }
                        
                    }
                } else {
                    $stock_updated = false;
                }
            } else {
                $get_stock_with_branches = ProductStock::where('product_id' ,  $product->product_id)
                    ->where('option_id' , $option_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                if (count($get_stock_with_branches) > 0) {
                    foreach ($get_stock_with_branches as $branch_stock) {
                        $branch_locations[] = [
                            'branch_id' => $branch_stock->branch_id,
                            'branch_name' => $branch_stock->branch_name,
                            'available' => $branch_stock->available_stock
                        ];
                    }
                }
                $stock_updated = false;
            }
        }
        else {
            $get_stock_with_branches = ProductStock::where('product_id' ,  $product->product_id)
                ->where('option_id' , $option_id)
                ->orderBy('created_at', 'desc')
                ->get();
            if (count($get_stock_with_branches) > 0) {
                foreach ($get_stock_with_branches as $branch_stock) {
                    $branch_locations[] = [
                        'branch_id' => $branch_stock->branch_id,
                        'branch_name' => $branch_stock->branch_name,
                        'available' => $branch_stock->available_stock
                    ];
                }
            }
            $stock_updated = false;
        }
        $product_stocks = ProductStock::where('product_id' ,  $product->product_id)
            ->where('option_id' , $option_id)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($product) {
            $views = $product->views;
            $product->views = $views + 1;
            $product->save();

            // adding product views to separate table
            $product_view = new ProductView();
            $product_view->product_id = $product->id;
            if (Auth::id() != null) {
                $product_view->user_id = Auth::id();
            }
            $product_view->save();
        }
        
        $productOption = ProductOption::where('option_id', $option_id)->with('products.categories', 'price')->first();
        if ($productOption->products->categories != '') {
            $category = Category::where('category_id', $productOption->products->categories->category_id)->first();
            $parent_category = Category::where('category_id', $category->parent_id)->first();
            $pname = '';
            if ($parent_category) {
                $pname =  $parent_category->name;
            } else {
                $category = Category::where('category_id', $category->category_id)->first();
                $pname = $category->name;
            }
        } else {
            $pname = '';
        }

        $user_id = Auth::id();
        $contact = '';
        if ($user_id != null) {
            $contact = Contact::where('user_id', $user_id)->first();
        }

        if ($contact) {
            $pricing = $contact->priceColumn;
        } else {
            $pricing = 'RetailUSD';
        }

        $contact_id = session()->get('contact_id');
        $user_list = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->first();
        if (!empty($user_list)) {
            $user_buy_list_options = ProductBuyList::where('list_id', $user_list->id)->pluck('option_id', 'option_id')->toArray();
        }

        $lists = BuyList::where('user_id', $user_id) 
            ->where('contact_id', $contact_id)
            ->with('list_products')
            ->get();
        $total_stock = 0;
        if ($stock_updated) {
            if (!empty($stock_updated_helper['total_stock'])) {
                $total_stock = $stock_updated_helper['total_stock'] >= 0 ? $stock_updated_helper['total_stock'] : 0;
            }
        } 
        // else {
        //     $total_stock = $productOption->stockAvailable;
        // }
        if ($customer_demand_inventory_number == 1) {
            if ($inventory_update_time_flag == false) {
                $locations = $branch_locations;
            }
            else {
                if (!empty($stock_updated_helper['branch_with_stocks'])) {
                    $locations = $stock_updated_helper['branch_with_stocks'];
                }
            }
        } else {
            $locations = $branch_locations;
        } 
        
        $notify_user_about_product_stock = AdminSetting::where('option_name', 'notify_user_about_product_stock')->first();
        $products_to_hide = BuyList::with('list_products')->where('title' , 'Products_to_hide')->first();
        
        if (!empty($products_to_hide)) {
            $products_to_hide = $products_to_hide->list_products->pluck('option_id')->toArray();
        }

        $ai_questions  = AiQuestion::where('status' , 1)->orderBy('created_at' , 'Desc')->get();

        $ai_setting = AdminSetting::where('option_name', 'enable_ai_prompt')->first();

        return view('product-detail', compact(
            'productOption',
            'pname',
            'pricing',
            'location_inventories',
            'user_buy_list_options',
            'lists',
            'contact_id',
            'stock_updated',
            'product_stocks',
            'notify_user_about_product_stock',
            'request_bulk_quantity_discount',
            'similar_products',
            'total_stock',
            'best_selling_products',
            'locations', 
            'inventory_update_time_flag' , 
            'customer_demand_inventory_number',
            'products_to_hide',
            'ai_questions',
            'ai_setting'
        ));

        
        
    }
    public function getSimilarProducts(Request $request ,$id, $option_id) {
        $similar_products = null;
        $perPage = 4;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $product = Product::with('categories' , 'brand')
        ->where('id', $id)
        ->where('status', '!=', 'Inactive')->first();
        if (empty($product)) {
            session()->flash('error', 'This product is not available! Please search another product.');
            return redirect('/products');
        }
        if (!empty($product->category_id) && !empty($product)) {
                
                $similar_products_query = Product::with('options', 'options.defaultPrice', 'brand', 'options.products', 'categories', 'apiorderItem', 'product_stock')
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->whereHas('categories' , function ($q){
                    $q->where('is_active' , 1);
                })
                ->where('status', '!=', 'Inactive');
                
                $total_products = $similar_products_query->count();
                $similar_products_query = $similar_products_query->take($perPage)
                ->skip($offset)
                ->get();
                $total = $total_products >= 16 ? 16 : $total_products;
                $similar_products = new LengthAwarePaginator($similar_products_query, $total, $perPage, $currentPage, [
                    'path' => url('/products/' . $id . '/' . $option_id . '/get-similar-products'),
                    'query' => $request->query(),
                ]);
        }
        return $similar_products;
    }
    public function showProductByCategory_slug($slug)
    {
        $category = Category::where('slug', $slug)->first();
        $category_ids = Category::where('parent_id', $category->id)->pluck('id')->toArray();
        $products = [];
        if (!empty($category_ids)) {
            $products = Product::with(['product_views','apiorderItem' , 'options' => function ($q) {
                $q->where('status', '!=', 'Disabled');
            }])
            ->whereIn('category_id', $category_ids)
            ->where('status' , '!=' , 'Inactive')
            ->get();
        }
        $user_id = Auth::id();
        $contact_id = session()->get('contact_id');
        $user_list = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->first();


        $user_buy_list_options = [];

        if (!empty($user_list)) {
            $user_buy_list_options = ProductBuyList::where('list_id', $user_list->id)->pluck('option_id', 'option_id')->toArray();
        }
        $lists = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->with('list_products')
            ->get();
        $product_views = null;
        if (auth()->user()) {
            $product_views = ProductView::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as entry_count'))
            ->whereNotNull('user_id')
            ->where('user_id' , auth()->id())
            ->orderBy('created_at' , 'DESC')
            ->groupBy('product_id')
            ->take(10)
            ->get();
            
        } else {
             $product_views = null;
        }

        $best_selling_products = null;
        $best_selling_products = ApiOrderItem::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
        ->whereHas('product' , function($query) {
            $query->where('status' , '!=' , 'Inactive');
        })
        ->select('product_id' , DB::raw('count(*) as entry_count'))
        ->orderBy('created_at' , 'DESC')
        ->groupBy('product_id')
        ->take(24)
        ->get();
        $notify_user_about_product_stock = AdminSetting::where('option_name', 'notify_user_about_product_stock')->first();
        $products_to_hide = BuyList::with('list_products')->where('title' , 'Products_to_hide')->first();
        
        if (!empty($products_to_hide)) {
            $products_to_hide = $products_to_hide->list_products->pluck('option_id')->toArray();
        }
        return view('categories', compact('products',
        'user_buy_list_options',
        'contact_id',
        'lists', 
        'product_views',
        'best_selling_products',
        'notify_user_about_product_stock',
        'products_to_hide'
        ));
    }

    public function showProductByBrands(Request $request, $name)
    {
        if ($request->get('per_page')) {
            $per_page = $request->get('per_page');
        } else {
            $per_page = 10;
        }

        $search_queries = $request->all();

        $products_query  = Product::with('options.price', 'brand' , 'product_views','apiorderItem')->where('brand', $name)->where('status' , '!=' , 'Inactive');

        $selected_category_id = $request->get('selected_category');

        $category_id = $selected_category_id;
        $brand_ids = Product::where('category_id', $selected_category_id)->pluck('brand_id', 'brand_id')->toArray();
        if (empty($brand_ids) && !empty($search_queries)) {
            $sub_category_ids = Category::where('parent_id', $selected_category_id)->pluck('id', 'id')->toArray();
            $brand_ids = Product::whereIn('category_id', $sub_category_ids)->pluck('brand_id', 'brand_id')->toArray();
            $products_query = Product::whereIn('category_id', $sub_category_ids);
        } elseif (!empty($selected_category_id)) {
            $products_query = Product::where('category_id', $selected_category_id)->with('brand', 'options');
        }
        $brand_id = $request->get('brand_id');
        if (!empty($brand_id)) {
            $products_query->where('brand_id', $brand_id);
        }
        $price_creteria = $request->get('search_price');
        if (!empty($price_creteria)) {
            if ($price_creteria == 'brand-a-to-z') {
                $products_query->orderBy('name', 'ASC');
            }
            if ($price_creteria == 'brand-z-to-a') {
                $products_query->orderBy('name', 'DESC');
            }
        }
        if (!empty($price_creteria)) {
            if ($price_creteria == 'best-selling') {
                $products_query->orderBy('views', 'DESC');
            }
        }
        if (!empty($price_creteria)) {
            if ($price_creteria == 'price-low-to-high') {
                $products_query->orderBy('retail_price', 'ASC');
            }
            if ($price_creteria == 'price-high-to-low') {
                $products_query->orderBy('retail_price', 'DESC');
            }
        }
        $stock = $request->get('stock');

        if (empty($stock)) {
            $products_query->where('stockAvailable', '>', 0);
        }

        if (!empty($stock) && $stock == 'in-stock') {
            $products_query->where('stockAvailable', '>', 0);
        }
        if (!empty($stock) && $stock == 'out-of-stock') {
            $products_query->where('stockAvailable', '<', 1);
        }

        if (empty($search_queries)) {
            $products = $products_query
            ->with(['product_views','apiorderItem' , 'options' => function ($q) {
                $q->where('status', '!=', 'Disabled');
            }])
            ->paginate($per_page);
        } else {
            $products = $products_query
            ->with(['product_views','apiorderItem' , 'options' => function ($q) {
                $q->where('status', '!=', 'Disabled');
            }])
            ->paginate($per_page);
        }
        $brands = [];
        if (!empty($brand_ids)) {
            $brands = Brand::whereIn('id', $brand_ids)->pluck('name', 'id')->toArray();
        } elseif (empty($search_queries)) {
            $brands = Brand::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }
        $categories = Category::orderBy('name', 'ASC')->where('parent_id', 0)->where('is_active' , 1)->get();
        $brand = Brand::where('name', $name)->first();
        $brand_id = $brand->id;
        $products_in_brand = Product::where('brand_id', $brand_id)->pluck('category_id', 'category_id')->toArray();
        $parent_ids = Category::whereIn('category_id', $products_in_brand)->pluck('parent_id', 'parent_id');
        $parent_names = Category::whereIn('category_id', $parent_ids)->pluck('name', 'id');
        $category_ids = Category::where('parent_id', $category_id)->pluck('id')->toArray();
        array_push($category_ids, $category_id);
        $all_product_ids = Product::whereIn('category_id', $category_ids)->pluck('id')->toArray();
        $brand_ids = Product::whereIn('id', $all_product_ids)->pluck('brand_id')->toArray();
        if (!empty($name)) {
            $brand = Brand::where('name', $name)->first();
            $brand_id = $brand->id;
        } else {
            $brand_id = $request->get('brand_id');
        }
        $stock = $request->get('stock');
        $price_creteria = $request->get('search_price');

        if (!empty($category_ids)) {
            $products_query = Product::where('status', '!=', 'Inactive')
                ->where('category_id', $category_id)
                ->with('options', 'brand');

            if (!empty($brand_id)) {
                $products_query->where('brand_id', $brand_id);
            }

            if (!empty($price_creteria)) {
                if ($price_creteria == 'brand-a-to-z') {
                    $products_query->orderBy('name', 'ASC');
                }
                if ($price_creteria == 'brand-z-to-a') {
                    $products_query->orderBy('name', 'DESC');
                }
            }
            if (!empty($price_creteria)) {
                if ($price_creteria == 'best-selling') {
                    $products_query->orderBy('views', 'DESC');
                }
            }
            if (!empty($price_creteria)) {
                if ($price_creteria == 'price-low-to-high') {
                    $products_query->orderBy('retail_price', 'ASC');
                }
                if ($price_creteria == 'price-high-to-low') {
                    $products_query->orderBy('retail_price', 'DESC');
                }
            }

            if (empty($stock)) {
                $products_query->where('stockAvailable', '>', 0);
            }
            if (!empty($stock) && $stock == 'in-stock') {
                $products_query->where('stockAvailable', '>', 0);
            }
            if (!empty($stock) && $stock == 'out-of-stock') {
                $products_query->where('stockAvailable', '<', 1);
            }

            if (!empty($selected_category_id)) {
                $sub_category_ids = Category::where('parent_id', $selected_category_id)->pluck('id')->toArray();
            }

            if ($category_id = '') {
                $parent_category = Category::find($category_id);
                $parent_category_slug = $parent_category->slug;
                $products_query = $products_query->where('category_id', $category_id);
            } else {
                $parent_category_slug  = '';
            }
        }
        $user_id = Auth::id();
        //$contact = Contact::where('user_id', $user_id)->first();
        $contact = '';
        if ($user_id != null) {
            $contact = Contact::where('user_id', $user_id)->first();
        }
        if ($contact) {
            $pricing = $contact->priceColumn;
        } else {
            $pricing = 'Retail';
        }
        $lists = BuyList::where('user_id', $user_id)->get();
        $category_id = $selected_category_id;

        $contact_id = session()->get('contact_id');
        $user_list = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->first();


        $user_buy_list_options = [];

        if (!empty($user_list)) {
            $user_buy_list_options = ProductBuyList::where('list_id', $user_list->id)->pluck('option_id', 'option_id')->toArray();
        }

        $product_views = null;
        if (auth()->user()) {
            $product_views = ProductView::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as entry_count'))
            ->whereNotNull('user_id')
            ->where('user_id' , auth()->id())
            ->orderBy('created_at' , 'DESC')
            ->groupBy('product_id')
            ->take(10)
            ->get();
            
        } else {
            $product_views = null;
        }
        
        $best_selling_products = null;
        $best_selling_products = ApiOrderItem::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
        ->whereHas('product' , function($query) {
            $query->where('status' , '!=' , 'Inactive');
        })
        ->select('product_id' , DB::raw('count(*) as entry_count'))
        ->orderBy('created_at' , 'DESC')
        ->groupBy('product_id')
        ->take(24)
        ->get();

        $notify_user_about_product_stock = AdminSetting::where('option_name', 'notify_user_about_product_stock')->first();
        $products_to_hide = BuyList::with('list_products')->where('title' , 'Products_to_hide')->first();
        
        if (!empty($products_to_hide)) {
            $products_to_hide = $products_to_hide->list_products->pluck('option_id')->toArray();
        }
        return view(
            'products-by-brand',
            compact(
                'products',
            'brands',
            'price_creteria',
            'categories',
            'per_page',
            'stock',
            'category_id',
            'parent_category_slug',
            'brand_id',
            'per_page',
            'name',
            'lists',
            'pricing',
            'user_buy_list_options',
            'contact_id',
            'product_views',
            'best_selling_products',
            'notify_user_about_product_stock',
            'products_to_hide' 
            )
        );
    }

    public function addToCart(Request $request)
    {
        
        $id = $request->p_id;
        $option_id = $request->option_id;
        $status = null;
        $message = null;
        $price = 0;
        $main_contact_id = null;
        $free_postal_state = false;
        $productOption = ProductOption::where('option_id', $option_id)->with('products.options.price')->first();
        $cart = session()->get('cart');
        if (Auth::id() !== null) {
            $user_id = Auth::id();
            $contact = Contact::where('user_id', $user_id)->first();
            if (!empty($contact)) {
                if ($contact->is_parent == 1) {
                    $main_contact_id = $contact->contact_id;
                    $free_postal_state = $contact->state == 'California' || $contact->state == 'CA' ? true : false;
                } else {
                    $main_contact_id = $contact->parent_id;
                    $free_postal_state = $contact->state == 'California' || $contact->state == 'CA' ? true : false;
                }
            } else {
                $main_contact_id = null;
                $free_postal_state = false;
            }
        } else {
            $user_id = '';
            $free_postal_state = true;
        }
        $actual_stock = 0;
        $actual_stock = !empty($productOption->stockAvailable)  ? $productOption->stockAvailable : 0;
        $user_price_column = UserHelper::getUserPriceColumn();
        foreach ($productOption->products->options as $option) {
            foreach ($option->price as $price_get) {
                // $price = !empty($price_get[$user_price_column]) ? $price_get[$user_price_column] : 0;
                // if (empty($price) || $price == 0 || $price == null || $price == '' || $price == '0') {
                //     $price = $price_get['sacramentoUSD'];
                // } else {
                //     $price = $price_get['retailUSD'];
                // }

                if (!empty($price_get[$user_price_column]) && $price_get[$user_price_column] != '0') {
                    $price = $price_get[$user_price_column];
                } elseif (!empty($price_get['sacramentoUSD']) && $price_get['sacramentoUSD'] != '0') {
                    $price = $price_get['sacramentoUSD'];
                } elseif (!empty($price_get['retailUSD']) && $price_get['retailUSD'] != '0') {
                    $price = $price_get['retailUSD'];
                }
        
            }
        }
        
        if (isset($cart[$id])) {
            $hash_cart = session()->get('cart_hash');
            $product_in_active_cart = Cart::where('qoute_id', $id)->first();
            if ($product_in_active_cart) {
                $current_quantity = $product_in_active_cart->quantity;
                if ($current_quantity + $request->quantity > intval($actual_stock)) {
                    $status = 'error';
                    $message = 'You can not add this item more than ' . intval($actual_stock) . ' in the cart';
                } 
                else {

                    $product_in_active_cart->quantity = $current_quantity + $request->quantity;
                    $product_in_active_cart->save();
                    $status = 'success';
                    $message = 'Product added to cart successfully';
                    $cart[$id]['quantity'] += $request->quantity;
                }
            }
        } else {
            $hash_cart = $request->session()->get('cart_hash');
            $cart_hash_exist = session()->has('cart_hash');


            if ($cart_hash_exist == false) {
                $request->session()->put('cart_hash', Str::random(10));
            }
            if ($request->quantity > intval($actual_stock)) {
                $status = 'error';
                $message = 'You can not add more this item than ' . intval($actual_stock) . ' in the cart';
            } 
            else {
                $cart[$id] = [
                    "product_id" => $productOption->products->product_id,
                    "name" => $productOption->products->name,
                    "quantity" => $request->quantity,
                    "price" => $price,
                    "code" => $productOption->code,
                    "image" => !empty($productOption->products) && !empty($productOption->products->images) ? $productOption->products->images : '',
                    'option_id' => $productOption->option_id,
                    "slug" => $productOption->products->slug,
                    "cart_hash" => session()->get('cart_hash')
                ];
                $cart[$id]['user_id'] = $user_id;
                $cart[$id]['is_active'] = 1;
                $cart[$id]['qoute_id'] = $id;

                $qoute = Cart::create($cart[$id]);
                $status = 'success';
                $message = 'Product added to cart successfully';
            }
        }

        $request->session()->put('cart', $cart);
        $cart_items = session()->get('cart');
        return response()->json([
            'status' => $status,
            'cart_items' => $cart_items,
            'cart' => $cart,
            'message' => $message,
            'actual_stock' => $actual_stock,
            'main_contact_id' => $main_contact_id,
            'free_postal_state' => $free_postal_state
        ]);
    }

    public function removeProductByCategory(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            //dd($request->id);
            if (isset($cart[$request->id])) {
                $qoute = Cart::where('qoute_id', $request->id)->delete();
                unset($cart[$request->id]);
            }

            $request->session()->put('cart', $cart);

            session()->flash('success', 'Product removed successfully');
        }
        return redirect()->back()->with('success', 'Product removed successfully!');
    }

    public function cart(Request $request)
    {
        $contact = [];
        $subtotal = 0;
        $cart_total = 0;
        $tax_rate = 0;
        $tax = 0 ;
        $d_none = 'd-none'; 
        $calculate_free_shipping = 0;
        $free_shipping = 0;
        $user_id = auth()->id();
        $parent_contact = null;
        $enable_free_shipping_banner = AdminSetting::where('option_name', 'enable_free_shipping_banner')->first();
        $free_shipping_value  = AdminSetting::where('option_name', 'free_shipping_value')->first();
        // $company = session()->get('company');
        $contact_id = session()->get('contact_id');
        $is_child = false; 
        
        if (!empty($user_id) && !empty($contact_id)) {
            $contact = Contact::where('user_id', $user_id)->where('contact_id', $contact_id)
                ->orWhere('secondary_id', $contact_id)
                ->first();
            
        } else {
            $contact = Contact::where('user_id', $user_id)->where('status' , 1)->first();
        }

        if (!empty($contact) && $contact->is_parent == 0) {
            $is_child = true;
        }

        if ($is_child == true) {
            $parent_contact = Contact::where('contact_id', $contact->parent_id)->first();
        } else {
            $parent_contact = $contact;
        }
        
        if (empty($contact)) {
            abort(404);
        }
        
        $cart_items = UserHelper::switch_price_tier($request);
        $tax_class = TaxClass::where('name', $contact->tax_class)->first();

        if (!empty($cart_items)) {
            foreach ($cart_items as $cart_item) {
                $subtotal += $cart_item['price'] * $cart_item['quantity'];
            }

            if (!empty($tax_class)) {
                $tax_rate = $subtotal * ($tax_class->rate / 100);
                $tax = $tax_rate;
            }

            $cart_total = $subtotal + $tax;

            if (!empty($free_shipping_value)) {
                $free_shipping = $free_shipping_value->option_value;
            } else {
                $free_shipping = 0;
            }
            $calculate_free_shipping = $free_shipping - $cart_total;
        }

        if ($calculate_free_shipping <= intval($free_shipping) && $calculate_free_shipping >= 0) {
            $d_none = '';
        } else {
            $d_none = 'd-none';
        }

        if ($cart_total >= intval($free_shipping) && $cart_total >= 0) {
            $congrats_div_dnone = '';
        } else {
            $congrats_div_dnone = 'd-none';
        }

        $new_checkout_flow = AdminSetting::where('option_name', 'new_checkout_flow')->first();

        if (!empty($cart_items)) {
            $view = 'cart';
        } else {
            $view = 'empty-cart';
        }
        return view($view, compact(
            'cart_items',
            'contact',
            'tax_class',
            'parent_contact',
            'cart_total',
            'enable_free_shipping_banner',
            'calculate_free_shipping',
            'free_shipping_value',
            'free_shipping',
            'd_none',
            'congrats_div_dnone',
            'new_checkout_flow'

        ));
    }

    public function updateCart(Request $request)
    {
        $quantity = $request->post('items_quantity');
        $cart_items = session()->get('cart');
        $main_contact_id = null;
        $free_postal_state = false;
        if (Auth::id() !== null) {
            $user_id = Auth::id();
            $contact = Contact::where('user_id', $user_id)->first();
            if (!empty($contact)) {
                if ($contact->is_parent == 1) {
                    $main_contact_id = $contact->contact_id;
                    $free_postal_state = $contact->state == 'California' || $contact->state == 'CA' ? true : false;
                } else {
                    $main_contact_id = $contact->parent_id;
                    $free_postal_state = $contact->state == 'California' || $contact->state == 'CA' ? true : false;
                }
            } else {
                $main_contact_id = null;
                $free_postal_state = false;
            }
        } else {
            $user_id = '';
            $free_postal_state = true;
        }
        
        $user_id = auth()->id();

        
        
        if (!empty($quantity)) {
            $product_id = $request->post('product_id');
            $cart_item = isset($cart_items[$product_id]) ? $cart_items[$product_id] : array();

            if (!empty($cart_item) && $cart_item['quantity'] != $quantity) {
                Session::put('cart.' . $product_id . '.quantity', $quantity);
                $cart_items = session()->get('cart');
                
                if (!empty($user_id)) {
                    $cart = Cart::where('qoute_id', $product_id)
                        ->where('user_id', $user_id)
                        ->first();

                    if (!empty($cart)) {
                        $cart->quantity = $quantity;
                        $cart->save();
                    }
                }
                else {
                    if ($request->session()->has('cart_hash')) {
                        $cart_hash = $request->session()->get('cart_hash');
                        $cart = Cart::where('qoute_id', $product_id)
                            ->where('user_id', 0)
                            ->where('cart_hash', $cart_hash)
                            ->first();

                        if (!empty($cart)) {
                            $cart->quantity = $quantity;
                            $cart->save();
                        }
                    }
                }
            }
        }
        
        $cart_item = session()->get('cart');
        return response()->json([
            'status' => 'success',
            'cart_items' => $cart_items,
            'main_contact_id' => $main_contact_id,
            'free_postal_state' => $free_postal_state
        ]);
    }

    public function update_product_cart(Request $request) {
        $id = $request->p_id;
        $option_id = $request->option_id;
        $action = $request->action;
        $subtraction = false;
        $status = null;
        $message = null;
        $main_contact_id = null;
        $free_postal_state = false;
        if (!empty($action) && $action === 'subtraction') {
            $subtraction = true;
        }
        $productOption = ProductOption::where('option_id', $option_id)->with('products.options.price')->first();
        $actual_stock = 0;
        $actual_stock = !empty($productOption->stockAvailable)  ? $productOption->stockAvailable : 0;
        $cart = session()->get('cart');
        if (Auth::id() !== null) {
            $user_id = Auth::id();
            $contact = Contact::where('user_id', $user_id)->first();
            if (!empty($contact)) {
                if ($contact->is_parent == 1) {
                    $main_contact_id = $contact->contact_id;
                    $free_postal_state = $contact->state == 'California' || $contact->state == 'CA' ? true : false;
                } else {
                    $main_contact_id = $contact->parent_id;
                    $free_postal_state = $contact->state == 'California' || $contact->state == 'CA' ? true : false;
                }
            } else {
                $main_contact_id = null;
                $free_postal_state = false;
            }
        } else {
            $user_id = '';
            $free_postal_state = true;
        }
        $user_price_column = UserHelper::getUserPriceColumn();
        foreach ($productOption->products->options as $option) {
            foreach ($option->price as $price_get) {
                // $price = isset($price_get[$user_price_column]) ? $price_get[$user_price_column] : 0;
                // if ($price == 0) {
                //     $price = $price_get['sacramentoUSD'];
                // }

                // if ($price == 0) {
                //     $price = $price_get['retailUSD'];
                // }

                if (!empty($price_get[$user_price_column]) && $price_get[$user_price_column] != '0') {
                    $price = $price_get[$user_price_column];
                } elseif (!empty($price_get['sacramentoUSD']) && $price_get['sacramentoUSD'] != '0') {
                    $price = $price_get['sacramentoUSD'];
                } elseif (!empty($price_get['retailUSD']) && $price_get['retailUSD'] != '0') {
                    $price = $price_get['retailUSD'];
                }
            }
        }
        if ($subtraction == true) {
            if (isset($cart[$id])) {
                $hash_cart = session()->get('cart_hash');
                $product_in_active_cart = Cart::where('qoute_id', $id)->first();
                if (!empty($product_in_active_cart)) {
                    if ($product_in_active_cart->quantity == 1) {
                        $product_in_active_cart->delete();
                        unset($cart[$id]);
                        $status = 'success';
                        $message = 'Product removed from cart successfully';
                    } else {
                        $current_quantity = $product_in_active_cart->quantity;
                        $current_quantity = $product_in_active_cart->quantity;
                        
                        
                        $product_in_active_cart->quantity = $current_quantity - $request->quantity;
                        $product_in_active_cart->save();
                        if (intval($product_in_active_cart->quantity) > intval($actual_stock)) {
                            $cart[$id]['quantity'] -= $request->quantity;
                            $status = 'error';
                            $message = 'You can not add this item more than ' . intval($actual_stock) . ' in the cart';
                        } 
                        else {
                            $cart[$id]['quantity'] -= $request->quantity;
                            $status = 'success';
                            $message = 'Product removed from cart successfully';
                        }
                        
                    }
                }
            } 
            else {
    
                $hash_cart = $request->session()->get('cart_hash');
                $cart_hash_exist = session()->has('cart_hash');
    
    
                if ($cart_hash_exist == false) {
                    $request->session()->put('cart_hash', Str::random(10));
                }
                if ($request->quantity > intval($actual_stock)) {
                    $status = 'error';
                    $message = 'You can not add more this item than ' . intval($actual_stock) . ' in the cart';
                }
                else {
                    $cart[$id] = [
                        "product_id" => $productOption->products->product_id,
                        "name" => $productOption->products->name,
                        "quantity" => $request->quantity,
                        "price" => $price,
                        "code" => $productOption->code,
                        "image" => $productOption->image,
                        'option_id' => $productOption->option_id,
                        "slug" => $productOption->products->slug,
                        "cart_hash" => session()->get('cart_hash')
                    ];
                    $cart[$id]['user_id'] = $user_id;
                    $cart[$id]['is_active'] = 1;
                    $cart[$id]['qoute_id'] = $id;
        
                    $qoute = Cart::create($cart[$id]);
                    $status = 'success';
                    $message = 'Product added to cart successfully';
                }
            }
        }
        else {
            if (isset($cart[$id])) {
                $hash_cart = session()->get('cart_hash');
                $product_in_active_cart = Cart::where('qoute_id', $id)->first();
                // if ($product_in_active_cart) {
                //     $current_quantity = $product_in_active_cart->quantity;
                //     $product_in_active_cart->quantity = $current_quantity + $request->quantity;
                //     $product_in_active_cart->save();
                // }
                // $cart[$id]['quantity'] += $request->quantity;
                if ($product_in_active_cart) {
                    $current_quantity = $product_in_active_cart->quantity;
                    if (intval($current_quantity + $request->quantity) > intval($actual_stock)) {
                        $status = 'error';
                        $message = 'You can not add this item more than ' . intval($actual_stock) . ' in the cart';
                    } 
                    else {
    
                        $product_in_active_cart->quantity = $current_quantity + $request->quantity;
                        $product_in_active_cart->save();
                        $status = 'success';
                        $message = 'Product added to cart successfully';
                        $cart[$id]['quantity'] += $request->quantity;
                    }
                } 
            }
            
            else {
    
                $hash_cart = $request->session()->get('cart_hash');
                $cart_hash_exist = session()->has('cart_hash');
    
    
                if ($cart_hash_exist == false) {
                    $request->session()->put('cart_hash', Str::random(10));
                }
                if ($request->quantity > intval($actual_stock)) {
                    $status = 'error';
                    $message = 'You can not add more this item than ' . intval($actual_stock) . ' in the cart';
                } 
                // $cart[$id] = [
                //     "product_id" => $productOption->products->product_id,
                //     "name" => $productOption->products->name,
                //     "quantity" => $request->quantity,
                //     "price" => $price,
                //     "code" => $productOption->code,
                //     "image" => $productOption->image,
                //     'option_id' => $productOption->option_id,
                //     "slug" => $productOption->products->slug,
                //     "cart_hash" => session()->get('cart_hash')
                // ];
                // $cart[$id]['user_id'] = $user_id;
                // $cart[$id]['is_active'] = 1;
                // $cart[$id]['qoute_id'] = $id;
    
                // $qoute = Cart::create($cart[$id]);
                else {
                    $cart[$id] = [
                        "product_id" => $productOption->products->product_id,
                        "name" => $productOption->products->name,
                        "quantity" => $request->quantity,
                        "price" => $price,
                        "code" => $productOption->code,
                        "image" => !empty($productOption->products) && !empty($productOption->products->images) ? $productOption->products->images : '',
                        'option_id' => $productOption->option_id,
                        "slug" => $productOption->products->slug,
                        "cart_hash" => session()->get('cart_hash')
                    ];
                    $cart[$id]['user_id'] = $user_id;
                    $cart[$id]['is_active'] = 1;
                    $cart[$id]['qoute_id'] = $id;
    
                    $qoute = Cart::create($cart[$id]);
                    $status = 'success';
                    $message = 'Product added to cart successfully';
                }
            }
        }
        
        $request->session()->put('cart', $cart);
        $cart_items = session()->get('cart');
        return response()->json([
            'status' => $status,
            'cart_items' => $cart_items,
            'cart' => $cart,
            'message' => $message,
            'actual_stock' => $actual_stock,
            'free_postal_state'=> $free_postal_state
        ]);
    }

    public function productSearch(Request $request)
    {
        if ($request->ajax()) {
            $brand = $request->input('brand');
            $price = $request->input('price');
            $stock = $request->input('instock');
            //$perpage = $request->input('perPage');
            $products = Product::with(['options' => function ($q) use ($price) {
                $q->where('status', '!=', 'Disabled')->where('retailPrice', '>=', $price)->where('stockAvailable', '>', 0);
            }])->where('status', '!=', 'Inactive')->where('brand_id', $brand)->paginate(20);
            $count = 0;
            return view('search_product.filters', compact('products', 'count', 'brand'))->render();
        }
        if ($request->get('per_page')) {
            $per_page = $request->get('per_page');
        } else {
            $per_page = 20;
        }

        $search_queries = $request->all();


        $products_query  = Product::with('options', 'brand', 'categories' , 'product_views','apiorderItem')->where('status', '!=', 'Inactive');
        $selected_category_id = $request->get('selected_category');

        $category_id = $selected_category_id;
        $brand_ids = Product::where('category_id', $selected_category_id)->pluck('brand_id', 'brand_id')->toArray();
        if (empty($brand_ids) && !empty($search_queries)) {
            $sub_category_ids = Category::where('parent_id', $selected_category_id)->pluck('id', 'id')->toArray();
            $brand_ids = Product::whereIn('category_id', $sub_category_ids)->pluck('brand_id', 'brand_id')->toArray();
            $products_query = Product::whereIn('category_id', $sub_category_ids)->where('status', '!=', 'Inactive');
        } elseif (!empty($selected_category_id)) {
            $products_query = Product::where('category_id', $selected_category_id)->where('status', '!=', 'Inactive')->with('brand', 'options');
        }
        $brand_id = $request->get('brand_id');
        if (!empty($brand_id)) {
            $products_query->where('brand_id', $brand_id);
        }
        $price_creteria = $request->get('search_price');
        if (!empty($price_creteria)) {
            if ($price_creteria == 'brand-a-to-z') {
                $products_query->orderBy('name', 'ASC');
            }
            if ($price_creteria == 'brand-z-to-a') {
                $products_query->orderBy('name', 'DESC');
            }
        }
        if (!empty($price_creteria)) {
            if ($price_creteria == 'best-selling') {
                $products_query->orderBy('views', 'DESC');
            }
        }
        if (!empty($price_creteria)) {
            if ($price_creteria == 'price-low-to-high') {
                $products_query->orderBy('retail_price', 'ASC');
            }
            if ($price_creteria == 'price-high-to-low') {
                $products_query->orderBy('retail_price', 'DESC');
            }
        }

        $stock = $request->get('stock');


        if (empty($stock)) {
            $products_query->where('stockAvailable', '>', 0);
        }

        if (!empty($stock) && $stock == 'in-stock') {
            $products_query->where('stockAvailable', '>', 0);
        }
        if (!empty($stock) && $stock == 'out-of-stock') {
            $products_query->where('stockAvailable', '<', 1);
        }

        if (empty($search_queries)) {
            $products = $products_query->with(['product_views','apiorderItem' , 'options' => function ($q) {
                $q->where('status', '!=', 'Disabled');
            }])
            // ->whereHas('options.defaultPrice', function ($q) {
            //     $q->where('retailUSD', '!=', 0);
            // })
            ->paginate($per_page);
            // $products = $products_query->paginate($per_page);
        } else {
            // $products = $products_query->with('options', 'brand')->paginate($per_page);
            $products = $products_query->with(['product_views','brand','apiorderItem' , 'options' => function ($q) {
                $q->where('status', '!=', 'Disabled');
            }])
            // ->whereHas('options.defaultPrice', function ($q) {
            //     $q->where('retailUSD', '!=', 0);
            // })
            ->paginate($per_page);
        }
        $brands = [];
        if (!empty($brand_ids)) {
            $brands = Brand::whereIn('id', $brand_ids)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        } elseif (empty($search_queries)) {
            $brands = Brand::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }
        $categories = Category::where('parent_id', 0)->where('is_active' , 1)->orderBy('name', 'ASC')->get();
        $category_ids = Category::where('parent_id', $category_id)->pluck('id')->toArray();
        array_push($category_ids, $category_id);
        $all_product_ids = Product::whereIn('category_id', $category_ids)->pluck('id')->toArray();
        $brand_ids = Product::whereIn('id', $all_product_ids)->pluck('brand_id')->toArray();
        $brand_id = $request->get('brand_id');
        $stock = $request->get('stock');
        $price_creteria = $request->get('search_price');

        if (!empty($category_ids)) {
            $products_query = Product::where('status', '!=', 'Inactive')
                ->where('category_id', $category_id)
                ->with('options', 'brand');

            if (!empty($brand_id)) {
                $products_query->where('brand_id', $brand_id);
            }

            if (!empty($price_creteria)) {
                if ($price_creteria == 'brand-a-to-z') {
                    $products_query->orderBy('name', 'ASC');
                }
                if ($price_creteria == 'brand-z-to-a') {
                    $products_query->orderBy('name', 'DESC');
                }
            }
            if (!empty($price_creteria)) {
                if ($price_creteria == 'best-selling') {
                    $products_query->orderBy('views', 'DESC');
                }
            }
            if (!empty($price_creteria)) {
                if ($price_creteria == 'price-low-to-high') {
                    $products_query->orderBy('retail_price', 'ASC');
                }
                if ($price_creteria == 'price-high-to-low') {
                    $products_query->orderBy('retail_price', 'DESC');
                }
            }

            if (empty($stock)) {
                $products_query->where('stockAvailable', '>', 0);
            }
            if (empty($stock) || $stock == 'in-stock') {
                $products_query->where('stockAvailable', '>', 0);
            } elseif (!empty($stock) && $stock == 'out-of-stock') {
                $products_query->where('stockAvailable', '<', 1);
            }

            if (!empty($selected_category_id)) {
                $sub_category_ids = Category::where('parent_id', $selected_category_id)->pluck('id')->toArray();
            }

            if ($category_id = '') {
                $parent_category = Category::find($category_id);
                $parent_category_slug = $parent_category->slug;
                $products_query = $products_query->where('category_id', $category_id);
            } else {
                $parent_category_slug  = '';
            }
        }

        $is_search = $request->get('is_search');

        $searchvalue = $request->value;
        $value = $searchvalue;

        // new filters
        $filter_value_main = $request->main_search_filter;
        $replace_special_characters = preg_replace('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', ' ', $searchvalue);
        $explode_search_value = explode(' ', $replace_special_characters);
        
        if ($filter_value_main === 'title_description') {
            $main_query = Product::with(['product_views','apiorderItem' , 'options' => function ($q) {
                $q->where('status', '!=', 'Disabled');
            }])
            // ->whereHas('options.defaultPrice', function ($q) {
            //     $q->where('retailUSD', '!=', 0);
            // })
            ->where(function (Builder $query) use ($explode_search_value) {
                foreach ($explode_search_value as $searchvalue) {
                    $query->where('name', 'LIKE', '%' . $searchvalue . '%')
                    ->where('status', '!=', 'Inactive');
                }
            })
            ->orWhere(function (Builder $query) use ($explode_search_value) {
                foreach ($explode_search_value as $searchvalue) {
                    $query->where('description', 'LIKE', '%' . $searchvalue . '%')
                    ->where('status', '!=', 'Inactive');
                }
            })
            ->orWhere(function (Builder $query) use ($searchvalue) {
                $query->where('code', 'LIKE', '%' . $searchvalue . '%')
                ->where('status', '!=', 'Inactive');
            })
            ->orWhereExists(function ($q) use ($searchvalue) {
                $q->select(DB::raw(1))
                ->from('product_options')
                ->whereColumn('products.product_id', 'product_options.product_id')
                ->where('code',  $searchvalue )
                ->where('status', '!=', 'Disabled');
            })
            ->where('status', '!=', 'Inactive')
            ->paginate($per_page);
            $products = $main_query;
        } 

        if ($filter_value_main === 'title') {
            $main_query = Product::with(['product_views','apiorderItem' , 'options' => function ($q) {
                $q->where('status', '!=', 'Disabled');
            }])
            ->where(function (Builder $query) use ($explode_search_value) {
                foreach ($explode_search_value as $searchvalue) {
                    $query->where('name', 'LIKE', '%' . $searchvalue . '%')
                    ->where('status', '!=', 'Inactive');
                }
            })
            ->orWhere(function (Builder $query) use ($searchvalue) {
                $query->where('code', 'LIKE', '%' . $searchvalue . '%')
                ->where('status', '!=', 'Inactive');
            })
            ->orWhereExists(function ($q) use ($searchvalue) {
                $q->select(DB::raw(1))
                ->from('product_options')
                ->whereColumn('products.product_id', 'product_options.product_id')
                ->where('code',  $searchvalue )
                ->where('status', '!=', 'Disabled');
            })
            ->where('status', '!=', 'Inactive')
            ->paginate($per_page);
            $products = $main_query;
        }


        if ($filter_value_main === 'description') {
            $main_query = Product::with(['product_views','apiorderItem' , 'options' => function ($q) {
                $q->where('status', '!=', 'Disabled');
            }])
            ->where(function (Builder $query) use ($explode_search_value) {
                foreach ($explode_search_value as $searchvalue) {
                    $query->where('description', 'LIKE', '%' . $searchvalue . '%')
                    ->where('status', '!=', 'Inactive');
                }
            })
            ->orWhere(function (Builder $query) use ($searchvalue) {
                $query->where('code', 'LIKE', '%' . $searchvalue . '%')
                ->where('status', '!=', 'Inactive');
            })
            ->orWhereExists(function ($q) use ($searchvalue) {
                $q->select(DB::raw(1))
                ->from('product_options')
                ->whereColumn('products.product_id', 'product_options.product_id')
                ->where('code',  $searchvalue )
                ->where('status', '!=', 'Disabled');
            })
            ->where('status', '!=', 'Inactive')
            ->paginate($per_page);
            $products = $main_query;
        }

        
        $searched_value = $request->value;

        $category_id = $selected_category_id;
        $user_id = Auth::id();;
        $lists = BuyList::where('user_id', $user_id)->get();
        $contact = '';
        if ($user_id != null) {
            $contact = Contact::where('user_id', $user_id)->first();
        }
        $user_id = Auth::id();
        $contact = '';
        if ($user_id != null) {
            $contact = Contact::where('user_id', $user_id)->first();
        }

        $contact_id = '';

        if ($contact) {
            $pricing = $contact->priceColumn;
            $contact_id = $contact->contact_id;
        } else {
            $pricing = 'RetailUSD';
        }


        // recent view products

        $contact_id = session()->get('contact_id');
        $user_list = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->first();


        $user_buy_list_options = [];

        if (!empty($user_list)) {
            $user_buy_list_options = ProductBuyList::where('list_id', $user_list->id)->pluck('option_id', 'option_id')->toArray();
        }

        $product_views = null;
        if (auth()->user()) {
            $product_views = ProductView::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as entry_count'))
            ->whereNotNull('user_id')
            ->where('user_id' , auth()->id())
            ->orderBy('created_at' , 'DESC')
            ->groupBy('product_id')
            ->take(10)
            ->get();
            
        } else {
            $product_views = null;
        }

        $best_selling_products = null;
        $best_selling_products = ApiOrderItem::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
        ->whereHas('product' , function($query) {
            $query->where('status' , '!=' , 'Inactive');
        })
        ->select('product_id' , DB::raw('count(*) as entry_count'))
        ->orderBy('created_at' , 'DESC')
        ->groupBy('product_id')
        ->take(24)
        ->get();
        $notify_user_about_product_stock = AdminSetting::where('option_name', 'notify_user_about_product_stock')->first();
        $products_to_hide = BuyList::with('list_products')->where('title' , 'Products_to_hide')->first();
        
        if (!empty($products_to_hide)) {
            $products_to_hide = $products_to_hide->list_products->pluck('option_id')->toArray();
        }

       
        return view('search_product.search_product', compact(
            'products',
            'brands',
            'price_creteria',
            'categories',
            'stock',
            'category_id',
            'parent_category_slug',
            'brand_id',
            'per_page',
            'searched_value',
            'lists',
            'contact_id',
            'pricing',
            'filter_value_main',
            'user_buy_list_options',
            'product_views',
            'best_selling_products',
            'notify_user_about_product_stock',
            'products_to_hide'
        ));
    }


    public function addToWishList(Request $request)
    {
        $user_id = Auth::id();
        //check if user have list already
        $contact_id = session()->get('contact_id');
        $contact =  Contact::where('contact_id', $contact_id)->orWhere('secondary_id', $contact_id)->first();
        $active_price = 0;

        if (empty($contact_id) || empty($user_id)) {
            return response()->json([
                'success' => false,
                'msg' => 'Please make sure you are logged in and chosen the company !'
            ]);
        }


        if ($contact->secondary_id) {
            $contact =  Contact::where('secondary_id', $contact->secondary_id)->first();
            $parent_id = $contact->parent_id;
            $contact_pricing =  Contact::where('contact_id', $parent_id)->first();
            $contact_pricing = lcfirst($contact->priceColumn);
        } else {
            $contact_pricing = lcfirst($contact->priceColumn);
        }

        $prices = Pricingnew::where('option_id', $request->option_id)->get();
        foreach ($prices as $price) {
            $active_price = $price->$contact_pricing;
            if ($active_price == 0) {
                $active_price = $price->sacramentoUSD;
            } 
            if ($active_price == 0) {
                $active_price = $price->retailUSD;
            } 
        }
        $user_lists = BuyList::where('user_id', $user_id)->where('contact_id', $contact_id)->where('title', 'My Favorites')->exists();
        if ($user_lists == false) {
            $wishlist = new BuyList();
            $wishlist->title = 'My Favorites';
            $wishlist->status = 'Public';
            $wishlist->description = 'My Favorites';
            $wishlist->user_id = $user_id;
            $wishlist->contact_id = $contact_id;
            $wishlist->save();
            $list_id = $wishlist->id;
        } else {
            $list = BuyList::where('title', 'My Favorites')->where('user_id', $user_id)->where('contact_id', $contact_id)->first();
            $list_id = $list->id;
        }

        $status = $request->get('status');
        if (!empty($status)) {
            $product_buy_list = ProductBuyList::where('list_id', $list_id)
                ->where('product_id', $request->product_id)
                ->where('option_id', $request->option_id)
                ->first();

            if (!empty($product_buy_list)) {
                $product_buy_list->delete();
            }
        } else {

            $flag = ProductBuyList::where('list_id', $list_id)
                ->where('product_id', $request->product_id)
                ->where('option_id', $request->option_id)
                ->first();
            if (empty($flag)) {
                $product_buy_list = new ProductBuyList();
                $product_buy_list->list_id = $list_id;
                $product_buy_list->product_id = $request->product_id;
                $product_buy_list->option_id = $request->option_id;
                $product_buy_list->sub_total = $active_price;
                $product_buy_list->quantity = $request->quantity;
                $product_buy_list->save();
            } else {
                $flag->delete();
            }
        }

        return response()->json([
            'success' => true,
            'msg' => 'List created Successfully !'
        ]);
    }

    public function getWishlists()
    {
        $user_id = Auth::id();
        $contact_id = session()->get('contact_id');
        $lists = BuyList::where('user_id', $user_id)->where('contact_id', $contact_id)->with('list_products.product.options.price')->where('title', 'My Favorites')->get();

        // dd($lists);
        //$list_title = $list->title;
        $images = [];
        // dd($lists);
        foreach ($lists as $list) {
            foreach ($list->list_products as $single_product) {
                foreach ($single_product->product as $image) {
                    array_push($images, $image);
                }
            }
        }

        // foreach ($lists->list_products as $list){
        //     foreach ($list->product->options as $image) {
        //         array_push($images, $image->image);
        //     }
        // }
        // dd($images);
        return view('wishlists.index', compact(
            'lists',
            'images'
        ));
        return $images;
    }

    public function getListNames()
    {
        $user_id = Auth::id();
        $lists = BuyList::where('user_id', $user_id)->where('type', 'wishlist')->get();
        return response()->json([
            'msg' => 'success',
            'lists' => $lists
        ]);
    }


    public function createList(Request $request)
    {
        $user_id = Auth::id();
        $request->list_title;
        $buyList = new BuyList();
        $buyList->title = $request->list_title;
        $buyList->status = 'Public';
        $buyList->description = $request->list_title;
        $buyList->user_id = $user_id;
        $buyList->type = $request->type;
        $buyList->save();
        return response()->json([
            'status' => 'success',
            'msg' => 'List created Successully'
        ]);
    }

    public function delete_favorite_product(Request $request)
    {
        $user_id = Auth::id();
        $contact_id = session()->get('contact_id');
        $lists = BuyList::where('user_id', $user_id)->where('contact_id', $contact_id)->with('list_products.product.options.price')->where('title', 'My Favorites')->get();

        $product_buy_list_id = $request->product_buy_list_id;
        $delete_favorite = ProductBuyList::find($product_buy_list_id)->delete();
        return response()->json([
            'status' => 'success',
            'lists' => $lists,
            'message' => 'product removed successfully!'
        ], 200);
    }

    public function get_child_categories($parent_id)
    {
        $child_categories = Category::where('parent_id', $parent_id)->get();
        return response()->json([
            'status' => 'success',
            'child_categories' => $child_categories
        ], 200);
    }

    //add multi favorites to cart
    public function multi_favorites_to_cart(Request $request)
    {
        $error = false;
        
        $products_to_hide = BuyList::with('list_products')->where('title' , 'Products_to_hide')->first();

        $main_contact_id = null;
        $free_postal_state = false;
        if (Auth::id() !== null) {
            $userId = Auth::id();
            $contact = Contact::where('user_id', $userId)->first();
            if (!empty($contact)) {
                if ($contact->is_parent == 1) {
                    $main_contact_id = $contact->contact_id;
                    $free_postal_state = $contact->state == 'California' || $contact->state == 'CA' ? true : false;
                } else {
                    $main_contact_id = $contact->parent_id;
                    $free_postal_state = $contact->state == 'California' || $contact->state == 'CA' ? true : false;
                }
            } else {
                $main_contact_id = null;
                $free_postal_state = false;
            }
        } else {
            $free_postal_state = true;
        }
    
        if (!empty($products_to_hide)) {
            $products_to_hide = $products_to_hide->list_products->pluck('option_id')->toArray();
        }
        if (!empty($request->all_fav)) {
            foreach ($request->all_fav as $multi_favorites) {
                $id = $multi_favorites['product_id'];
                $option_id = $multi_favorites['option_id'];

                $productOption = ProductOption::where('option_id', $option_id)->with('products.options.price')->whereNotIn('option_id', $products_to_hide)->first();
                if (empty($productOption) || empty($productOption->products)) {
                    $error = true;
                    continue;
                }
                $cart = session()->get('cart');
                if (Auth::id() !== null) {
                    $user_id = Auth::id();
                } else {
                    $user_id = '';
                }

                $contact_id = '';
                $secondary_id = '';

                if ($user_id) {
                    $contact = Contact::where('user_id', $user_id)->first();
                    $contact_id = $contact->contact_id;
                    $secondary_id = $contact->secondary_id;
                }

                if ($contact_id || $secondary_id) {
                    $pricing = $contact->priceColumn;
                }

                $user_price_column = UserHelper::getUserPriceColumn();
                foreach ($productOption->products->options as $option) {
                    foreach ($option->price as $price_get) {
                        // $price = isset($price_get[$user_price_column]) ? $price_get[$user_price_column] : 0;
                        // if ($price == 0) {
                        //     $price = $price_get['sacramentoUSD'];
                        // }
        
                        // if ($price == 0) {
                        //     $price = $price_get['retailUSD'];
                        // }

                        if (!empty($price_get[$user_price_column]) && $price_get[$user_price_column] != '0') {
                            $price = $price_get[$user_price_column];
                        } elseif (!empty($price_get['sacramentoUSD']) && $price_get['sacramentoUSD'] != '0') {
                            $price = $price_get['sacramentoUSD'];
                        } elseif (!empty($price_get['retailUSD']) && $price_get['retailUSD'] != '0') {
                            $price = $price_get['retailUSD'];
                        }
                    }
                }

                if (isset($cart[$id])) {
                    $hash_cart = session()->get('cart_hash');
                    $product_in_active_cart = Cart::where('qoute_id', $id)->first();
                    if ($product_in_active_cart) {
                        $current_quantity = $product_in_active_cart->quantity;
                        $product_in_active_cart->quantity = $current_quantity + $request->quantity;
                        $product_in_active_cart->save();
                    }
                    $cart[$id]['quantity'] += $request->quantity;
                } else {

                    $hash_cart = $request->session()->get('cart_hash');
                    $cart_hash_exist = session()->has('cart_hash');


                    if ($cart_hash_exist == false) {
                        $request->session()->put('cart_hash', Str::random(10));
                    }

                    $cart[$id] = [
                        "product_id" => $productOption->products->product_id,
                        "name" => $productOption->products->name,
                        "quantity" => $request->quantity,
                        "price" => $price,
                        "code" => $productOption->code,
                        "image" => $productOption->image,
                        'option_id' => $productOption->option_id,
                        "slug" => $productOption->products->slug,
                        "cart_hash" => session()->get('cart_hash')
                    ];
                    $cart[$id]['user_id'] = $user_id;
                    $cart[$id]['is_active'] = 1;
                    $cart[$id]['qoute_id'] = $id;

                    $qoute = Cart::create($cart[$id]);
                }

                $request->session()->put('cart', $cart);
                $cart_items = session()->get('cart');
            }

            if ($error == true) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Some products are not available in the cart',
                    'free_postal_state' => $free_postal_state,
                    'main_contact_id' => $main_contact_id
                ]);
            }
            return response()->json([
                'status' => 'success',
                'cart_items' => $cart_items,
                'cart' => $cart,
                'main_contact_id' => $main_contact_id,
                'free_postal_state' => $free_postal_state
            ]);
        }
    }

    // order buyed items again 
    public function order_items(Request $request , $id) {
        $order_items = ApiOrder::with('apiOrderItem' , 'apiOrderItem.product' , 'apiOrderItem.product.options')->where('id' , $id)->first();
        return response()->json([
            'status' => 'success',
            'order_items' => $order_items
        ],200);  
    }

    //buy again items to cart and checkout

    public function buy_again_order_items(Request $request)
    {
        if (Auth::id() !== null) {
            $userId = Auth::id();
            $contact = Contact::where('user_id', $userId)->first();
            if (!empty($contact)) {
                if ($contact->is_parent == 1) {
                    $main_contact_id = $contact->contact_id;
                    $free_postal_state = $contact->state == 'California' || $contact->state == 'CA' ? true : false;
                } else {
                    $main_contact_id = $contact->parent_id;
                    $free_postal_state = $contact->state == 'California' || $contact->state == 'CA' ? true : false;
                }
            } else {
                $main_contact_id = null;
                $free_postal_state = false;
            }
        } else {
            $free_postal_state = true;
        }
        if (!empty($request->ordered_products)) {
            foreach ($request->ordered_products as $orderProducts) {
                $id = $orderProducts['product_id'];
                $option_id = $orderProducts['option_id'];
                $quantity = $orderProducts['quantity'];

                $productOption = ProductOption::where('option_id', $option_id)->with('products.options.price')->first();
                $cart = session()->get('cart');
                if (Auth::id() !== null) {
                    $user_id = Auth::id();
                } else {
                    $user_id = '';
                }

                $contact_id = '';
                $secondary_id = '';

                if ($user_id) {
                    $contact = Contact::where('user_id', $user_id)->first();
                    $contact_id = $contact->contact_id;
                    $secondary_id = $contact->secondary_id;
                }

                if ($contact_id || $secondary_id) {
                    $pricing = $contact->priceColumn;
                }

                $user_price_column = UserHelper::getUserPriceColumn();
                foreach ($productOption->products->options as $option) {
                    foreach ($option->price as $price_get) {
                        // $price = isset($price_get[$user_price_column]) ? $price_get[$user_price_column] : 0;
                        // if ($price == 0) {
                        //     $price = $price_get['sacramentoUSD'];
                        // }
        
                        // if ($price == 0) {
                        //     $price = $price_get['retailUSD'];
                        // }

                        if (!empty($price_get[$user_price_column]) && $price_get[$user_price_column] != '0') {
                            $price = $price_get[$user_price_column];
                        } elseif (!empty($price_get['sacramentoUSD']) && $price_get['sacramentoUSD'] != '0') {
                            $price = $price_get['sacramentoUSD'];
                        } elseif (!empty($price_get['retailUSD']) && $price_get['retailUSD'] != '0') {
                            $price = $price_get['retailUSD'];
                        }
                    }
                }

                if (isset($cart[$id])) {
                    $hash_cart = session()->get('cart_hash');
                    $product_in_active_cart = Cart::where('qoute_id', $id)->first();
                    if ($product_in_active_cart) {
                        $current_quantity = $product_in_active_cart->quantity;
                        $product_in_active_cart->quantity = $current_quantity + $quantity;
                        $product_in_active_cart->save();
                    }
                    $cart[$id]['quantity'] += $quantity;
                } else {

                    $hash_cart = $request->session()->get('cart_hash');
                    $cart_hash_exist = session()->has('cart_hash');


                    if ($cart_hash_exist == false) {
                        $request->session()->put('cart_hash', Str::random(10));
                    }

                    $cart[$id] = [
                        "product_id" => $productOption->products->product_id,
                        "name" => $productOption->products->name,
                        "quantity" => $quantity,
                        "price" => $price,
                        "code" => $productOption->code,
                        "image" => $productOption->image,
                        'option_id' => $productOption->option_id,
                        "slug" => $productOption->products->slug,
                        "cart_hash" => session()->get('cart_hash')
                    ];
                    $cart[$id]['user_id'] = $user_id;
                    $cart[$id]['is_active'] = 1;
                    $cart[$id]['qoute_id'] = $id;

                    $qoute = Cart::create($cart[$id]);
                }

                $request->session()->put('cart', $cart);
                $cart_items = session()->get('cart');
            }
            return response()->json([
                'status' => 'success',
                'cart_items' => $cart_items,
                'cart' => $cart,
                'main_contact_id' => $main_contact_id,
                'free_postal_state' => $free_postal_state
            ]);
        }
    }

    // request bulk quantity 
    public function bulk_products_request(Request $request) {
        try {
            // Validate incoming request data
            $validatedData = $request->validate([
                'items_list' => 'required',
                'quantity' => 'required|numeric',
                'phone_number' => 'required',
                'email' => 'required|email',
                'name' => 'required|string',
                'delievery' => 'required|string',
            ]);

            $bulkQuantity = new BulkQuantityDiscount();
            $bulkQuantity->items_list = $validatedData['items_list']; // Save the raw list
            $bulkQuantity->quantity = $validatedData['quantity'];
            $bulkQuantity->phone_number = $validatedData['phone_number'];
            $bulkQuantity->email = $validatedData['email'];
            $bulkQuantity->name = $validatedData['name'];
            $bulkQuantity->delievery = $validatedData['delievery'];
            $bulkQuantity->save();

            // $admin_users = DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');

            // $admin_users = $admin_users->toArray();

            // $adminsEmails = User::whereIn('id', $admin_users)->pluck('email')->toArray();
            // if (!empty($adminsEmails)) {
            //     foreach ($adminsEmails as $adminEmail) {
            //         MailHelper::send_discount_mail_request('emails.admin_bulk_request', [
            //             'from' => SettingHelper::getSetting('noreply_email_address'),
            //             'email' => $adminEmail,
            //             'subject' => 'New Bulk Products Request Received',
            //             'data' => $validatedData,
            //         ]);
            //     }
            // }
    
            // // Send confirmation email to user
            // MailHelper::send_discount_mail_request('emails.user_bulk_request', [
            //     'from' => SettingHelper::getSetting('noreply_email_address'),
            //     'email' => $validatedData['email'],
            //     'subject' => 'Bulk Products Request Confirmation',
            //     'data' => $validatedData,
            // ]);


            $subdomain = env('ZENDESK_SUBDOMAIN'); 
            $username = env('ZENDESK_USERNAME'); 
            $token =  env('ZENDESK_TOKEN'); 
            $auth = [
                'token' => $token, 
            ];
            
            $client = new ZendeskClient($subdomain);
            $client->setAuth('basic', ['username' => $username, 'token' => $token]);

            $subject = 'New Bulk Products Request Received';
            $description = "Items: " . $validatedData['items_list'] . "\nQuantity: " . $validatedData['quantity'] . "\nPhone Number: " . $validatedData['phone_number'] . "\nDelivery: " . $validatedData['delievery'];
            $requesterName = $validatedData['name'];
            $requesterEmail = $validatedData['email'];

            $ticketData = [
                'subject' => $subject,
                'description' => $description,
                'requester' => [
                    'email' => $requesterEmail,
                    'name' => $requesterName,
                ],
            ];


            $response = $client->tickets()->create($ticketData);
            return response()->json(['message' => 'Your request has been recieved , We will get back to you soon!'], 200);
        } 
        catch (\Exception $e) {
            // Log the error
            Log::error('Error submitting bulk product request: ' . $e->getMessage());
            return response()->json(['message' => 'Error submitting bulk product request'], 500);
        }
    }

    // ai answer 
    public function ai_answer(Request $request)
    {
        $tempature = 1;
        $ai_tempature = AdminSetting::where('option_name', 'ai_tempature')->first();
        if (!empty($ai_tempature) && !empty($ai_tempature->option_value)) {
            $tempature = $ai_tempature->option_value;
        } else {
            $tempature = 1;
        }

        $gpt_model = 'gpt-4o';
        $gpt_model_option_name = AdminSetting::where('option_name', 'enable_gpt-3.5-turbo')->first();
        if (!empty($gpt_model_option_name) && ($gpt_model_option_name->option_value == 'Yes')) {
            $gpt_model = 'gpt-3.5-turbo';
        } else {
            $gpt_model = 'gpt-4o';
        }

        $top_p = 1;
        $ai_top_p = AdminSetting::where('option_name', 'ai_top_p')->first();
        if (!empty($ai_top_p) && !empty($ai_top_p->option_value)) {
            $top_p = $ai_top_p->option_value;
        } else {
            $top_p = 1;
        }

        $max_tokens = 4096;
        $ai_max_tokens = AdminSetting::where('option_name', 'ai_max_tokens')->first();
        if (!empty($ai_max_tokens) && !empty($ai_max_tokens->option_value)) {
            $max_tokens = $ai_max_tokens->option_value;
        } else {
            $max_tokens = 4096;
        }
        
        $prompt_format_text = 'Please provide a concise and structured description relevent to the product using HTML tags where necessary. Keep the response within a reasonable length and formatted. The content should look as it is created in ckeditor. Add bullet points, headings, and other formatting elements as needed. Do not mention about the ckeditor or any other editor.Do not add any kind of suport email , phone number of websites other then https://indoorsunhydro.com .';
        $ai_prompt_text = AdminSetting::where('option_name', 'ai_prompt_text')->first();
        if (!empty($ai_prompt_text) && !empty($ai_prompt_text->option_value)) {
            $prompt_format = $ai_prompt_text->option_value;
        } else {
            $prompt_format = $prompt_format_text;
        }
       
        $apiKey = config('services.ai.ai_key');
        $product_name = $request->product_name;
        $question = $request->question;
        
        $main_product = Product::where('name', $product_name)->first();
        $productDescription =  !empty($main_product) ? $main_product->description : $main_product->code;

        // Check if the question is relevant to the product
        if (!$this->isQuestionRelatedToProduct($question .' '.$product_name, $productDescription)) {
            return response()->json([
                'message' => 'I apologize, but I do not feel comfortable providing information about products that are unrelated to the question you asked. Please feel free to ask about any items you may be interested in purchasing, and I will do my best to assist you.',
                'status' => 'error'
            ], 200);
        }

        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ],
        ]);

        $response = $client->post('chat/completions', [
            'json' => [
                'model'=> $gpt_model,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $question .' '.$product_name . ' ' . $prompt_format],
                ],
                'max_tokens' => intval($max_tokens),
                'temperature' => intval($tempature),
                'top_p' => floatval($top_p),
            ],
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        if (!isset($body['choices'][0]['message']['content'])) {
            return response()->json([
                'message' => 'No answer found',
                'status' => 'error'
            ], 404);
        } else {
            // $content = $body['choices'][0]['message']['content'];
            
            // // Optionally decode HTML entities if needed
            // $decodedContent = htmlspecialchars_decode($content);
        
            // return response()->json([
            //     'message' => $decodedContent,
            //     'status' => 'success'
            // ], 200);

            $decodedContent = $body['choices'][0]['message']['content'];

            // Remove leading and trailing ```html and ```
            $decodedContent = preg_replace('/^```\s*html\s*/', '', $decodedContent); // Remove from the start
            $decodedContent = preg_replace('/\s*```$/', '', $decodedContent); // Remove from the end

            // Optionally, trim any extra whitespace
            $decodedContent = trim($decodedContent);

            // Now return the cleaned-up content
            return response()->json([
                'message' => $decodedContent,
                'status' => 'success'
            ], 200);
        }
    }

    

    private function isQuestionRelatedToProduct(string $question, string $productDescription): bool
    {
        $genericKeywords = [
            'buy', 'price', 'specifications', 'features', 'availability', 'order', 
            'info', 'detail', 'stock', 'summary', 'use', 'helping', 'cost', 
            'discount', 'warranty', 'model', 'brand', 'size', 'color', 'material', 
            'reviews', 'rating', 'quantity', 'shipping', 'delivery', 'return', 
            'how', 'what', 'where', 'when', 'why', 'which', 'who', 'whom','information','instructions','manual','guide','details','specification','specifications','features','feature','price','cost','buy','purchase','order','availability','stock',
            'refund', 'condition', 'guarantee','similar', 'alternate', 'usage','use', 'benefit', 'advantage', 'disadvantage', 'pros', 'cons', 'comparison', 'compare', 'difference', 'similarities', 'differences', 'used' , 'explain'   
        ];

        // List of irrelevant terms to exclude
        $irrelevantTerms = [
            'car', 'truck', 'aeroplane', 'airplane', 'boat', 'motorcycle', 'scooter', 'bicycle', 'bike', 
            'bus', 'train', 'helicopter', 'ship', 'submarine', 'jet', // Vehicles
            'earth', 'moon', 'physics', 'capital', 'country', 'continent', 'equation', 'atom', 'galaxy', // General knowledge
            'algorithm', 'software', 'hardware', 'network', 'protocol', 'database', 'encryption', // Tech terms
            'movie', 'song', 'actor', 'director', 'album', 'game', 'series', 'concert', 'festival', // Entertainment
            'football', 'soccer', 'basketball', 'tennis', 'olympics', 'team', 'player', 'coach', // Sports
             'subtraction', 'multiplication', 'division', 'calculus', 'geometry', 'algebra' // Math
        ];

        $questionWords = array_map('strtolower', explode(' ', $question));

        // if found one word in the that is relevant to the product then return true
        // if (count(array_intersect($questionWords, $genericKeywords)) == 0) {
        //     return false;
        // }


        // Check for any irrelevant terms in the question
        foreach ($questionWords as $word) {
            if (in_array($word, $irrelevantTerms) && !in_array($word, $genericKeywords)) {
                return false; // Immediately return false if any irrelevant term is found
            }
        }

        $keywords = array_map('strtolower', explode(' ', $productDescription));
        
        // Combine product description keywords and generic keywords
        $allKeywords = array_unique(array_merge($keywords, $genericKeywords));

        $relatedKeywordCount = 0;

        foreach ($questionWords as $word) {
            if (in_array($word, $allKeywords)) {
                $relatedKeywordCount++;
            }
        }

        // Check if the question has a significant number of related keywords
        $relatedKeywordRatio = $relatedKeywordCount / count($questionWords);
        return $relatedKeywordRatio > 0.2; // Adjust the threshold as needed
    }


    // get similar products

    public function see_similar_products(Request $request) {
        $product_primary_id = $request->product_id;
        $get_single_product = Product::where('id', $product_primary_id)->first();
        $keywords = explode(' ', $get_single_product->name);
        $option_id = $request->option_id;
        $user_id = Auth::id();
        $primary_contact = null;
        $secondary_contact = null;
        $price_column = null;
        $primary_contact_id = null;
        $default_price_column = AdminSetting::where('option_name', 'default_price_column')->first();
    
        // Determine the price column
        if (!auth()->user()) {
            $price_column = !empty($default_price_column) ? $default_price_column->option_value : 'sacramentoUSD';
        } else {
            $contact = Contact::where('user_id', $user_id)->first();
            if (empty($contact)) {
                $price_column = !empty($default_price_column) ? $default_price_column->option_value : 'sacramentoUSD';
            } else {
                $contact_id_new = $contact->is_parent == 1 ? $contact->contact_id : $contact->parent_id;
                $primary_contact = Contact::where('contact_id', $contact_id_new)->first();
                $price_column = !empty($primary_contact) ? $primary_contact->priceColumn : (!empty($default_price_column) ? $default_price_column->option_value : 'sacramentoUSD');
            }
        }
    
        // Fetch the main product
        $products = Product::with('categories', 'brand')
            ->where('id', $product_primary_id)
            ->where('status', '!=', 'Inactive')
            ->get();
    
        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'No similar product found',
                'status' => 'error'
            ], 404);
        }
    
        // Handle user's buy list
        $lists = BuyList::where('user_id', $user_id)->get();
        $contact = $user_id ? Contact::where('user_id', $user_id)->first() : null;
        $contact_id = session()->get('contact_id');
        $user_list = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->first();
    
        $user_buy_list_options = !empty($user_list) ? ProductBuyList::where('list_id', $user_list->id)->pluck('option_id', 'option_id')->toArray() : [];
    
        // Get category ID
        $get_category_id = Product::with('categories', 'brand')
            ->where('id', $product_primary_id)
            ->whereHas('categories', function ($q) {
                $q->where('is_active', 1);
            })
            ->pluck('category_id')
            ->toArray();
    
        // Get similar products in the same category
        $get_same_category_products_ids = Product::with('categories', 'brand')
            ->where('status', '!=', 'Inactive')
            ->whereIn('category_id', $get_category_id)
            ->where('id', '!=', $product_primary_id)
            ->pluck('product_id')
            ->toArray();
            
        if (count($keywords) > 1) {
            $get_same_category_products_ids = Product::with('categories', 'brand')
            ->where('status', '!=', 'Inactive')
            ->whereIn('category_id', $get_category_id)
            ->where('id', '!=', $product_primary_id)
            ->where(function($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('name', 'like', '%' . $keyword . '%');
                }
            })
            ->pluck('product_id')
            ->toArray();
        }
        // Get in-stock products
        $get_in_stock_products = ProductOption::with('products', 'products.categories', 'products.brand', 'defaultPrice')
            ->where('status', '!=', 'Disabled')
            ->where('stockAvailable', '>', 0)
            ->whereHas('defaultPrice', function ($q) use ($price_column) {
                $q->where($price_column, '!=', null)
                  ->where($price_column, '>', 0);
            })
            ->whereIn('product_id', $get_same_category_products_ids)
            ->get();


    
        if ($get_in_stock_products->isEmpty()) {
            return response()->json([
                'message' => 'No similar product found',
                'status' => 'error'
            ], 404);
        }
    
        // Add to cart and show price logic
        foreach ($get_in_stock_products as $option) {
            $add_to_cart = true;
            $show_price = true;
    
            if (!empty($request->products_to_hide) && in_array($option->option_id, $request->products_to_hide)) {
                if (!auth()->user()) {
                    $add_to_cart = false;
                    $show_price = false;
                } else {
                    $contact = Contact::where('user_id', auth()->user()->id)->first();
                    if (empty($contact)) {
                        $add_to_cart = false;
                        $show_price = false;
                    } else {
                        $contact_id_new = $contact->is_parent == 1 ? $contact->contact_id : $contact->parent_id;
                        $get_main_contact = Contact::where('contact_id', $contact_id_new)->first();
                        if (!empty($get_main_contact) && strtolower($get_main_contact->paymentTerms) == 'pay in advanced') {
                            $add_to_cart = false;
                            $show_price = false;
                        }
                    }
                }
            }
    
            $option->add_to_cart = $add_to_cart;
            $option->show_price = $show_price;
        }
        
        return response()->json([
            'message' => 'Products found',
            'status' => 'success',
            'products' => $get_in_stock_products,
            'user_buy_list_options' => $user_buy_list_options,
            'contact_id' => $contact_id,
            'price_column' => $price_column ?? 'sacramentoUSD',
        ], 200);
    }
    
}
