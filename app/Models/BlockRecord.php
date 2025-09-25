<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'ip_address',
        'reason',
        'blocked_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
