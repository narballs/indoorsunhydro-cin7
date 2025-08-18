<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ["id","name","slug", "product_id", "status", "description", "code",'barcode' ,"images", "category_id","retail_price", "stockAvailable", "brand", "views" , 'length','width','height', 'is_compressed'];
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

    public function ai_image_generation()
    {
        return $this->hasMany('App\Models\AIImageGeneration', 'product_id', 'id');
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
    public function product_views()
    {
        return $this->hasMany('App\Models\ProductView', 'product_id', 'id');
    }
    public function product_stock()
    {
        return $this->hasMany('App\Models\ProductStock', 'product_id', 'id');
    }
    public function product_brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id', 'id');
    }

    public function product_image()
    {
        return $this->hasOne('App\Models\ProductImage', 'product_id', 'id');
    }

}
