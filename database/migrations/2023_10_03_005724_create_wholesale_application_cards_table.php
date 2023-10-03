<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesaleApplicationCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wholesale_application_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wholesale_application_id');
            $table->string('card_type')->nullable();
            $table->string('cardholder_name')->nullable();
            $table->string('card_number')->nullable();
            $table->string('authorize_card_name')->nullable();
            $table->string('authorize_card_text')->nullable();
            $table->string('expiration_date')->nullable();
            $table->string('customer_signature')->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('wholesale_application_cards');
    }
}
