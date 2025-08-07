<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'option_id',
        'request',
        'response',
        'status',
        'time_taken',
        'product_name',
        'sku'
    ];
}
