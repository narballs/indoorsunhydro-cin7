<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_code',
        'carrier_code',
        'carrier_name',
        'service_name',
        'type',
        'status',
    ];

    public function selected_shipping_quote()
    {
        return $this->hasOne(SelectedShippingQuote::class);
    }
}
