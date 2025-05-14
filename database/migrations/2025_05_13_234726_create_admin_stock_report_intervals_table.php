<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminStockReportIntervalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_stock_report_intervals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_stock_report_setting_id');
            $table->date('report_date')->nullable();
            $table->time('report_time')->default('09:00:00')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_stock_report_intervals');
    }
}
