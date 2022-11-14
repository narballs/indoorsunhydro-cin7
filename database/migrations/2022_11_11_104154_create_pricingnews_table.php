<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingnewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricingnews', function (Blueprint $table) {
            $table->id();
            $table->integer('option_id');
            $table->float('retailUSD',10,2);
            $table->float('wholesaleUSD',10,2);
            $table->float('oklahomaUSD',10,2);
            $table->float('calaverasUSD',10,2);
            $table->float('tier1USD',10,2);
            $table->float('tier2USD',10,2);
            $table->float('tier3USD',10,2);
            $table->float('commercialOKUSD',10,2);
            $table->float('costUSD',10,2);
            $table->float('specialPrice',10,2);
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
        Schema::dropIfExists('pricingnews');
    }
}
