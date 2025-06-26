<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_reminders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // FK from users
            $table->unsignedBigInteger('contact_id')->nullable(); // FK from api_contacts
            $table->unsignedBigInteger('order_id')->nullable(); // FK from api_orders
            $table->date('reminder_date')->nullable();
            $table->boolean('is_sent')->default(0)->nullable();
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
        Schema::dropIfExists('order_reminders');
    }
}
