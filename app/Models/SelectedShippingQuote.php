<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectedShippingQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_quote_id',
    ];

    public function shipping_quote()
    {
        return $this->belongsTo(ShippingQuote::class);
    }
}
