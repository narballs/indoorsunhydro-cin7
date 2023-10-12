<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegulationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regulation_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->string('seller_name')->nullable();
            $table->string('seller_address')->nullable();
            $table->string('purchaser_signature')->nullable();
            $table->string('certificate_eligibility_1')->nullable();
            $table->string('certificate_eligibility_2')->nullable();
            $table->string('equipment_type')->nullable();
            $table->string('purchaser_company_name')->nullable();
            $table->string('title')->nullable();
            $table->string('purchaser_address')->nullable();
            $table->string('purchaser_phone')->nullable();
            $table->date('purchase_date')->nullable();
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
        Schema::dropIfExists('regulation_details');
    }
}
