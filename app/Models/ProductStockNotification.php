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


    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id' , 'id');
    }

    public function productStockNotificationAlternatives()
    {
        return $this->hasMany(ProductStockNotificationAlternative::class);
    }
}
