<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiryDateToBuyListShippingAndDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_list_shipping_and_discounts', function (Blueprint $table) {
            $table->dateTime('expiry_date')->nullable()->after('discount_type');
            $table->integer('discount_count')->nullable()->default(0)->after('expiry_date');
            $table->integer('discount_limit')->nullable()->default(0)->after('discount_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buy_list_shipping_and_discounts', function (Blueprint $table) {
            //
        });
    }
}
