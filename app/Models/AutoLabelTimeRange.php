<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoLabelTimeRange extends Model
{
    use HasFactory;

    protected $fillable = [
        'auto_label_settings_id',
        'start_time',
        'end_time',
    ];

    public function autoLabelSetting()
    {
        return $this->belongsTo(AutoLabelSetting::class);
    }
}
