<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\ProductOption;
use App\Models\Pricingnew;
use Str;
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
use App\Models\ApiOrderItem;
use App\Models\ProductStock;
use Illuminate\Support\Facades\Session;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

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
            ->take(10)
            ->get();
        
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
            'best_selling_products'
            // 'product_buy_list'
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
            ->take(10)
            ->get();

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
            'best_selling_products'
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

        return view('buy_again', compact(
            'products',
            'lists',
            'pricing',
            'user_buy_list_options',
            'contact_id',
        ));
    }

    public function showProductDetail(Request $request ,$id, $option_id)
    {
        
        
        $similar_products = null;
        $product = Product::with('categories' , 'brand')
        ->where('id', $id)
        ->where('status', '!=', 'Inactive')->first();
        if (empty($product)) {
            session()->flash('error', 'This product is not available! Please search another product.');
            return redirect('/products');
        }
        if (!empty($product->category_id) && !empty($product)) {
            $similar_products = $this->getSimilarProducts($request , $id, $option_id);
        }

        $best_selling_products = null;
        $best_selling_products = ApiOrderItem::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as entry_count'))
            ->orderBy('created_at' , 'DESC')
            ->groupBy('product_id')
            ->take(10)
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

            
        // Fetch stock from API
        $stock_updated = UtilHelper::updateProductStock($product, $option_id);

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
            foreach ($product_stocks as $product_stock) {
                $total_stock += $product_stock->available_stock;
            }
        }

        $notify_user_about_product_stock = AdminSetting::where('option_name', 'notify_user_about_product_stock')->first();

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
            'similar_products','total_stock','best_selling_products'
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
        ->take(10)
        ->get();
        
        return view('categories', compact('products',
        'user_buy_list_options',
        'contact_id',
        'lists', 
        'product_views',
        'best_selling_products'
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
        ->take(10)
        ->get();


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
            'best_selling_products' 
            )
        );
    }

    public function addToCart(Request $request)
    {
        $id = $request->p_id;
        $option_id = $request->option_id;

        $productOption = ProductOption::where('option_id', $option_id)->with('products.options.price')->first();
        $cart = session()->get('cart');
        if (Auth::id() !== null) {
            $user_id = Auth::id();
        } else {
            $user_id = '';
        }

        $user_price_column = UserHelper::getUserPriceColumn();
        foreach ($productOption->products->options as $option) {
            foreach ($option->price as $price) {
                $price = isset($price[$user_price_column]) ? $price[$user_price_column] : 0;
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
        return response()->json([
            'status' => 'success',
            'cart_items' => $cart_items,
            'cart' => $cart,
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
            $contact = Contact::where('user_id', $user_id)->first();
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
            'd_none'

        ));
    }

    public function updateCart(Request $request)
    {
        $quantity = $request->post('items_quantity');
        $cart_items = session()->get('cart');

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
        ]);
    }

    public function update_product_cart(Request $request) {
        $id = $request->p_id;
        $option_id = $request->option_id;
        $action = $request->action;
        $subtraction = false;
        if (!empty($action) && $action === 'subtraction') {
            $subtraction = true;
        }
        $productOption = ProductOption::where('option_id', $option_id)->with('products.options.price')->first();
        $cart = session()->get('cart');
        if (Auth::id() !== null) {
            $user_id = Auth::id();
        } else {
            $user_id = '';
        }
        $user_price_column = UserHelper::getUserPriceColumn();
        foreach ($productOption->products->options as $option) {
            foreach ($option->price as $price) {
                $price = isset($price[$user_price_column]) ? $price[$user_price_column] : 0;
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
                    } else {
                        $current_quantity = $product_in_active_cart->quantity;
                        $product_in_active_cart->quantity = $current_quantity - $request->quantity;
                        $product_in_active_cart->save();
                        $cart[$id]['quantity'] -= $request->quantity;
                    }
                }
            } 
            else {
    
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
        }
        else {
            if (isset($cart[$id])) {
                $hash_cart = session()->get('cart_hash');
                $product_in_active_cart = Cart::where('qoute_id', $id)->first();
                if ($product_in_active_cart) {
                    $current_quantity = $product_in_active_cart->quantity;
                    $product_in_active_cart->quantity = $current_quantity + $request->quantity;
                    $product_in_active_cart->save();
                }
                $cart[$id]['quantity'] += $request->quantity;
            } 
            else {
    
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
        }
        
        $request->session()->put('cart', $cart);
        $cart_items = session()->get('cart');
        return response()->json([
            'status' => 'success',
            'cart_items' => $cart_items,
            'cart' => $cart,
        ]);
    }

    public function productSearch(Request $request)
    {
        if ($request->ajax()) {
            $brand = $request->input('brand');
            $price = $request->input('price');
            $stock = $request->input('instock');
            //$perpage = $request->input('perPage');
            $products = Product::with(['options' => function ($q) use ($price, $instock) {
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
            ->where('status', '!=', 'Inactive')
            ->paginate($per_page);
            $products = $main_query;
        } 

        if ($filter_value_main === 'title') {
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
            ->orWhere(function (Builder $query) use ($searchvalue) {
                $query->where('code', 'LIKE', '%' . $searchvalue . '%')
                ->where('status', '!=', 'Inactive');
            })
            ->where('status', '!=', 'Inactive')
            ->paginate($per_page);
            $products = $main_query;
        }


        if ($filter_value_main === 'description') {
            $main_query = Product::with(['product_views','apiorderItem' , 'options' => function ($q) {
                $q->where('status', '!=', 'Disabled');
            }])
            // ->whereHas('options.defaultPrice', function ($q) {
            //     $q->where('retailUSD', '!=', 0);
            // })
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
        ->take(10)
        ->get();
        
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
            'best_selling_products'
        ));
    }


    public function addToWishList(Request $request)
    {
        $user_id = Auth::id();
        //check if user have list already
        $contact_id = session()->get('contact_id');
        $contact =  Contact::where('contact_id', $contact_id)->orWhere('secondary_id', $contact_id)->first();

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
            $contact_pricing = $contact->priceColumn;
        } else {
            $contact_pricing = $contact->priceColumn;
        }
        $prices = Pricingnew::where('option_id', $request->option_id)->pluck($contact_pricing);
        foreach ($prices as $price) {
            $active_price = $price;
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
        if (!empty($request->all_fav)) {
            foreach ($request->all_fav as $multi_favorites) {
                $id = $multi_favorites['product_id'];
                $option_id = $multi_favorites['option_id'];

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
                    foreach ($option->price as $price) {
                        $price = isset($price[$user_price_column]) ? $price[$user_price_column] : 0;
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
            return response()->json([
                'status' => 'success',
                'cart_items' => $cart_items,
                'cart' => $cart,
            ]);
        }
    }

    // order buyed items again 
    public function order_items(Request $request , $id) {
        $order_items = ApiOrder::with('apiOrderItem' , 'apiOrderItem.product')->where('id' , $id)->first();
        return response()->json([
            'status' => 'success',
            'order_items' => $order_items
        ],200);  
    }

    //buy again items to cart and checkout

    public function buy_again_order_items(Request $request)
    {
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
                    foreach ($option->price as $price) {
                        $price = isset($price[$user_price_column]) ? $price[$user_price_column] : 0;
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
            ]);
        }
    }
}
