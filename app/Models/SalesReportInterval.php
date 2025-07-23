<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReportInterval extends Model
{
    use HasFactory;
    protected $fillable = ['sales_report_setting_id', 'report_date', 'report_time'];

    public function sales_report_settings()
    {
        return $this->belongsTo(SalesReportSetting::class);
    }
}
