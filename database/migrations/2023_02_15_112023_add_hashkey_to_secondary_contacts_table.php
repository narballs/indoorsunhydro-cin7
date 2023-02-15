<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHashkeyToSecondaryContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('secondary_contacts', function (Blueprint $table) {
            $table->string('hashKey')->after('phone')->nullable();
            $table->boolean('hashUsed')->after('hashKey')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('secondary_contacts', function (Blueprint $table) {
            //
        });
    }
}
