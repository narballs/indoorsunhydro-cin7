<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileNumberCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_number_campaigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sms_template_id');
            $table->unsignedBigInteger('mobile_number_list_id');
            $table->timestamp('sent_date')->nullable();
            $table->boolean('sent')->default(false);
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
        Schema::dropIfExists('mobile_number_campaigns');
    }
}
