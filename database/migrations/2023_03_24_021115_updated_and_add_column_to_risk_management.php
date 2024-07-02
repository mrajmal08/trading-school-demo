<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatedAndAddColumnToRiskManagement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risk_management', function (Blueprint $table) {
            $table->string('dashboard_id')->nullable()->after('amp_id');
            $table->string('isActive')->nullable()->after('dashboard_id');
            $table->string('isPrimary')->nullable()->after('isActive');
            $table->string('accountSize')->nullable()->after('isPrimary');
            $table->string('isLocked')->nullable()->after('accountSize');
            $table->string('isEmpty')->nullable()->after('isLocked');
            $table->string('isMaybeLocked')->nullable()->after('isEmpty');
            $table->string('balance')->nullable()->after('isMaybeLocked');
            $table->string('dailyLossLimit')->nullable()->after('balance');
            $table->string('currentDrawdown')->nullable()->after('dailyLossLimit');
            $table->string('drawdownLimit')->nullable()->after('currentDrawdown');
            $table->string('rule1Enabled')->nullable()->after('drawdownLimit');
            $table->string('rule1Key')->nullable()->after('rule1Enabled');
            $table->string('rule1Failed')->nullable()->after('rule1Key');
            $table->string('rule2Enabled')->nullable()->after('rule1Failed');
            $table->string('rule2Key')->nullable()->after('rule2Enabled');
            $table->string('rule2Failed')->nullable()->after('rule2Key');
            $table->string('rule3Enabled')->nullable()->after('rule2Failed');
            $table->string('rule3Key')->nullable()->after('rule3Enabled');
            $table->string('rule3Failed')->nullable()->after('rule3Key');
            $table->string('profitTarget')->nullable()->after('rule3Failed');
            $table->string('minimumDays')->nullable()->after('profitTarget');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risk_management', function (Blueprint $table) {

            $table->dropColumn('dashboard_id');
            $table->dropColumn('isActive');
            $table->dropColumn('isPrimary');
            $table->dropColumn('accountSize');
            $table->dropColumn('isLocked');
            $table->dropColumn('isEmpty');
            $table->dropColumn('isMaybeLocked');
            $table->dropColumn('balance');
            $table->dropColumn('dailyLossLimit');
            $table->dropColumn('currentDrawdown');
            $table->dropColumn('drawdownLimit');
            $table->dropColumn('rule1Enabled');
            $table->dropColumn('rule1Key');
            $table->dropColumn('rule1Failed');
            $table->dropColumn('rule2Enabled');
            $table->dropColumn('rule2Key');
            $table->dropColumn('rule2Failed');
            $table->dropColumn('rule3Enabled');
            $table->dropColumn('rule3Key');
            $table->dropColumn('rule3Failed');
            $table->dropColumn('profitTarget');
            $table->dropColumn('minimumDays');
        });
    }
}
