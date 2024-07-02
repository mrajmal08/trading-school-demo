<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatedAddColumnToPackagePurchaseAccountDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_purchase_account_details', function (Blueprint $table) {
            $table->string('challenge_fail_popup')->nullable()->after('end_date');
            $table->string('rule_fail_popup')->nullable()->after('challenge_fail_popup');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_purchase_account_details', function (Blueprint $table) {
            $table->dropColumn('challenge_fail_popup');
            $table->dropColumn('rule_fail_popup');
        });
    }
}
