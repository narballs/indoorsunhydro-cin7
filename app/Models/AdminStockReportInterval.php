<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminStockReportInterval extends Model
{
    use HasFactory;

    protected $fillable = ['admin_stock_report_setting_id', 'report_date', 'report_time'];

    public function admin_stock_report_setting()
    {
        return $this->belongsTo(AdminStockReportSetting::class);
    }
}
