<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAiPriceUsdToPricingnewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricingnews', function (Blueprint $table) {
            $table->decimal('aiPriceUSD', 10, 2)->nullable()->default(0)->after('costUSD');
            $table->boolean('enable_ai_price')->nullable()->default(0)->after('aiPriceUSD');
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
