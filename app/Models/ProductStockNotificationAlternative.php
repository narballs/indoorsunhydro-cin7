<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockNotificationAlternative extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'product_stock_notification_id'];

    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id' , 'product_id');
    }

    public function productStockNotification()
    {
        return $this->belongsTo(ProductStockNotification::class , 'product_stock_notification_id' , 'id');
    }

}
