<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyList extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'status',
        'description'
    ];

    public function list_products()
    {
        return $this->hasMany('App\Models\ProducyBuyList', 'id', 'list_id');
    }
}