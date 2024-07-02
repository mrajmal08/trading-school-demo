<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGraphHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('graph_histories', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('account_id_number')->nullable();
            $table->double('day_index')->nullable();
            $table->double('eod_net_liq')->nullable();
            $table->double('eod_drawdown')->nullable();
            $table->double('eod_profit_target')->nullable();
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
        Schema::dropIfExists('graph_histories');
    }
}
