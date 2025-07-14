<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('stripe_id')->nullable();
            $table->string('status')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency', 10)->default('usd')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('refund_reason')->nullable();
            $table->string('partial_refund_reason')->nullable();
            $table->timestamp('refund_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamp('transaction_date')->nullable();

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
        Schema::dropIfExists('sales_reports');
    }
}
