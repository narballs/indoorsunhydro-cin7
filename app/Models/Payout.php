<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'payout_id',
        'amount',
        'status',
        'type',
        'method',
        'source_type',
        'currency',
        'destination_name',
        'payout_created',
        'arrive_date',
    ];

    public function payoutBalances()
    {
        return $this->hasMany(PayoutBalance::class);
    }
}
