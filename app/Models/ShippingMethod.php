<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;
    protected $fillable = ["title","cost"];
    public function state()
    {
        return $this->belongsTo('App\Models\ShippingState','method_id');
    }
}
