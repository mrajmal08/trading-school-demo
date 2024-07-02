<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagePurchaseAccountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_purchase_account_details', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('request_id')->nullable();
            $table->string('amp_id')->nullable();
            $table->string('trader_id')->nullable();
            $table->string('trader_name')->nullable();
            $table->string('account_id')->nullable();
            $table->string('account_name')->nullable();
            $table->string('new_customer_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_purchase_account_details');
    }
}
