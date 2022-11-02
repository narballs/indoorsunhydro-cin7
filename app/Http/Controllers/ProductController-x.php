<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Session;
use App\Models\Contact;

class ProductController extends Controller
{
    public function showProductByCategory($id) {
        $category_ids = Category::where('parent_id', $id)->pluck('id')->toArray();
        array_push($category_ids, $id);
        $products = [];
        if (!empty($category_ids)) {
            $products = Product::whereIn('category_id', $category_ids)->get(); 
        }
        return view('categories', compact('products'));
    }

    public function showProductDetail($id) { 
        $product = Product::where('id', $id)->with('categories')->first();
        //dd($product);
        return view('product-detail', compact('product'));

    }
    public function showProductByCategory_slug($slug) {

        $category = Category::where('slug', $slug)->first();
        $category_ids = Category::where('parent_id', $category->id)->pluck('id')->toArray();

        $products = [];
        if (!empty($category_ids)) {
            $products = Product::whereIn('category_id', $category_ids)->get(); 
        }
        return view('categories', compact('products'));
    }
    public function showProductByBrands($name) {
        $products = Product::where('brand', $name)->get(); 
        return view('products-by-brand', compact('products'));
    }

    public function addToCart(Request $request)
    {
        //$request->session()->forget('cart');exit;
        $id = $request->p_id;
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {

            $cart[$id]['quantity'] += $request->quantity;

        } else {

            $cart[$id] = [
                "product_id" => $product->product_id,
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $product->retail_price,
                "image" => $product->images
            ];

        }
        $request->session()->put('cart', $cart);
        $cart_items = session()->get('cart');
        return response()->json([
            'status' => 'success',
            'cart_items' => $cart_items,
        ]);
    }

    public function removeProductByCategory(Request $request)
    {
        //dd($request->id);

        if($request->id) {
            $cart = session()->get('cart');
            //dd($cart[$id]);
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product removed successfully');
        }
          return redirect()->back()->with('success', 'Product removed successfully!');
    }

    public function cart(Request $request)
    {
        $cart_items = $request->session()->get('cart');
        $user_id = auth()->id();
        $contact = Contact::where('user_id', $user_id)->first(); 
        
        if (!empty($cart_items)) {
            $view = 'cart.items';
        }
        else {
            $view = 'cart.empty';
        }

        return view('cart', compact(
            'cart_items', 'contact'
        ));
    }

    public function updateCart(Request $request)
    {

        $items = $request->post('items_quantity');
        $cart_items = session()->get('cart');

        if (!empty($items)) {
            foreach ($items as $item) {
                $product_id = $item['id'];

                $cart_item = isset($cart_items[$product_id]) ? $cart_items[$product_id] : array();

                if (!empty($cart_item) && $cart_item['quantity'] != $item['quantity']) {
                    Session::put('cart.' . $product_id . '.quantity', $item['quantity']);
                }
            }
        }

        $cart_items = session()->get('cart');

        return response()->json([
            'status' => 'success',
            'cart_items' => $cart_items,
        ]);
    }
}


