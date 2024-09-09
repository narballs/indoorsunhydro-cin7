<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->id();
            $table->string('createdDate')->nullable();
            $table->string('modifiedDate')->nullable();
            $table->string('paymentDate')->nullable();
            $table->float('amount')->default(0);
            $table->string('method')->nullable();
            $table->boolean('isAuthorized')->default(false);
            $table->string('transactionRef')->nullable();
            $table->string('comments')->nullable();
            $table->integer('orderId')->nullable();
            $table->string('orderRef')->nullable();
            $table->string('paymentImportedRef')->nullable();
            $table->string('batchReference')->nullable();
            $table->string('reconcileDate')->nullable();
            $table->string('branchId')->nullable();
            $table->string('orderType')->nullable();
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
        Schema::dropIfExists('sale_payments');
    }
}
