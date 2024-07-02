<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiskManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risk_management', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('amp_id')->nullable();
            $table->string('trader_id')->nullable();
            $table->string('trader_name')->nullable();
            $table->string('account_id')->nullable();
            $table->string('account_name')->nullable();
            $table->foreignId('card_challenge_id');
            $table->foreign('card_challenge_id')->references('id')->on('card_challenges')->onDelete('cascade');
            $table->string('package_price')->nullable();
            $table->string('account_status')->nullable();
            $table->string('open_contracts')->nullable();
            $table->string('current_daily_pl')->nullable();
            $table->string('trading_day')->nullable();
            $table->string('net_liq_value')->nullable();
            $table->string('sodbalance')->nullable();
            $table->string('rule_1_value')->nullable();
            $table->string('rule_1_maximum')->nullable();
            $table->string('rule_2_value')->nullable();
            $table->string('rule_2_maximum')->nullable();
            $table->string('rule_3_value')->nullable();
            $table->string('rule_3_maximum')->nullable();
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
        Schema::dropIfExists('risk_management');
    }
}
