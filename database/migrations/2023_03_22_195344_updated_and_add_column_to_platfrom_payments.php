<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatedAndAddColumnToPlatfromPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('platfrom_payments', function (Blueprint $table) {
            $table->string('total_user')->nullable()->after('your_cut_amount');
            $table->string('total_purchase')->nullable()->after('total_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('platfrom_payments', function (Blueprint $table) {
            $table->dropColumn('total_user');
            $table->dropColumn('total_purchase');
        });
    }
}
