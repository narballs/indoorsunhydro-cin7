<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminStockReportSetting extends Model
{
    use HasFactory;

    protected $fillable = ['emails'];

    public function admin_stock_report_interval()
    {
        return $this->hasMany(AdminStockReportInterval::class);
    }
}
