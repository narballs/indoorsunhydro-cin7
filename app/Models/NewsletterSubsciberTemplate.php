<?php

namespace App\Models;

use App\Mail\Subscribe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubsciberTemplate extends Model
{
    use HasFactory;
    protected $table = 'newsletter_subscriber_template';
    protected $fillable = ['list_id', 'newsletter_template_id'];

    public function subscriber_email_list()
    {
        return $this->belongsTo(SubscriberList::class, 'list_id');
    }

    public function template()
    {
        return $this->belongsTo(NewsletterTemplate::class, 'newsletter_template_id');
    }
}
