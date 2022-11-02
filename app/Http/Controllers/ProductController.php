<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use Session;
use App\Models\Contact;
use App\Models\Brand;

class ProductController extends Controller
{
    public function showProductByCategory(Request $request, $category_id) {
        $selected_category_id = $request->get('selected_category_id');
        if (!empty($selected_category_id)) {
            //$category_id = $selected_category_id;
        }

        $parent_category = Category::find($category_id);
        $parent_category_slug = $parent_category->slug;
        $categories = Category::where('parent_id',0)->get();
     

        //git switch branch
        $category_ids = Category::where('parent_id', $category_id)->pluck('id')->toArray();
        array_push($category_ids, $category_id);
        $products = [];

        $all_product_ids = Product::whereIn('category_id', $category_ids)->pluck('id')->toArray();
        $brand_ids = Product::whereIn('id', $all_product_ids)->pluck('brand_id')->toArray();

        //echo '<pre>'; var_export($category_ids);
        //echo '<pre>'; var_export($brand_ids); exit;

        $brand_id = $request->get('brand_id');
        

        $stock = $request->get('stock');

        if ($request->get('per_page')) {
            $per_page = $request->get('per_page');
        }
        else {
            $per_page = 10;
        }
        $price_creteria = $request->get('search_price');
 

        if (!empty($category_ids)) {
            $products_query = Product::where('status', '!=' ,'Inactive')
                ->whereIn('category_id', $category_ids)
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
                if ($price_creteria == 'price-low-to-high') {
                    $products_query->orderBy('retail_price', 'ASC');
                }
                if ($price_creteria == 'price-high-to-low') {
                    $products_query->orderBy('retail_price', 'DESC');
                }
            }
            if(empty($stock)) {
               $products_query->where('stockAvailable','>',0); 
            }
            if (!empty($stock && $stock == 'in-stock')) {
                $products_query->where('stockAvailable','>',0);
            }
            if (!empty($stock && $stock == 'out-of-stock')) {
                $products_query->where('stockAvailable','<',1);
            }

            if (!empty($selected_category_id)) {
                //echo $selected_category_id; exit;
                //dd($selected_category_id);

                //$sub_category_ids = Category::where('parent_id', $selected_category_id)->pluck('id');
                $sub_category_ids = Category::where('parent_id', $selected_category_id)->pluck('id')->toArray();

                //echo '<pre>'; var_export($sub_category_ids); exit;

                //$products_query->whereIn('category_id', $sub_category_ids);
            }
           // echo '<pre>'; var_dump($products_query);'<pre>';exit;
             //dd($selected_category_id);
            $products = $products_query->with('options', 'brand')->paginate($per_page);

        }

