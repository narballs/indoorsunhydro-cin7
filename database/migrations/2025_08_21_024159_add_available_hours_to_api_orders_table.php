<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvailableHoursToApiOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_orders', function (Blueprint $table) {
            $table->string('delivery_hours')->nullable()->after('tax_class_id');
            $table->string('location_type')->nullable()->after('delivery_hours');
            $table->string('delievery_fee_disclaimer')->nullable()->after('location_type');
            $table->string('contact_person')->nullable()->after('delievery_fee_disclaimer');
            $table->string('contact_person_phone_number')->nullable()->after('contact_person');
            $table->string('request_lift_gate_truck')->nullable()->after('contact_person_phone_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_orders', function (Blueprint $table) {
            //
        });
    }
}
