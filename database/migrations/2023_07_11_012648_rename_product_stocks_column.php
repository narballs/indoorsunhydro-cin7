<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProductStocksColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stocks', function(Blueprint $table) {
            $table->renameColumn('stock_available', 'available_stock');
            $table->renameColumn('branch_name', 'branch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_stocks', function(Blueprint $table) {
            $table->renameColumn('stock_available', 'available_stock');
            $table->renameColumn('branch_name', 'branch_id');
        });
    }
}
