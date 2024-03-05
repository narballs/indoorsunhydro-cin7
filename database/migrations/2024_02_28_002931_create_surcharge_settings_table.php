<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurchargeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surcharge_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('apply_surcharge')->default(0);
            $table->string('surcharge_type')->nullable();
            $table->float('surcharge_value')->default(0);
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
        Schema::dropIfExists('surcharge_settings');
    }
}
