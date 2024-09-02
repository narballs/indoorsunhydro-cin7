<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerRelatedColumnsToSalePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_payments', function (Blueprint $table) {
            $table->string('customer_first_name')->nullable()->after('orderType');
            $table->string('customer_last_name')->nullable()->after('customer_first_name');
            $table->string('invoice_number')->nullable()->after('customer_last_name');
            $table->string('po_number')->nullable()->after('invoice_number');
            $table->string('company')->nullable()->after('po_number');
            $table->string('email')->nullable()->after('company');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_payments', function (Blueprint $table) {
            //
        });
    }
}
