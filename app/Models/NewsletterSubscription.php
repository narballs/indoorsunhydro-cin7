<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
    ];

    public function emailLists()
    {
        return $this->belongsToMany(EmailList::class, 'email_list_subscribers', 'subscriber_id', 'email_list_id');
    }
}
