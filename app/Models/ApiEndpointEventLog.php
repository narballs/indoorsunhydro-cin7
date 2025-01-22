<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiEndpointEventLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_end_point_request_id',
        'description',
    ];

    public function api_end_point_request()
    {
        return $this->belongsTo(ApiEndpointRequest::class, 'api_end_point_request_id');
    }
}
