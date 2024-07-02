<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyAllRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_all_records', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('account_id_number')->nullable();
            $table->string('current_date')->nullable();
            $table->string('total_pl')->nullable();
            $table->string('number_trades')->nullable();
            $table->string('number_contracts')->nullable();
            $table->string('avg_trading_time')->nullable();
            $table->string('longest_trading_time')->nullable();
            $table->string('percent_profitable')->nullable();
            $table->string('expectancy')->nullable();
            $table->foreignId('daily_record_id')->nullable();
            $table->foreign('daily_record_id')->references('id')->on('daily_records')->onDelete('cascade');
            $table->string('avgPlTrade')->nullable();
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
        Schema::dropIfExists('daily_all_records');
    }
}
