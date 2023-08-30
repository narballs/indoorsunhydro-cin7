<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipstationApiLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_url',
        'request',
        'response',
        'status'
    ];
}
