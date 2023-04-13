<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomRole extends Model
{
    protected $fillable = [
        'user_id',
        'role_id',
        'company',
    ];
}
