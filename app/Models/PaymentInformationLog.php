<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInformationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'order_id',
        'method',
        'amount',
        'order_type',
        'order_reference',
        'created_date',
        'payment_date',
        'branch_id',
        'status'
    ];
}
