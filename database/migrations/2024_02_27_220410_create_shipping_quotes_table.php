<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('service_code')->nullable();
            $table->string('carrier_code')->nullable(); 
            $table->string('carrier_name')->nullable();
            $table->string('service_name')->nullable();
            $table->string('type')->nullable();
            $table->boolean('status')->default(1);


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
        Schema::dropIfExists('shipping_quotes');
    }
}
