<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
             $table->string('last_name')->after('first_name')->nullable();
            $table->string('job_title')->after('last_name')->nullable();
            $table->string('mobile')->after('job_title')->nullable();
            $table->string('fax')->after('mobile')->nullable();
            $table->string('website')->after('fax')->nullable();
            $table->string('email')->after('website')->nullable();
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
