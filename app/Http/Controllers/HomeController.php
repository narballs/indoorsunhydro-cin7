<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Session;


class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // session::forget('companies');
        //  session::forget('cart');
        //  exit;
        $categories = Category::orderBy('name', 'ASC')
            ->with('products')->where('is_active', 1)
            ->get();
        return view('index', compact('categories'));
    }
}
