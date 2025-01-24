<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiEventLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_key_id',
        'description',
    ];

    public function api_key()
    {
        return $this->belongsTo(ApiKeys::class, 'api_key_id');
    }
}
