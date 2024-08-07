<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreThreeColumnsToApiOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('billingEmail')->nullable();
            $table->string('accountsFirstName')->nullable();
            $table->string('accountsLastName')->nullable();
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
            $table->dropColumn('billingEmail');
            $table->dropColumn('accountsFirstName');
            $table->dropColumn('accountsLastName');
        });
    }
}
