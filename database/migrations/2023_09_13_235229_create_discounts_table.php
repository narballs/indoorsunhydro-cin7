<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('mode')->nullable();
            $table->string('discount_code')->nullable();
            $table->string('minimum_purchase_requirements')->nullable();
            $table->string('discount_variation')->nullable();
            $table->float('discount_variation_value')->nullable();
            $table->string('minimum_quantity_items')->nullable();
            $table->float('minimum_purchase_amount')->nullable();
            $table->string('customer_eligibility')->nullable();
            $table->integer('max_usage_count')->nullable();
            $table->integer('usage_count')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('discounts');
    }
}
