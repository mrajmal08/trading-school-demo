<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnToBlogDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_details', function (Blueprint $table) {
            $table->dropForeign(['blog_category_id']);
            $table->dropColumn('blog_category_id');
            $table->string('user_name')->after('uuid');
            $table->string('user_image')->nullable()->after('date');
            $table->text('tags')->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_details', function (Blueprint $table) {
            //
        });
    }
}
