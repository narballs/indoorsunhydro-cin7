<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleAdsData extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'clicks',
        'impressions',
        'spend',
        'google_ads_id',
    ];
}
