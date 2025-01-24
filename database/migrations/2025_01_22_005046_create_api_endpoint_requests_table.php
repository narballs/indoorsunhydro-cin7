<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiEndpointRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_endpoint_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_key_id');
            $table->string('title')->nullable();
            $table->text('url')->nullable();
            $table->integer('request_count')->default(0)->nullable();
            $table->boolean('is_active')->default(true)->nullable();
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
        Schema::dropIfExists('api_endpoint_requests');
    }
}
