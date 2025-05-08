<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiRateLimitAlert extends Model
{
    use HasFactory;

    protected $table = 'api_rate_limit_alerts';
    
    protected $fillable = [
        'api_name',
        'api_key',
        'api_secret',
        'email_sent',
        'alert_sent_at',
    ];

}
