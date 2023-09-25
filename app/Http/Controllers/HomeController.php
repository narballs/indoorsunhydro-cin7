<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use Session;
use Illuminate\Http\Request;


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
        $pages = Page::where('status', 1)->get();
        return view('index', compact('categories'));
    }

    public function show_page($slug) {
        $page = Page::where('slug' , $slug)->first();
        $pages = Page::where('status', 1)->get();
        return view('partials.show_page', compact('page' , 'pages'));
    }

    
}
