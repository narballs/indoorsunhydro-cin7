<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropShipped extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'description'
    ];


    public function order() {
        return $this->belongsTo(ApiOrder::class);
    }
}
