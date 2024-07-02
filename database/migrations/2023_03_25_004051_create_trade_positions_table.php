<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_positions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('risk_management_id');
            // $table->foreign('risk_management_id')->references('id')->on('risk_management')->onDelete('cascade');
            $table->string('account_id')->nullable();
            $table->string('index')->nullable();
            $table->string('symbol')->nullable();
            $table->string('quantity')->nullable();
            $table->string('avgPrice')->nullable();
            $table->string('realizedPl')->nullable();
            $table->string('openPl')->nullable();
            $table->string('totalPl')->nullable();
            $table->string('priceScale')->nullable();
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
        Schema::dropIfExists('trade_positions');
    }
}
