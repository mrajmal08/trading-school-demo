<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnToChallengStripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('challenge_stripes', function (Blueprint $table) {
            $table->string('stripe_product_price_id')->nullable()->after('stripe_product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('challenge_stripes', function (Blueprint $table) {
            $table->dropColumn('stripe_product_price_id');
        });
    }
}
