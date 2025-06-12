<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderJobLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_job_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_order_id')->nullable();
            $table->string('reference')->nullable();
            $table->integer('attempt_number')->default(1)->nullable();
            $table->longText('message')->nullable();
            $table->timestamp('logged_at')->useCurrent()->nullable();
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
        Schema::dropIfExists('order_job_logs');
    }
}
