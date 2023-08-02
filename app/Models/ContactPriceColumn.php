<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPriceColumn extends Model
{
    protected $fillable = [
        'site_id', 
        'price_column'
    ];
}
