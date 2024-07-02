<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfitTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profit_trades', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('account_id_number')->nullable();
            $table->double('total_pl')->nullable();
            $table->double('number_trades')->nullable();
            $table->double('number_contracts')->nullable();
            $table->double('avg_trading_time')->nullable()->comment('in seconds');
            $table->double('longest_trading_time')->nullable();
            $table->double('percent_profitable')->nullable();
            $table->double('expectancy')->nullable();
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
        Schema::dropIfExists('profit_trades');
    }
}
