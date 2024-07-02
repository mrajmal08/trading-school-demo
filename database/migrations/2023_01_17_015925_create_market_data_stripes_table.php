<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketDataStripesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_data_stripes', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('market_data_id');
            $table->foreign('market_data_id')->references('id')->on('market_data')->onDelete('cascade');
            $table->string('stripe_product_id')->nullable();
            $table->string('stripe_product_price_id')->nullable();
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
        Schema::dropIfExists('market_data_stripes');
    }
}
