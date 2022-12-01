<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubtotalAndTotalColumnToProductBuyListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_buy_lists', function (Blueprint $table) {
            $table->float('sub_total')->after('quantity');
            $table->float('grand_total')->after('sub_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_buy_lists', function (Blueprint $table) {
            //
        });
    }
}
