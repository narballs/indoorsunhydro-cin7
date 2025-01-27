<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiEndpointRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_key_id',
        'title',
        'url',
        'request_count',
        'is_active',
    ];

    public function api_key()
    {
        return $this->belongsTo(ApiKeys::class, 'api_key_id');
    }

    public function api_endpoint_event_logs()
    {
        return $this->hasMany(ApiEndpointEventLog::class, 'api_end_point_request_id');
    }
}
