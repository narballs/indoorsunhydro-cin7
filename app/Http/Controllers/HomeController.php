<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductView;
use Session;
use Illuminate\Http\Request;
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
        $categories = Category::orderBy('name', 'ASC')
            ->with('products')->where('is_active', 1)
            ->get();
        $product_views = null;
        $product_views_chunks = null;
        $pages = Page::where('status', 1)->get();
        if (auth()->user()) {
            $product_views = ProductView::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as entry_count'))
            ->whereNotNull('user_id')
            ->where('user_id' , auth()->id())
            ->orderBy('entry_count' , 'DESC')
            ->groupBy('product_id')
            ->get();
            
        } else {
            $product_views = null;
        }
        if (!empty($product_views) && count($product_views) > 0 ) {
            $product_views_chunks = $product_views->chunk(4);
            $product_views_chunks->toArray();
        }
        return view('index', compact('categories' , 'product_views_chunks'));
    }

    public function show_page($slug) {
        $page = Page::where('slug' , $slug)->first();
        $pages = Page::where('status', 1)->get();
        return view('partials.show_page', compact('page' , 'pages'));
    }

    
}
