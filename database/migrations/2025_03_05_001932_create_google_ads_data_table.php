<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleAdsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_ads_data', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->integer('clicks')->default(0)->nullable();
            $table->integer('impressions')->default(0)->nullable();
            $table->decimal('spend', 10, 2)->default(0.00)->nullable();
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
        Schema::dropIfExists('google_ads_data');
    }
}
