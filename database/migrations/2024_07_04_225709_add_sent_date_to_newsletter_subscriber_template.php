<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSentDateToNewsletterSubscriberTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newsletter_subscriber_template', function (Blueprint $table) {
            $table->timestamp('sent_date')->nullable()->after('sent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('newsletter_subscriber_template', function (Blueprint $table) {
            //
        });
    }
}
