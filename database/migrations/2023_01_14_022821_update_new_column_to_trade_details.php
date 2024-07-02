<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNewColumnToTradeDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trading_details', function (Blueprint $table) {
            $table->string('rule_one_key')->change();
            $table->string('rule_two_key')->change();
            $table->string('rule_three_key')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trading_details', function (Blueprint $table) {
            $table->double('rule_one_key')->change();
            $table->double('rule_two_key')->change();
            $table->double('rule_three_key')->change();
        });
    }
}
