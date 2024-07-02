<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddNewColumnToPackagePurchaseAccountDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_purchase_account_details', function (Blueprint $table) {
            $table->double('stripe_amount')->nullable()->after('account_activation_status');
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
            $table->dropColumn('stripe_amount');
        });
    }
}
