<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payout_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payout_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->text('payout_balance_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->text('customer_email')->nullable();
            $table->string('currency')->nullable();
            $table->string('type')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('converted_amount', 10, 2)->nullable();
            $table->decimal('fees', 10, 2)->nullable();
            $table->decimal('net', 10, 2)->nullable();
            $table->dateTime('charge_created')->nullable();

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
        Schema::dropIfExists('payout_balances');
    }
}
