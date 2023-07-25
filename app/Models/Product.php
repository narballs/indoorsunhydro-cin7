<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ["name","slug", "product_id", "status", "description", "code", "images", "category_id","retail_price", "stockAvailable", "brand", "views"];
    public function categories()
    {
        return $this->belongsTo('App\Models\Category','category_id');
    }
    public function getProductsByBrand($brand = [])
    {
        return Product::select('id', 'images', 'brand')
        ->whereIn('brand', $brand)
        ->where('images', '<>', '')
        ->take(50)
        ->get()->toArray();
    }
    // public function getProductsByBrand($brand = [])
    // {
    //     return Product::select('id', 'images', 'brand')
    //     ->whereIn('brand', $brand)
    //     ->where('images', '<>', '')
    //     ->take(50)
    //     ->get()->toArray();
    // }

    public function orderItem()
    {
        return $this->hasMany('App\Models\OrderItem', 'product_id', 'id');
    }

    public function apiorderItem()
    {
        return $this->hasMany('App\Models\ApiOrderItem', 'product_id', 'product_id');
    }

    public function options()
    {
        return $this->hasMany('App\Models\ProductOption', 'product_id', 'product_id');
    }
    public function product_options()
    {
        return $this->hasMany('App\Models\ProductOption', 'product_id', 'product_id');
    }
    public function brand()
    {
        return $this->hasMany('App\Models\Brand', 'id', 'brand_id');
    }

}
