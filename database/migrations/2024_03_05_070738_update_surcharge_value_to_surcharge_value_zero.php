<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSurchargeValueToSurchargeValueZero extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surcharge_settings', function (Blueprint $table) {
            $table->boolean('apply_surcharge')->nullable()->default(0)->change();
            $table->float('surcharge_value')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surcharge_value_zero', function (Blueprint $table) {
            //
        });
    }
}
