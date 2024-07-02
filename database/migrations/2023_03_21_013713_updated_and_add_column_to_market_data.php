<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatedAndAddColumnToMarketData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_data', function (Blueprint $table) {
            $table->string('type')->nullable()->after('price')->comment('CME and EUREX');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_data', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