        $brands = Brand::whereIn('id', $brand_ids)->pluck('name', 'id')->toArray();

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
            'selected_category_id'

        ));
    }

    public function showAllProducts(Request $request) {

        $products = Product::with('options')->get();
        $all_product_ids = Product::pluck('id')->toArray();
        //dd($all_product_ids);
        $brand_ids = Product::whereIn('id', $all_product_ids)->pluck('brand_id')->toArray();
        //dd($brand_ids);

        $brand_id = $request->get('brand_id');
        $stock = $request->get('stock');
        if ($request->get('per_page')) {
            $per_page = $request->get('per_page');
        }
        else {
            $per_page = 10;
        }
        $price_creteria = $request->get('search_price');
 

       
            $products_query = Product::where('status', '!=' ,'Inactive')
                ->with('options', 'brand');    
            //dd($products_query);

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
                if ($price_creteria == 'price-low-to-high') {
                    $products_query->orderBy('retail_price', 'ASC');
                }
                if ($price_creteria == 'price-high-to-low') {
                    $products_query->orderBy('retail_price', 'DESC');
                }
            }
            if(empty($stock)) {
               $products_query->where('stockAvailable','>',0); 
            }
            if (!empty($stock && $stock == 'in-stock')) {
                $products_query->where('stockAvailable','>',0);
            }
            if (!empty($stock && $stock == 'out-of-stock')) {
                $products_query->where('stockAvailable','<',1);
            }
            $products = $products_query->with('options', 'brand')->paginate($per_page);

   

        $brands = Brand::whereIn('id', $brand_ids)->pluck('name', 'id')->toArray();

        return view('all_products', compact(
            'products',
            'brands', 
            'brand_id',
            'per_page',
            'price_creteria',
            'stock'
            )
        );
    }

    public function showProductDetail($id, $option_id) {
        $productOption = ProductOption::where('option_id', $option_id)->with('products.categories')->first();
        $category = Category::where('category_id', $productOption->products->categories->category_id)->first();
        $parent_category = Category::where('category_id', $category->parent_id)->first();
        if ($parent_category) {
            $pname =  $parent_category->name;
        }
        else {
            $pname = '';
        }
        return view('product-detail', compact('productOption', 'pname'));

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
        // $products = Product::where('brand', $name)->where('status', 'Public')->get(); 
        $products = Product::where('brand', $name)->with('options')->get();
        return view('products-by-brand', compact('products'));
    }

    public function addToCart(Request $request)
    {
        // dd($request->all());
        $id = $request->p_id;
        $option_id = $request->option_id;

        //$product = Product::findOrFail($id);
        $productOption = ProductOption::where('option_id', $option_id)->with('products')->first();
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->quantity;
        } 
        else {
            $cart[$id] = [
                "product_id" => $productOption->products->product_id,
                "name" => $productOption->products->name,
                "quantity" => $request->quantity,
                "price" => $productOption->retailPrice,
                "image" => $productOption->image,
                'option_id' => $productOption->option_id
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
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
            }

            $request->session()->put('cart', $cart);

            session()->flash('success', 'Product removed successfully');
        }
        return redirect()->back()->with('success', 'Product removed successfully!');
    }

    public function cart(Request $request)
    {
        $cart_items = $request->session()->get('cart');
        $user_id = auth()->id();
        $contact = [];
        if (!empty($user_id)) {
            $contact = Contact::where('user_id', $user_id)->first();     
        }
        
        if (!empty($cart_items)) {

            $view = 'cart';
        }
        else {
            $view = 'empty-cart';
        }

        return view($view, compact(
            'cart_items', 
            'contact'
        ));
    }

    public function updateCart(Request $request)
    {

        $items = $request->post('items_quantity');

        //echo '<pre>'; var_export($items);


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

    public function productSearch(Request $request) {
        if ($request->ajax()) {
            $brand = $request->input('brand');
            $price = $request->input('price');
            $instock = $request->input('instock');

            //$perpage = $request->input('perPage');
            $products = Product::with(['options' => function ($q) use($price, $instock)  {
                $q->where('status', '!=', 'Disabled')->where('retailPrice', '>=', $price)->where('stockAvailable', '>', 0);
            }])->where('status', '!=', 'Inactive')->where('brand_id', $brand)->paginate(10);
            $count = 0;
            return view('search_product.filters', compact('products', 'count', 'brand'))->render();
        }

        //$products = Product::orderBy("retail_price", "asc")->get();
   
        $products = Product::with(['options' => function ($q) {
            $q->where('status', '!=', 'Disabled');
        }])->where('status', '!=', 'Inactive')
            ->where('name', 'LIKE', '%' . $request->value . '%')
            ->orWhere('code', 'LIKE', '%' . $request->value . '%')->get();

            //   $products = Product::with('options')->where('status', '!=', 'Inactive')
            // ->where('name', 'LIKE', '%' . $request->value . '%')
            // ->orWhere('code', 'LIKE', '%' . $request->value . '%')->get();
            // //dd($products);

               //  $products = Product::where('status', '!=', 'Inactive' )->with('options')->where('name', 'LIKE', '%' . $request->value . '%')
               //  ->orWhere('code', 'LIKE', '%' . $request->value . '%')->get();
               //dd( $products);
        return view('search_product.search_product', compact('products'));
    }

}


