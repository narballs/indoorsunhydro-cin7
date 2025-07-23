<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReportSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'emails', // Comma-separated list of emails
    ];

    public function sales_report_interval()
    {
        return $this->hasMany(SalesReportInterval::class);
    }
}
