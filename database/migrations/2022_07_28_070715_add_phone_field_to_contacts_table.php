<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneFieldToContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('delivery_phone')->after('mobile')->nullable();
            $table->longText('delivery_address_1')->after('delivery_phone')->nullable();
            $table->longText('delivery_address_2')->after('delivery_address_1')->nullable();
            $table->string('delivery_city')->after('delivery_address_2')->nullable();
            $table->string('delivery_state')->after('delivery_city')->nullable();
            $table->string('delivery_postal_code')->after('delivery_state')->nullable();


            $table->string('billing_phone')->after('delivery_postal_code')->nullable();
            $table->longText('billing_address_1')->after('billing_phone')->nullable();
            $table->longText('billing_address_2')->after('billing_address_1')->nullable();
            $table->string('billing_city')->after('billing_address_2')->nullable();
            $table->string('billing_state')->after('billing_city')->nullable();
            $table->string('billing_postal_code')->after('billing_state')->nullable();
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
