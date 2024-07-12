<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumberList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tags',
        'url',
        'description',
        'status'
    ];
}
