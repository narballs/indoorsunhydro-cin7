<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderComment extends Model
{
    use HasFactory;
    protected $fillable = ["order_id","comment","created_at", "updated_at"];

    public function comment()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }
}


