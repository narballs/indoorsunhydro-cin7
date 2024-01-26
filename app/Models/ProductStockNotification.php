<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',   
        'email',
        'status',
        'is_notified',
    ];
}
