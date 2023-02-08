<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'city',
        'country',
        'latitude',
        'lanoitude'
    ];

    public function states()
    {
        return $this->hasMany('App\Models\UsState', 'id', 'state_id');
    }
}
