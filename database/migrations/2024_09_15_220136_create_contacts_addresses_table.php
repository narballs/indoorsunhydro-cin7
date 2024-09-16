<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->string('address_label')->nullable();
            $table->string('DeliveryFirstName' , 255)->nullable();
            $table->string('DeliveryLastName' , 255)->nullable();
            $table->string('DeliveryCompany' , 255)->nullable();
            $table->text('DeliveryAddress1')->nullable();
            $table->text('DeliveryAddress2')->nullable();
            $table->string('DeliveryCity' , 255)->nullable();
            $table->string('DeliveryState' , 255)->nullable();
            $table->string('DeliveryZip' , 255)->nullable();
            $table->string('DeliveryCountry' , 255)->nullable();
            $table->string('DeliveryPhone' , 255)->nullable();
            $table->string('BillingFirstName' , 255)->nullable();
            $table->string('BillingLastName' , 255)->nullable();
            $table->string('BillingCompany' , 255)->nullable();
            $table->text('BillingAddress1')->nullable();
            $table->text('BillingAddress2')->nullable();
            $table->string('BillingCity' , 255)->nullable();
            $table->string('BillingState' , 255)->nullable();
            $table->string('BillingZip' , 255)->nullable();
            $table->string('BillingCountry' , 255)->nullable();
            $table->string('BillingPhone' , 255)->nullable();
            $table->boolean('is_default')->default(0);      
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
        Schema::dropIfExists('contacts_addresses');
    }
}
