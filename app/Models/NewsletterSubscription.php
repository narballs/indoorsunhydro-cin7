<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'tags'
    ];

    public function templates()
    {
        return $this->belongsToMany(NewsletterTemplate::class, 'newsletter_subscriber_template', 'newsletter_subscription_id', 'newsletter_template_id');
    }
}
