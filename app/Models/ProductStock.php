<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStock extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'available_stock',
        'branch_id',
        'branch_name',
        'product_id',
        'option_id',
    ];
}
