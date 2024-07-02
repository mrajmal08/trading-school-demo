<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddColumnToSubscribeLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscribe_logs', function (Blueprint $table) {
            $table->string('package_purchase_account_detail_id')->nullable()->after('response');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscribe_logs', function (Blueprint $table) {
            $table->dropColumn('package_purchase_account_detail_id');
        });
    }
}
