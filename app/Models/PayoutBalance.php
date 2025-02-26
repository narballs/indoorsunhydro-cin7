<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'payout_id',
        'payout_balance_id',
        'order_id',
        'customer_name',
        'customer_email',
        'currency',
        'type',
        'description',
        'amount',
        'converted_amount',
        'fees',
        'net',
        'charge_created',
    ];


    public function payout()
    {
        return $this->belongsTo(Payout::class);
    }
}
