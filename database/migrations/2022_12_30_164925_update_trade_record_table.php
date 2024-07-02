<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTradeRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_records', function (Blueprint $table) {
            $table->renameColumn('balance', 'buy');
            $table->foreignId('user_id')->nullable()->after('uuid');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trade_records', function (Blueprint $table) {
            $table->renameColumn('buy', 'balance');
            $table->dropColumn('user_id');
        });
    }
}
