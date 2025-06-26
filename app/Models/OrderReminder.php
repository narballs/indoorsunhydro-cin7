<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReminder extends Model
{
    use HasFactory;

    protected $table = 'order_reminders';

    protected $fillable = [
        'user_id',
        'contact_id',
        'order_id',
        'reminder_date',
        'is_sent',
    ];

    /**
     * Get the user that owns the order reminder.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the contact associated with the order reminder.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id' , 'contact_id');
    }

    /**
     * Get the order associated with the order reminder.
     */
    public function order()
    {
        return $this->belongsTo(ApiOrder::class, 'order_id', 'id');
    }
}
