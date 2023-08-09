<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayTermsColumnToApiOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_orders', function (Blueprint $table) {
            $table->string('pay_terms')->after('paymentTerms')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_orders', function (Blueprint $table) {
            $table->dropColumn('pay_terms');
        });
    }
}
