<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriberList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'description',
        'status',
    ];

    public function subscribers()
    {
        return $this->hasMany(NewsletterSubscription::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }


    public function templates()
    {
        return $this->belongsToMany(NewsletterTemplate::class, 'newsletter_subscriber_template', 'list_id', 'newsletter_template_id');
    }

    public function subscriberEmailList()
    {
        return $this->hasMany(SubscriberEmailList::class);
    }
}
