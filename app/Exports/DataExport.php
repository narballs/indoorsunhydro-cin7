<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use App\Models\Product;

class DataExport implements FromCollection
{
    public function collection()
    {
        $product_array = [];
        $products = Product::with('options','options.defaultPrice', 'product_brand', 'categories' , 'product_views','apiorderItem', 'product_stock')
        ->with(['product_views','apiorderItem' , 'options' => function ($q) {
            $q->where('status', '!=', 'Disabled');
            
        }])
        ->whereHas('options.defaultPrice', function ($q) {
            $q->where('retailUSD', '!=', 0);
        })
        ->whereHas('categories' , function ($q) {
            $q->where('is_active', 1);
        })
        ->where('status' , '!=' , 'Inactive')
        ->where('barcode' , '!=' , '')
        ->get();

        if (count($products) > 0) {
            foreach ($products as $product) {
                if (count($product->options) > 0) {
                    foreach ($product->options as $option) {
                        $category = 'General > General';
                        if (!empty($product->categories)) {
                            if (!empty($product->categories->category_id) && $product->categories->parent_id == 0) {
                                $category = $product->categories->category_id;
                                $new_category = !empty($product->categories->category_id) ? $product->categories->name : 'General > General';
                            } else if (!empty($product->categories->parent_id) && !empty($product->categories->category_id) && $product->categories->parent_id != 0) {
                                $new_category = !empty($product->categories->category_id) ? $product->categories->name : 'General > General';
                            }
                        }
                        else {
                            $category = 'General > General';
                        } 
                        $product_array[] = [
                            'id' => $product->id,
                            'title' => $product->name,
                            'code' => $product->code,
                            'description' => !empty($product->description) ? strip_tags($product->description) : 'No description available',
                            'link' => url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug),
                            'image_link' => !empty($product->images) ? $product->images : url(asset('theme/img/image_not_available.png')),
                            'price' => !empty($option->price[0]->retailUSD) ? $option->price[0]->retailUSD : 0,
                            'condition' => 'new',
                            'availability' => 'In stock',
                            'brand' => !empty($product->product_brand->name) ? $product->product_brand->name : 'General brand',
                            'barcode' => $product->barcode,
                            'google_product_category' => $new_category,
                        ];
                    }
                    
                }
                
            }
            return new Collection($product_array);
        }
    }
}