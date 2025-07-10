<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    protected $fillable = [
        'order_id',
        'stripe_id',
        'status',
        'amount',
        'partial_refund_amount',
        'currency',
        'customer_email',
        'refund_reason',
        'partial_refund_reason',
        'refund_date',
        'payment_method',
        'transaction_date',
    ];

    protected $dates = ['transaction_date', 'refund_date'];
}
