<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddColumnToMarketDataPurchaseDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_data_purchase_details', function (Blueprint $table) {
            $table->string('log')->nullable()->after('stripe_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_data_purchase_details', function (Blueprint $table) {
            $table->dropColumn('log');
        });
    }
}
