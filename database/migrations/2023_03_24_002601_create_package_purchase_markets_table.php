<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagePurchaseMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_purchase_markets', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('package_purchase_account_detail_id');
            $table->foreignId('market_data_id');
            $table->foreign('market_data_id')->references('id')->on('market_data')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_purchase_markets');
    }
}
