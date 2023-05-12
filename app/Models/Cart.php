<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'qoute_id',
        'name',
        'quantity',
        'price',
        'code',
        'image',
        'option_id',
        'slug',
        'user_id',
        'is_active',
        'cart_hash'
    ];
}
