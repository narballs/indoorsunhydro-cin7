<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBuyList extends Model
{
    use HasFactory;
    protected $fillable = [
        'list_id',
        'product_id',
        'option_id',
        'sub_total',
        'quantity'
    ];


    public function buylist()
    {
        return $this->belongsTo('App\Models\BuyList', 'list_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}