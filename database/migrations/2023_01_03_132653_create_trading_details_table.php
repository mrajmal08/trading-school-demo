<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trading_details', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('account_id_number')->nullable();
            $table->double('api_id')->nullable();
            $table->boolean('is_active')->nullable()->comment('1=true; 0=false');
            $table->boolean('is_primary')->nullable()->comment('1=true; 0=false');
            $table->double('account_size')->nullable();
            $table->string('account_name')->nullable();
            $table->boolean('is_locked')->nullable()->comment('1=true; 0=false');
            $table->boolean('is_empty')->nullable()->comment('1=true; 0=false');
            $table->boolean('is_maybe_locked')->nullable()->comment('1=true; 0=false');
            $table->double('balance')->nullable();
            $table->double('sodbalance')->nullable();
            $table->double('current_daily_pl')->nullable();
            $table->double('open_contracts')->nullable();
            $table->double('daily_loss_limit')->nullable();
            $table->double('net_liq_value')->nullable();

            $table->double('current_drawdown')->nullable();
            $table->double('drawdown_limit')->nullable();
            $table->string('drawdown_type')->nullable();
            $table->double('trading_day')->nullable();
            $table->boolean('rule_one_enabled')->nullable()->comment('1=true; 0=false');
            $table->double('rule_one_value')->nullable();
            $table->double('rule_one_key')->nullable();
            $table->double('rule_one_maximum')->nullable();

            $table->boolean('rule_two_enabled')->nullable()->comment('1=true; 0=false');
            $table->double('rule_two_value')->nullable();
            $table->double('rule_two_key')->nullable();
            $table->double('rule_two_maximum')->nullable();

            $table->boolean('rule_three_enabled')->nullable()->comment('1=true; 0=false');
            $table->double('rule_three_value')->nullable();
            $table->double('rule_three_key')->nullable();
            $table->double('rule_three_maximum')->nullable();

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
        Schema::dropIfExists('trading_details');
    }
}
