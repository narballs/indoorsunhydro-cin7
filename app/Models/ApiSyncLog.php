<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiSyncLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'endpoint',
        'desription',
        'last_synced'
    ];
}
