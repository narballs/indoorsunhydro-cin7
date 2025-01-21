<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutoLabelSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_label_settings', function (Blueprint $table) {
            $table->id();
            $table->string('days_of_week')->nullable();  // Store as comma-separated values (e.g., "M,T,W,TH,F")
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('delay_processing')->default(false)->nullable();
            $table->integer('delay_duration')->nullable();
            $table->string('delay_unit')->nullable(); // 'Minutes', 'Hours', etc.
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
        Schema::dropIfExists('auto_label_settings');
    }
}
