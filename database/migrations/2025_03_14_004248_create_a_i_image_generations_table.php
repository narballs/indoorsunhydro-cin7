<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAIImageGenerationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_i_image_generations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->longText('image_url')->nullable();
            $table->boolean('status')->default(false);
            $table->longText('prompt')->nullable();
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
        Schema::dropIfExists('a_i_image_generations');
    }
}
