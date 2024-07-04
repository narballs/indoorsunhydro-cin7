<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulkQuantityDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_quantity_discounts', function (Blueprint $table) {
            $table->id();
            $table->longText('items_list')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->string('delievery')->nullable();
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
        Schema::dropIfExists('bulk_quantity_discounts');
    }
}
