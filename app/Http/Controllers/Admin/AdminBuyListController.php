<?php

namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\BuyList;
use App\Models\ProductBuyList;



class AdminBuyListController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    
    public function index(Request $request) {
        // $products_in_list = ProductBuyList::with('product.options')->where('list_id', 1)->get();
        // dd($products_in_list);
        $buylists = BuyList::all();
         return view('admin/buy-lists', compact('buylists'));
    }

    public function create(Request $request) {
        $products = Product::paginate(10);
        return view('admin/buy-list-new', compact('products'));
    }

    public function store(Request $request) {
        $list = BuyList::create([
            'title' => $request->title,
            'status' => $request->status, 
            'description' => $request->description
        ]);
        return response()->json([
            'success' => 'List Created Successfully',
            'list_id' => $list->id
        ]);

    }

    public function show($id) {
        $list = BuyList::where('id', $id)->with('list_products.product.options')->first();
       return view('admin/buy_list/list-detail', compact(
            'list'));
    }

    public function addToList(Request $request) {
        $product = Product::with('options')->where('product_id', $request->product_id)->first();
        //dd($product);
        $option_id = Product::find($request->product_id);

        return view('admin/buy_list/list_row', compact(
            'product'
        ));
    }

    public function genrateList(Request $request) {
        $list_id = $request->listId;
        $list_items = $request->listItems;
        $quantity = $request->quantity;
        foreach ($list_items as $list_item ) {
            $product_buy_list = new ProductBuyList();
            $product_buy_list->list_id = $list_id;
            $product_buy_list->product_id = $list_item['product_id'];
            $product_buy_list->option_id = $list_item['option_id'];
            $product_buy_list->quantity = $list_item['quantity'];
            $product_buy_list->save();
        }
    }
}