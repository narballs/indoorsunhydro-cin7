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
}
