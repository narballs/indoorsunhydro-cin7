<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiSyncLog extends Model
{
    use HasFactory;
    protected $table = 'api_sync_logs';
    protected $fillable = [
        'end_point',
        'description',
        'last_synced'
    ];
}
