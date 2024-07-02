<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnToPackagePurchaseAccountDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_purchase_account_details', function (Blueprint $table) {
           

            $table->foreignId('card_challenge_id')->nullable()->after('uuid');
            $table->foreign('card_challenge_id')->references('id')->on('card_challenges')->onDelete('cascade');
            $table->double('package_price')->nullable()->after('new_customer_id');
            $table->string('account_status')->nullable()->after('package_price');

        
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
            $table->dropConstrainedForeignId('card_challenge_id');
            $table->dropColumn('package_price');
            $table->dropColumn('account_status');
        });
    }
}
