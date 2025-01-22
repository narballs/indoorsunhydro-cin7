<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKeys extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'password',
        'threshold',
        'request_count',
        'is_active',
    ];

    public function api_endpoint_requests()
    {
        return $this->hasMany(ApiEndpointRequest::class, 'api_key_id');
    }

    public function api_event_logs()
    {
        return $this->hasMany(ApiEventLog::class, 'api_key_id');
    }
}
