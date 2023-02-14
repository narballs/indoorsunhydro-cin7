<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecondaryContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secondary_contacts', function (Blueprint $table) {
            $table->id();
            $table->biginteger('secondary_id');
            $table->integer('parent_id');
            $table->string('company');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('jobTitle');
            $table->string('email');
            $table->string('mobile');
            $table->string('phone');
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
        Schema::dropIfExists('secondary_contacts');
    }
}
