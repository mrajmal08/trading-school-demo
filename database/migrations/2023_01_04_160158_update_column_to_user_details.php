<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnToUserDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->string('gender_id')->change();
            // $table->date('age')->change();
            $table->renameColumn('timezone', 'picture');
            $table->renameColumn('gender_id', 'gender');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_details', function (Blueprint $table) {
            //
            $table->renameColumn('gender', 'gender_id');
            $table->renameColumn('picture', 'timezone');
        });
    }
}
