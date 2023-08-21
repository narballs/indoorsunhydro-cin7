<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsPricingnews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricingnews', function (Blueprint $table) {
             $table->double('tier0USD', 10, 2)->after('wholesaleUSD')->nullable();
             $table->double('disP1USD', 10, 2)->after('specialPrice')->nullable();
             $table->double('disP2USD', 10, 2)->after('disP1USD')->nullable();
             $table->double('comccusd', 10, 2)->after('disP2USD')->nullable();
             $table->double('com1USD', 10, 2)->after('comccusd')->nullable();
             $table->double('msrpusd', 10, 2)->after('com1USD')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
