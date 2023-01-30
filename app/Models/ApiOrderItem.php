<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ApiOrderItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ["id", "order_id", "product_id", "quantity", "price"];

    // public function product()
    // {
    //     return $this->belongsTo('App\Models\Product', 'product_id', 'product_id');
    // }
    public function order()
    {
        return $this->belongsTo('App\Models\ApiOrder', 'order_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    // public function product()
    //  {
    //  return $this->belongsToMany('App\Models\Product', 'api_order_items', 
    //    'product_id', 'id');
    //  }
}