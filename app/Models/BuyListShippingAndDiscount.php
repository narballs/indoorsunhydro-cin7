<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyListShippingAndDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_cost',
        'discount',
        'buylist_id',
        'discount_type'
    ];
    public function buylist()
    {
        return $this->belongsTo(BuyList::class, 'buylist_id', 'id');
    }
}
