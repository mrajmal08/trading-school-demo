<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_records', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('current_date')->nullable();
            $table->string('amp_id')->nullable();
            $table->string('trader_id')->nullable();
            $table->string('trader_name')->nullable();
            $table->string('account_id')->nullable();
            $table->string('account_name')->nullable();
            $table->foreignId('card_challenge_id');
            $table->foreign('card_challenge_id')->references('id')->on('card_challenges')->onDelete('cascade');
            $table->string('package_price')->nullable();
            $table->string('account_status')->nullable();
            $table->string('trading_day')->nullable();
            $table->string('open_contracts')->nullable();
            $table->string('current_daily_pl')->nullable();
            $table->string('net_liq_value')->nullable();
            $table->string('sodbalance')->nullable();
            $table->string('rule_1_value')->nullable();
            $table->string('rule_1_maximum')->nullable();
            $table->string('rule_2_value')->nullable();
            $table->string('rule_2_maximum')->nullable();
            $table->string('rule_3_value')->nullable();
            $table->string('rule_3_maximum')->nullable();
            $table->string('dashboard_id')->nullable();
            $table->string('isActive')->nullable();
            $table->string('isPrimary')->nullable();
            $table->string('accountSize')->nullable();
            $table->string('isLocked')->nullable();
            $table->string('isEmpty')->nullable();
            $table->string('isMaybeLocked')->nullable();
            $table->string('balance')->nullable();
            $table->string('dailyLossLimit')->nullable();
            $table->string('currentDrawdown')->nullable();
            $table->string('drawdownLimit')->nullable();
            $table->string('rule1Enabled')->nullable();
            $table->string('rule1Key')->nullable();
            $table->string('rule1Failed')->nullable();
            $table->string('rule2Enabled')->nullable();
            $table->string('rule2Key')->nullable();
            $table->string('rule2Failed')->nullable();
            $table->string('rule3Enabled')->nullable();
            $table->string('rule3Key')->nullable();
            $table->string('rule3Failed')->nullable();
            $table->string('profitTarget')->nullable();
            $table->string('minimumDays')->nullable();
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
        Schema::dropIfExists('daily_records');
    }
}
