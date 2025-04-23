<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyListShippingAndDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_list_shipping_and_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buylist_id')->nullable();
            $table->decimal('shipping_cost', 10, 2)->default(0)->nullable();
            $table->decimal('discount', 10, 2)->default(0)->nullable();
            $table->string('discount_type')->default('percentage')->nullable(); // 'fixed' or 'percentage'
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
        Schema::dropIfExists('buy_list_shipping_and_discounts');
    }
}
