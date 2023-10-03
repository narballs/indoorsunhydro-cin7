<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCardholderZipCodeToWholesaleApplicationCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wholesale_application_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('cardholder_zip_code')->nullable()->after('card_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wholesale_application_cards', function (Blueprint $table) {
            //
        });
    }
}
