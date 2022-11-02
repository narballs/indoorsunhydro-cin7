<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->dateTime('createdDate');
            $table->dateTime('modifiedDate');
            $table->bigInteger('option_id');
            $table->integer('product_id');
            $table->string('status');
            $table->string('code');
            $table->string('productOptionSizeCode')->nullable();
            $table->string('supplierCode')->nullable();
            $table->string('option1')->nullable();
            $table->string('option2')->nullable();
            $table->string('option3')->nullable();
            $table->string('optionWeight')->nullable();
            $table->string('size')->nullable();
            $table->string('retailPrice')->nullable();
            $table->string('wholesalePrice')->nullable();
            $table->string('vipPrice')->nullable();
            $table->string('specialPrice')->nullable();
            $table->string('specialsStartDate')->nullable();
            $table->string('stockAvailable')->nullable();
            $table->string('stockOnHand')->nullable();
            $table->string('specialDays')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('product_options');
    }
}
