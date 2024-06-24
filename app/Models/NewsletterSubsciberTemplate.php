<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubsciberTemplate extends Model
{
    use HasFactory;
    protected $table = 'newsletter_subscriber_template';
    protected $fillable = ['newsletter_subscription_id', 'newsletter_template_id'];

    public function subscriber()
    {
        return $this->belongsTo(NewsletterSubscription::class, 'newsletter_subscription_id');
    }

    public function template()
    {
        return $this->belongsTo(NewsletterTemplate::class, 'newsletter_template_id');
    }
}
