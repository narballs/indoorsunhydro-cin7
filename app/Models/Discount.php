<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'mode',
        'discount_code' ,
        'minimum_purchase_requirements',
        'minimum_quantity_items',
        'customer_eligibility',
        'discount_variation',
        'discount_variation_value',
        'minimum_purchase_amount',
        'max_discount_uses',
        'max_usage_count',
        'limit_per_user',
        'usage_count',
        'start_date',
        'end_date',
        'status'
    ];

    public function customerDiscounts()
    {
        return $this->hasMany(CustomerDiscount::class);
    }
    
    public function customerDiscountUses()
    {
        return $this->hasMany(CustomerDiscountUses::class);
    }
}
