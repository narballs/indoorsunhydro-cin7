<?php

namespace App\Http\Controllers;

use App\Models\ApiOrderItem;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductView;
use App\Models\BuyList;
use App\Models\ProductBuyList;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
       // session::forget('cart_hash');
       //  session::forget('companies');
       //  session::forget('cart');
       //  $cart = session::get('cart');
       
       //   exit;
        $contact_id = null;
        $contact_id = session()->get('contact_id');
        $categories = Category::orderBy('name', 'ASC')
            ->with('products')->where('is_active', 1)
            ->get();
        $product_views = null;
        $product_views_chunks = null;
        $user_id = Auth::id();
        $user_buy_list_options = [];
        $lists = '';
        $pages = Page::where('status', 1)->get();
        if (auth()->user()) {
            $product_views = ProductView::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as entry_count'))
            ->whereNotNull('user_id')
            ->where('user_id' , $user_id)
            ->orderBy('created_at' , 'DESC')
            ->groupBy('product_id')
            ->get();
            
        } else {
            $product_views = ApiOrderItem::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as entry_count'))
            ->orderBy('created_at' , 'DESC')
            ->groupBy('product_id')
            ->get();
        }
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


        
        return view('index', compact('categories' , 'product_views','lists','user_buy_list_options' , 'contact_id'));
    }

    public function show_page($slug) {
        $page = Page::where('slug' , $slug)->first();
        $pages = Page::where('status', 1)->get();
        return view('partials.show_page', compact('page' , 'pages'));
    }

    
}
