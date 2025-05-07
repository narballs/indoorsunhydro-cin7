<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentInformationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_information_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->string('method')->nullable();
            $table->float('amount')->default(0)->nullable();
            $table->string('order_type')->nullable();
            $table->string('order_reference')->nullable();
            $table->string('created_date')->nullable();
            $table->string('payment_date')->nullable();
            $table->integer('branch_id')->nullable();
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
        Schema::dropIfExists('payment_information_logs');
    }
}
