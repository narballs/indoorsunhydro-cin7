<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationColumnsToSpecificAdminNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('specific_admin_notifications', function (Blueprint $table) {
            $table->boolean('receive_order_notifications')->nullable()->default(false)->after('user_id');
            $table->boolean('receive_label_notifications')->nullable()->default(false)->after('receive_order_notifications');
            $table->boolean('receive_accounting_reports')->nullable()->default(false)->after('receive_label_notifications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('specific_admin_notifications', function (Blueprint $table) {
            //
        });
    }
}
