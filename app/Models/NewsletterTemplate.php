<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'content',
    ];

    public function subscribers()
    {
        return $this->belongsToMany(NewsletterSubscription::class, 'newsletter_subscriber_template', 'newsletter_subscription_id', 'newsletter_template_id');
    }
}
