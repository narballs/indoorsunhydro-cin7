<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSacramentoUSDToPricingnewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricingnews', function (Blueprint $table) {
            $table->float('terraInternUSD',10,2)->after('retailUSD');
            $table->float('sacramentoUSD',10,2)->after('terraInternUSD');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pricingnews', function (Blueprint $table) {
            //
        });
    }
}
