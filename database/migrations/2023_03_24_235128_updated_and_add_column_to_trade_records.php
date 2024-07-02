<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatedAndAddColumnToTradeRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_records', function (Blueprint $table) {
            $table->foreignId('risk_management_id')->after('id');
            // $table->foreign('risk_management_id')->references('id')->on('risk_management')->onDelete('cascade');
            $table->string('scalePrice')->nullable()->after('risk_management_id');
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
            $table->dropColumn('risk_management_id');
            $table->dropColumn('scalePrice');
        });
    }
}
