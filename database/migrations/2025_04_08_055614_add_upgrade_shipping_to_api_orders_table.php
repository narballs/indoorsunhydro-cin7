<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpgradeShippingToApiOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_orders', function (Blueprint $table) {
            $table->boolean('upgrade_shipping')->nullable()->default(0)->after('is_stripe');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_orders', function (Blueprint $table) {
            //
        });
    }
}
