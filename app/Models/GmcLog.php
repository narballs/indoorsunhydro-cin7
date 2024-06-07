<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmcLog extends Model
{
    use HasFactory;


    protected $table = 'gmc_logs';
    protected $fillable = [
        'last_updated_at',
    ];
}
