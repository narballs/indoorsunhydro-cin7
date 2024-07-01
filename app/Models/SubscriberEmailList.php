<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriberEmailList extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_lists_id',
        'email',
    ];

    public function subscriberList()
    {
        return $this->belongsTo(SubscriberList::class);
    }
}
