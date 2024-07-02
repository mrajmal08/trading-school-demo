<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNewColumnToWebSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->text('privacy_policy_detail')->nullable()->after('linkedin');
            $table->text('terms_service_detail')->nullable()->after('privacy_policy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->dropColumn('privacy_policy_detail');
            $table->dropColumn('terms_service_detail');
        });
    }
}
