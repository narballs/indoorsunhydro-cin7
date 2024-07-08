<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'description',
        'tags',
        'reply'
    ];


    public function mobile_numbers()
    {
        return $this->belongsToMany(MobileNumber::class, 'mobile_number_campaigns', 'mobile_number_id', 'sms_template_id');
    }

    public function mobile_number_lists()
    {
        return $this->belongsToMany(MobileNumberList::class, 'mobile_number_campaigns', 'mobile_number_list_id', 'sms_template_id');
    }

    public function send_sms()
    {
        return $this->hasMany(MobileNumberCampaign::class, 'sms_template_id');
    }

}
