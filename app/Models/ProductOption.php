<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;
    protected $fillable = [
        "createdDate",
        "modifiedDate",
        "option_id",
        "product_id",
        "status",
        "code",
        "productOptionSizeCode", 
        "supplierCode",
        "option1", 
        "option2", 
        "option3", 
        "optionWeight",
        "size", 
        "retailPrice",
        "wholesalePrice",
        "vipPrice",
        "specialPrice",
        "specialsStartDate",
        "specialDays",
        "stockAvailable",
        "stockOnHand",
        "image",
        'inventory_update_time'
    ];

    public function products()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'product_id');
    }
    public function price()
    {
        return $this->hasMany('App\Models\Pricingnew', 'option_id', 'option_id');
    }

    public function defaultPrice() {
        return $this->hasOne('App\Models\Pricingnew', 'option_id', 'option_id');
    }
}