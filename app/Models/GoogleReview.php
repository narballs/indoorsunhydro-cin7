<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleReview extends Model
{
    use HasFactory;
    protected $fillable = [
        'author_name',
        'author_url',
        'language',
        'profile_photo_url',
        'rating',
        'relative_time_description',
        'text',
        'review_time',
        'google_review_id',
        'place_id',
        'translated',
    ];

    protected $casts = [
        'review_time' => 'datetime', // Convert to Carbon instance
    ];
    
}
