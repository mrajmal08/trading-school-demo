<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNewColumnToMonthlyPays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_pays', function (Blueprint $table) {
            $table->string('stripe_monthly_product_id')->nullable()->after('stripe_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monthly_pays', function (Blueprint $table) {
            $table->dropColumn('stripe_monthly_product_id');
        });
    }
}
