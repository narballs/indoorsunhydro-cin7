<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsletterSubscriberTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletter_subscriber_template', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('list_id'); // Change column name to 'list_id'
            $table->unsignedBigInteger('newsletter_template_id');
            $table->boolean('sent')->default(false); // Add 'sent' column
            $table->timestamps();

            // Use shorter foreign key constraint names
            // $table->foreign('list_id', 'fk_list_id') // Change foreign key name to 'fk_list_id'
            //       ->references('id')->on('subscriber_lists')->onDelete('cascade');
            // $table->foreign('newsletter_template_id', 'fk_template_id')
            //       ->references('id')->on('newsletter_templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('newsletter_subscriber_template', function (Blueprint $table) {
        //     $table->dropForeign('fk_list_id'); // Drop foreign key constraint 'fk_list_id'
        //     $table->dropForeign('fk_template_id'); // Drop foreign key constraint 'fk_template_id'
        // });

        // Schema::dropIfExists('newsletter_subscriber_template');
    }
}
