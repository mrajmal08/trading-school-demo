<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNewColumnToPackagePurchaseAccountDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_purchase_account_details', function (Blueprint $table) {
            $table->boolean('account_activation_status')->nullable()->after('account_status');
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
            $table->dropColumn('account_activation_status');
        });
    }
}
