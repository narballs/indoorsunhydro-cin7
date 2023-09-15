<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDiscount extends Model
{
    use HasFactory;
    protected $table = 'customer_discounts';
    protected $fillable = [
        'contact_id',
        'discount_id',
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
