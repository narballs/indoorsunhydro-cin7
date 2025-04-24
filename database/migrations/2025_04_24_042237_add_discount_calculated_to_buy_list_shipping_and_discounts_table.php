<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountCalculatedToBuyListShippingAndDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_list_shipping_and_discounts', function (Blueprint $table) {
            $table->decimal('discount_calculated', 8, 2)->default(0.00)->after('shipping_cost');
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
