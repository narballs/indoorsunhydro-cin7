<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyApiLog extends Model
{
    protected $fillable = [
        'date',
        'api_endpoint',
        'count',
    ];

}
