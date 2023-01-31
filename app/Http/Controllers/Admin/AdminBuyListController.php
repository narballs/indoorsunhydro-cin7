<?php

namespace App\Http\Controllers\Admin;

use \App\Http\Requests\BuyLists\BuyListRequest;
use \App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\BuyList;
use App\Models\ProductBuyList;
use Redirect;
use Illuminate\Support\Facades\Auth;

class AdminBuyListController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware(['role:Admin']);
    // }

    public function index(Request $request)
    {
        $buylists = BuyList::all();
        return view('admin/buy-lists', compact('buylists'));
    }

    public function create(Request $request)
    {
        if ($request->id) {
            $list = BuyList::where('id', $request->id)->with('list_products.product.options')->first();
            $products = Product::paginate(10);
            return view('admin/buy-list-new', compact('products', 'list'));
        } else {
            $products = Product::paginate(10);
            $list = '';
            return view('admin/buy-list-new', compact('products', 'list'));
        }
    }

    public function store(BuyListRequest $request)
    {
        $user_id = Auth::id();
        $list = BuyList::create([
            'title' => $request->title,
            'status' => $request->status,
            'description' => $request->description,
            'type' => $request->type,
            'user_id' =>  $user_id
        ]);
        return response()->json([
            'success' => 'List Created Successfully. Please add prodcuts to list.',
            'list_id' => $list->id
        ]);
    }

    public function show($id)
    {
        $list = BuyList::where('id', $id)->with('list_products.product.options')->first();
        //dd($list);
        return view('admin/buy_list/list-detail', compact(
            'list'
        ));
    }

    public function edit($id)
    {
        $list = BuyList::where('id', $id)->with('list_products.product.options')->first();
        return view('admin/buy_list/buy-list-edit', compact(
            'list'
        ));
    }

    public function destroy($id)
    {
        BuyList::where('id', $id)->delete();
        return Redirect::back()->withErrors(['msg' => 'The Message']);
    }

    public function addToList(Request $request)
    {
        $product = Product::with('options')->where('product_id', $request->product_id)->first();
        //dd($product);
        $option_id = Product::find($request->product_id);

        return view('admin/buy_list/list_row', compact(
            'product'
        ));
    }

    public function genrateList(Request $request)
    {
        $list_id = $request->listId;
        $list_items = $request->listItems;

        $is_update = $request->is_update;
        $quantity = $request->quantity;
        if ($is_update) {
            $product_buy_list = ProductBuyList::where('list_id', $list_id)->delete();
            foreach ($list_items as $list_item) {
                $product_buy_list = new ProductBuyList();
                $product_buy_list->list_id = $list_id;
                $product_buy_list->product_id = $list_item['product_id'];
                $product_buy_list->option_id = $list_item['option_id'];
                $product_buy_list->quantity = $list_item['quantity'];
                $product_buy_list->sub_total = $list_item['subtotal'];
                $product_buy_list->grand_total = $list_item['grand_total'];
                $product_buy_list->save();
            }
        } else {
            foreach ($list_items as $list_item) {
                $product_buy_list = new ProductBuyList();
                $product_buy_list->list_id = $list_id;
                $product_buy_list->product_id = $list_item['product_id'];
                $product_buy_list->option_id = $list_item['option_id'];
                $product_buy_list->quantity = $list_item['quantity'];
                $product_buy_list->sub_total = $list_item['subtotal'];
                $product_buy_list->grand_total = $list_item['grand_total'];
                $product_buy_list->save();
            }
        }
    }
}