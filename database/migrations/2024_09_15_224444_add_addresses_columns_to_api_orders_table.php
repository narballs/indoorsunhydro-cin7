<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressesColumnsToApiOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_orders', function (Blueprint $table) {
            $table->string('DeliveryFirstName' , 255)->nullable()->after('memo');
            $table->string('DeliveryLastName' , 255)->nullable()->after('memo');
            $table->string('DeliveryCompany' , 255)->nullable()->after('memo');
            $table->text('DeliveryAddress1')->nullable()->after('memo');
            $table->text('DeliveryAddress2')->nullable()->after('memo');
            $table->string('DeliveryCity' , 255)->nullable()->after('memo');
            $table->string('DeliveryState'  , 255)->nullable()->after('memo');
            $table->string('DeliveryZip' , 255)->nullable()->after('memo');
            $table->string('DeliveryCountry' , 255)->nullable()->after('memo');
            $table->string('DeliveryPhone' , 255)->nullable()->after('memo');
            $table->string('BillingFirstName' , 255)->nullable()->after('memo');
            $table->string('BillingLastName' , 255)->nullable()->after('memo');
            $table->string('BillingCompany' , 255)->nullable()->after('memo');
            $table->text('BillingAddress1')->nullable()->after('memo');
            $table->text('BillingAddress2')->nullable()->after('memo');
            $table->string('BillingCity' , 255)->nullable()->after('memo');
            $table->string('BillingState' , 255)->nullable()->after('memo');
            $table->string('BillingZip' , 255)->nullable()->after('memo');
            $table->string('BillingCountry' , 255)->nullable()->after('memo');
            $table->string('BillingPhone' , 255)->nullable()->after('memo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts_addresses', function (Blueprint $table) {
            //
        });
    }
}
