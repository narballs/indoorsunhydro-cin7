<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsState extends Model
{
    use HasFactory;
    protected $fillable = [
        'state_code',
        'state_name',
    ];

    public function city()
    {
        return $this->hasMany('App\Models\UsCity', 'state_id', 'id');
    }
}
