<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTablesForWholessale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('addresses', 'wholesale_application_addresses');
        Schema::rename('regulation_details', 'wholesale_application_regulation_details');
        Schema::rename('authorization_form_details', 'wholesale_application_authorization_details');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
