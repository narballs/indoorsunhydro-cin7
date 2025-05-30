<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSummarySentToInvalidAddressUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invalid_address_users', function (Blueprint $table) {
            $table->boolean('summary_sent')->default(false)->nullable()->after('shipping_country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invalid_address_users', function (Blueprint $table) {
            //
        });
    }
}
