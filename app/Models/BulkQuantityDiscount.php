<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkQuantityDiscount extends Model
{
    use HasFactory;
    protected $fillable = ['items_list', 'quantity', 'phone_number', 'email', 'name','delievery', 'status'];
}
