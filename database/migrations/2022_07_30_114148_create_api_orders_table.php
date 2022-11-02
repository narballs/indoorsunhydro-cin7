<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->dateTime('createdDate');
            $table->dateTime('modifiedDate');
            $table->integer('createdBy');
            $table->integer('processedBy');
            $table->boolean('isApproved');
            $table->string('reference');
            $table->integer('memberId');
            $table->integer('branchId');
            $table->string('branchEmail');
            $table->float('productTotal');
            $table->float('total');
            $table->string('currencyCode');
            $table->string('currencyRate');
            $table->string('currencySymbol');
            $table->string('status');
            $table->string('stage');
            $table->string('paymentTerms');
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
        Schema::dropIfExists('api_orders');
    }
}
