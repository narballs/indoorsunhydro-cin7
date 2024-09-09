<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalePaymentOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_payment_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_payment_id')->nullable();
            $table->unsignedBigInteger('orderId')->nullable();
            $table->string('createdDate')->nullable();
            $table->integer('transactionId')->nullable();
            $table->integer('parentId')->nullable();
            $table->integer('productId')->nullable();
            $table->integer('productOptionId')->nullable();
            $table->string('integrationRef')->nullable();
            $table->string('sort')->nullable();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('option1')->nullable();
            $table->string('option2')->nullable();
            $table->string('option3')->nullable();
            $table->string('qty')->nullable();
            $table->string('styleCode')->nullable();
            $table->string('barcode')->nullable();
            $table->string('sizeCodes')->nullable();
            $table->longtext('lineComments')->nullable();
            $table->float('unitCost')->nullable();
            $table->float('unitPrice')->nullable();
            $table->float('uomPrice')->nullable();
            $table->float('discount')->nullable();
            $table->integer('uomQtyOrdered')->nullable();
            $table->integer('uomQtyShipped')->nullable();
            $table->integer('uomSize')->nullable();
            $table->integer('qtyShipped')->nullable();
            $table->integer('holdingQty')->nullable();
            $table->string('accountCode')->nullable();
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
        Schema::dropIfExists('sale_payment_order_items');
    }
}
