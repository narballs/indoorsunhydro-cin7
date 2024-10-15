<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('author_name')->nullable();
            $table->longtext('author_url')->nullable();
            $table->string('language')->nullable();
            $table->longtext('profile_photo_url')->nullable();
            $table->integer('rating')->nullable()->default(0);
            $table->string('relative_time_description')->nullable();
            $table->longtext('text')->nullable();
            $table->timestamp('review_time')->nullable();
            $table->string('google_review_id')->nullable()->unique(); // To avoid duplicates
            $table->string('place_id')->nullable();
            $table->boolean('translated')->default(false); // Whether the review is translated or not
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
        Schema::dropIfExists('google_reviews');
    }
}
