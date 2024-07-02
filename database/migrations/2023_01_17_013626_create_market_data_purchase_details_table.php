<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketDataPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_data_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreignId('market_data_id');
            $table->foreign('market_data_id')->references('id')->on('market_data')->onDelete('cascade');
            $table->boolean('account_activation_status')->nullable();

            $table->string('amp_id')->nullable();
            $table->string('trader_id')->nullable();
            $table->string('trader_name')->nullable();
            $table->string('account_id')->nullable();
            $table->string('account_name')->nullable();
            $table->double('package_price')->nullable();

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
        Schema::dropIfExists('market_data_purchase_details');
    }
}
