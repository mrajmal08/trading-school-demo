<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddNewColumnToMonthlyPays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_pays', function (Blueprint $table) {
            $table->double('stripe_amount')->nullable()->after('stripe_payment_id');
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
            $table->dropColumn('stripe_amount');
        });
    }
}
