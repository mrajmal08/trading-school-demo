<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnToWebSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->string('dark_logo', 255)->nullable()->after('logo');
            $table->string('linkedin')->nullable()->after('subscribe_description');
            $table->string('facebook')->nullable()->after('subscribe_description');
            $table->string('instagram')->nullable()->after('subscribe_description');
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
            $table->dropColumn('dark_logo');
            $table->dropColumn('linkedin');
            $table->dropColumn('facebook');
            $table->dropColumn('instagram');
        });
    }
}
