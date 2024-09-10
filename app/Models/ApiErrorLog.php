<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiErrorLog extends Model
{
    protected $table = 'api_error_logs';
    use HasFactory;
    protected $fillable = [
        'payload',
        'exception'
    ];
}
