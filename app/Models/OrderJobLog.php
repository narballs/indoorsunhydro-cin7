<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderJobLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'api_order_id',
        'reference',
        'attempt_number',
        'message',
        'logged_at',
    ];


    public function apiOrder()
    {
        return $this->belongsTo(ApiOrder::class, 'api_order_id');
    }
}
