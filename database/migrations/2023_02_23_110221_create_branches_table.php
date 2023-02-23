<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->integer('branchId');
            $table->integer('secondaryContactId');
            $table->integer('branchLocationId');
            $table->double('isActive');
            $table->string('company')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('jobTitle')->nullable();
            $table->string('email');
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('mobile')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postCode')->nullable();
            $table->string('country')->nullable();
            $table->string('postalAddress1')->nullable();
            $table->string('postalAddress2')->nullable();
            $table->string('postalCity')->nullable();
            $table->string('postalState')->nullable();
            $table->string('postalCountry')->nullable();
            $table->string('notes')->nullable();
            $table->string('integrationRef')->nullable();
            $table->string('branchType')->nullable();
            $table->string('stockControlOptions')->nullable();
            $table->string('taxStatus')->nullable();
            $table->string('accountNumber')->nullable();
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
        Schema::dropIfExists('branches');
    }
}
