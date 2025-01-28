<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActionColumnToShipstationApiLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipstation_api_logs', function (Blueprint $table) {
            $table->string('action')->nullable()->after('api_url'); // Adjust 'some_existing_column' as needed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipstation_api_logs', function (Blueprint $table) {
            //
        });
    }
}
