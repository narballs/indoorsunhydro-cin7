<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodOption extends Model
{
    use HasFactory;
    protected $fillable = ["title","cost"];
    public function paymentmethod()
    {
        return $this->belongsTo('App\Models\PaymentMethod', 'id', 'method_id');
    }
}
