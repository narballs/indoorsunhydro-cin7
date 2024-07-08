<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileNumberCampaign extends Model
{
    use HasFactory;
    protected $fillable = [
        'sms_template_id',
        'mobile_number_list_id',
        'sent_date',
        'sent'
    ];


    public function sms_template()
    {
        return $this->belongsTo(SmsTemplate::class, 'sms_template_id');
    }

    public function mobile_number_list()
    {
        return $this->belongsTo(MobileNumberList::class, 'mobile_number_list_id');
    }
}
