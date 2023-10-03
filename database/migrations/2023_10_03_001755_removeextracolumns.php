<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Removeextracolumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function($table) {
            $table->dropColumn('permit_image');
            $table->dropColumn('payable_phone');
            $table->dropColumn('payable_email');
            $table->dropColumn('payable_name');
            $table->dropColumn('parent_company_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
