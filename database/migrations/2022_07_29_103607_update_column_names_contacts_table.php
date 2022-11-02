<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnNamesContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->renameColumn('first_name', 'firstName');
            $table->renameColumn('last_name', 'lastName');
            $table->renameColumn('job_title', 'jobTitle');
            $table->renameColumn('delivery_phone', 'phone');
            $table->renameColumn('delivery_address_1', 'address1');
            $table->renameColumn('delivery_city', 'city');
            $table->renameColumn('delivery_state', 'state');
            $table->renameColumn('delivery_postal_code', 'postCode');
            $table->renameColumn('billing_address_1', 'postalAddress1');
            $table->renameColumn('billing_address_2', 'postalAddress2');
            $table->renameColumn('billing_city', 'postalCity');
            $table->renameColumn('billing_state', 'postalState');
            $table->renameColumn('billing_postal_code', 'postalPostCode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            //
        });
    }
}
