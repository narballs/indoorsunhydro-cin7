<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoLabelSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'days_of_week',
        'start_time',
        'end_time',
        'delay_processing',
        'delay_duration',
        'delay_unit',
    ];

    public function timeRanges()
    {
        return $this->hasMany(AutoLabelTimeRange::class  , 'auto_label_settings_id');
    }
}
