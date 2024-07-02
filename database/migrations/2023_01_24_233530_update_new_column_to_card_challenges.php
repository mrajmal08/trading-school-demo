<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNewColumnToCardChallenges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_challenges', function (Blueprint $table) {
            $table->string('market_data_id')->nullable()->after('title');
            $table->string('clone_id')->nullable()->after('market_data_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_challenges', function (Blueprint $table) {
            $table->dropColumn('market_data_id');
            $table->dropColumn('clone_id');
        });
    }
}
