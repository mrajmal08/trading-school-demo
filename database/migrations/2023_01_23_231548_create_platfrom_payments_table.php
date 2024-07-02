<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatfromPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platfrom_payments', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->double('challenges_amount')->nullable();
            $table->double('market_amount')->nullable();
            $table->double('payment_getaway_amount')->nullable();
            $table->double('other_amount')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('system_cut_amount')->nullable();
            $table->double('your_cut_amount')->nullable();
            $table->boolean('status')->nullable()->default(0);
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
        Schema::dropIfExists('platfrom_payments');
    }
}
