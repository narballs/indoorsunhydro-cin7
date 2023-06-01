<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\ProductOption;
use App\Models\Pricingnew;
use Session;
use Str;
use App\Models\Contact;
use App\Models\Brand;
use App\Models\BuyList;
use App\Models\ProductBuyList;
use App\Models\TaxClass;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function showProductByCategory(Request $request, $category_id)
    {

        $selected_category_id = $request->get('selected_category_id');
        if (!empty($selected_category_id)) {
        }
        $parent_category = Category::find($category_id);
        $parent_category_slug = $parent_category->slug;
        $categories = Category::where('parent_id', 0)->get();
        //git switch branch
        $category_ids = Category::where('parent_id', $category_id)->pluck('id')->toArray();
        array_push($category_ids, $category_id);
        $products = [];

        $all_product_ids = Product::whereIn('category_id', $category_ids)->pluck('id')->toArray();
        $brand_ids = Product::whereIn('id', $all_product_ids)->pluck('brand_id')->toArray();

        $childerens  = Category::where('parent_id', $category_id)->get();

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

            $products = $products_query->with('options.price', 'brand')->paginate($per_page);

            $queries = DB::getQueryLog();

            //echo '<pre>'; var_export($queries); echo '</pre>';
        }

        $brands = Brand::whereIn('id', $brand_ids)->pluck('name', 'id')->toArray();
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
            'pricing',
            'user_buy_list_options'
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

        $products_query  = Product::with('options', 'brand', 'categories');

        $selected_category_id = $request->get('selected_category');

        $childerens = Category::where('parent_id', $selected_category_id)->get();



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
            $products = $products_query->paginate($per_page);
        } else {
            $products = $products_query->with('options.defaultPrice', 'brand')->paginate($per_page);
        }


        $brands = [];
        if (!empty($brand_ids)) {
            $brands = Brand::whereIn('id', $brand_ids)->pluck('name', 'id')->toArray();
        } elseif (empty($search_queries)) {
            $brands = Brand::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }
        $categories = Category::where('parent_id', 0)->get();
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
            $products = $products_query->with('options.defaultPrice', 'brand')->paginate($per_page);
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
            'user_buy_list_options'
            // 'db_price_column'
        ));
    }

    public function showProductDetail($id, $option_id)
    {
        $product = Product::where('id', $id)->first();
        $location_inventories = [];

        try {
            $url = 'https://api.cin7.com/api/v1/Stock?where=productId=' . $product->product_id . '&productOptionId=' . $option_id;
            $client2 = new \GuzzleHttp\Client();
            $res = $client2->request(
                'GET',
                $url,
                [
                    'auth' => [
                        'IndoorSunHydroUS',
                        'faada8a7a5ef4f90abaabb63e078b5c1'
                    ]

                ]
            );
            $inventory = $res->getBody()->getContents();
            $location_inventories = json_decode($inventory);
        } catch (Exception $ex) {
        }

        if ($product) {
            $views = $product->views;
            $product->views = $views + 1;
            $product->save();
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


        return view('product-detail', compact(
            'productOption',
            'pname',
            'pricing',
            'location_inventories',
        ));
    }
    public function showProductByCategory_slug($slug)
    {
        $category = Category::where('slug', $slug)->first();
        $category_ids = Category::where('parent_id', $category->id)->pluck('id')->toArray();
        $products = [];
        if (!empty($category_ids)) {
            $products = Product::whereIn('category_id', $category_ids)->get();
        }
        return view('categories', compact('products'));
    }

    public function showProductByBrands(Request $request, $name)
    {
        if ($request->get('per_page')) {
            $per_page = $request->get('per_page');
        } else {
            $per_page = 10;
        }

        $search_queries = $request->all();

        $products_query  = Product::with('options.price', 'brand')->where('brand', $name);

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
            $products = $products_query->paginate($per_page);
        } else {
            $products = $products_query->with('options.price', 'brand')->paginate($per_page);
        }
        $brands = [];
        if (!empty($brand_ids)) {
            $brands = Brand::whereIn('id', $brand_ids)->pluck('name', 'id')->toArray();
        } elseif (empty($search_queries)) {
            $brands = Brand::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }
        $categories = Category::where('parent_id', 0)->get();
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
                'pricing'
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

        if (!empty($user_id) && !empty($contact_id || $secondary_id)) {
            foreach ($productOption->products->options as $option) {
                foreach ($option->price as $price) {

                    if ($pricing == 'RetailUSD') {

                        $price = $price['retailUSD'];
                    } elseif ($pricing == 'WholesaleUSD') {
                        $price = $price['wholesaleUSD'];
                    } elseif ($pricing == 'TerraInternUSD') {
                        $price = $price['terraInternUSD'];
                    } elseif ($pricing == 'SacramentoUSD') {
                        $price = $price['sacramentoUSD'];
                    } elseif ($pricing == 'OklahomaUSD') {
                        $price = $price['oklahomaUSD'];
                    } elseif ($pricing == 'CalaverasUSD') {
                        $price = $price['calaverasUSD'];
                    } elseif ($pricing == 'Tier1USD') {
                        $price = $price['tier1USD'];
                    } elseif ($pricing == 'Tier2USD') {
                        $price = $price['tier2USD'];
                    } elseif ($pricing == 'Tier3USD') {
                        $price = $price['tier3USD'];
                    } elseif ($pricing == 'ComercialOkUSD') {
                        $price = $price['commercialOKUSD'];
                    } elseif ($pricing == 'CostUSD') {
                        $price = $price['costUSD'];
                    } else {
                        $price = $price['retailUSD'];
                    }
                }
            }
        } else {
            foreach ($productOption->products->options as $option) {
                foreach ($option->price as $price) {
                    $price = $price['retailUSD'];
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
        $cart_items = $request->session()->get('cart');
        //dd($cart_items);
        $user_id = auth()->id();
        $contact = [];
        if (!empty($user_id)) {
            $contact = Contact::where('user_id', $user_id)->first();
        }
        $tax_class = TaxClass::where('is_default', 1)->first();


        if (!empty($cart_items)) {
            $view = 'cart';
        } else {
            $view = 'empty-cart';
        }
        return view($view, compact(
            'cart_items',
            'contact',
            'tax_class'
        ));
    }

    public function updateCart(Request $request)
    {

        // $items = $request->post('items_quantity');

        // $cart_items = session()->get('cart');
        // if (!empty($items)) {
        //     foreach ($items as $item) {
        //         $product_id = $item['id'];


        //         $cart_item = isset($cart_items[$product_id]) ? $cart_items[$product_id] : array();

        //         if (!empty($cart_item) && $cart_item['quantity'] != $item['quantity']) {
        //             Session::put('cart.' . $product_id . '.quantity', $item['quantity']);
        //             $qoute = Cart::where('qoute_id', $product_id)->first();
        //             $qoute->quantity = $item['quantity'];
        //             $qoute->save();
        //         }
        //     }
        // }
        $item = $request->post('items_quantity');
        // dd($request->all());

        $cart_items = session()->get('cart');
        if (!empty($item)) {

            $product_id = $request->post('product_id');


            $cart_item = isset($cart_items[$product_id]) ? $cart_items[$product_id] : array();

            if (!empty($cart_item) && $cart_item['quantity'] != $item) {
                Session::put('cart.' . $product_id . '.quantity', $item);
                $cart_items = session()->get('cart');
                $qoute = Cart::where('qoute_id', $product_id)->first();
                $qoute->quantity = $item;
                $qoute->save();
            }
        }
        $cart_item = session()->get('cart');

        return response()->json([
            'status' => 'success',
            'cart_items' => $cart_items,
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

        //$db_price_column = 'retailUSD';



        $products_query  = Product::with('options', 'brand', 'categories');

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
            $products = $products_query->paginate($per_page);
        } else {
            $products = $products_query->with('options', 'brand')->paginate($per_page);
        }
        $brands = [];
        if (!empty($brand_ids)) {
            $brands = Brand::whereIn('id', $brand_ids)->pluck('name', 'id')->toArray();
        } elseif (empty($search_queries)) {
            $brands = Brand::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }
        $categories = Category::where('parent_id', 0)->get();
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
        //dd($value);
        // $products = Product::with(['options' => function ($q) {
        //     $q->where('status', '!=', 'Disabled');
        // }])->where('status', '!=', 'Inactive')
        //     ->where('name', 'LIKE', '%' . $value . '%')
        //     ->whereRaw('LOWER(`name`) like ?', ['%'.strtolower($value).'%'])
        //     ->orWhere('code', 'LIKE', '%' . $value . '%')
        //     ->paginate($per_page);

        $searchvalue = preg_split('/\s+/', $searchvalue, -1, PREG_SPLIT_NO_EMPTY);
        if (!empty($is_search)) {
            foreach ($searchvalue as $value) {
                $products = Product::with(['options' => function ($q) {
                    $q->where('status', '!=', 'Disabled');
                }])->where('status', '!=', 'Inactive')
                    ->where('name', 'LIKE', '%' . $value . '%')
                    ->orWhere('code', 'LIKE', '%' . $value . '%')->paginate($per_page);
            };
        }

        $searched_value = $request->value;

        $category_id = $selected_category_id;
        $user_id = Auth::id();
        $lists = BuyList::where('user_id', $user_id)->get();
        //$contact = Contact::where('user_id', $user_id)->first();
        $contact = '';
        if ($user_id != null) {
            $contact = Contact::where('user_id', $user_id)->first();
        }
        $user_id = Auth::id();
        $contact = '';
        if ($user_id != null) {
            $contact = Contact::where('user_id', $user_id)->first();
        }
        //$db_price_column = 'retailUSD';
        if ($contact) {
            $pricing = $contact->priceColumn;
        } else {
            $pricing = 'RetailUSD';
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
            'pricing'
            //'db_price_column'
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


        $user_lists = BuyList::where('user_id', $user_id)->where('contact_id', $contact_id)->where('title', 'My Favourites')->exists();
        if ($user_lists == false) {
            $wishlist = new BuyList();
            $wishlist->title = 'My Favourites';
            $wishlist->status = 'Public';
            $wishlist->description = 'My Favourites';
            $wishlist->user_id = $user_id;
            $wishlist->contact_id = $contact_id;
            $wishlist->save();
            $list_id = $wishlist->id;
        } else {
            $list = BuyList::where('title', 'My Favourites')->where('user_id', $user_id)->where('contact_id', $contact_id)->first();
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
            $product_buy_list = new ProductBuyList();
            $product_buy_list->list_id = $list_id;
            $product_buy_list->product_id = $request->product_id;
            $product_buy_list->option_id = $request->option_id;
            $product_buy_list->sub_total = $active_price;
            $product_buy_list->quantity = $request->quantity;
            $product_buy_list->save();
        }

        return response()->json([
            'success' => true,
            'msg' => 'List created Successully !'
        ]);
    }

    public function getWishlists()
    {
        $user_id = Auth::id();
        $contact_id = session()->get('contact_id');
        $lists = BuyList::where('user_id', $user_id)->where('contact_id', $contact_id)->with('list_products.product.options.price')->where('title', 'My Favourites')->get();

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
        $lists = BuyList::where('user_id', $user_id)->where('contact_id', $contact_id)->with('list_products.product.options.price')->where('title', 'My Favourites')->get();

        $product_buy_list_id = $request->product_buy_list_id;
        $delete_favorite = ProductBuyList::find($product_buy_list_id)->delete();
        return response()->json([
            'status' => 'success',
            'lists' => $lists,
            'message' => 'product removed successfully!'
        ], 200);
    }
}
