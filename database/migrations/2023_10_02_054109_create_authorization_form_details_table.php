<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorizationFormDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorization_form_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->string('authorize_name')->nullable();
            $table->string('financial_institute_name')->nullable();
            $table->string('financial_institute_address')->nullable();
            $table->string('financial_institute_signature')->nullable();
            $table->float('set_amount')->nullable();
            $table->float('maximum_amount')->nullable();
            $table->string('financial_institute_routine_number')->nullable();
            $table->string('financial_institute_account_number')->nullable();
            $table->string('financial_institute_permit_number')->nullable();
            $table->string('financial_institute_phone_number')->nullable();
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
        Schema::dropIfExists('authorization_form_details');
    }
}
