<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    use HasFactory;
    protected $fillable = [
        'option_id',
        'retailUSD',
        'wholesaleUSD',
        'oklahomaUSD',
        'calaverasUSD',
        'tier1USD',
        'tier2USD',
        'tier3USD',
        'tier0USD',
        'commercialOKUSD',
        'costUSD',
        'specialPrice'
    ];
    public function option()
    {
        return $this->belongsTo('App\Models\ProductOption', 'option_id', 'option_id');
    }

}