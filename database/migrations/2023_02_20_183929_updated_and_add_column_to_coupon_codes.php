<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatedAndAddColumnToCouponCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_codes', function (Blueprint $table) {
            $table->string('promotion_name')->nullable()->after('status');
            $table->double('total_number')->default(0)->after('promotion_name');
            $table->double('total_apply')->default(0)->after('total_number');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupon_codes', function (Blueprint $table) {
            $table->dropColumn('promotion_name');
            $table->dropColumn('total_number');
            $table->dropColumn('total_apply');
        });
    }
}
