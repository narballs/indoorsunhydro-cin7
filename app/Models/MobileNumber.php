<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobile_number',
        'tags'
    ];

    public function templates()
    {
        return $this->belongsToMany(SmsTemplate::class, 'mobile_number_campaigns', 'mobile_number_id', 'sms_template_id');
    }
}
