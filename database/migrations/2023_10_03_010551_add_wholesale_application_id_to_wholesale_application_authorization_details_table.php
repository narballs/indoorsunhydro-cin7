<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWholesaleApplicationIdToWholesaleApplicationAuthorizationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wholesale_application_authorization_details', function (Blueprint $table) {
            $table->unsignedBigInteger('wholesale_application_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wholesale_application_authorization_details', function (Blueprint $table) {
            //
        });
    }
}
