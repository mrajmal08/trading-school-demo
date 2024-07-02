<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyPaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_pays', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->double('amount')->nullable();
            $table->date('pay_date')->nullable();
            $table->boolean('status')->nullable()->default(0);
            $table->string('stripe_product_id')->nullable();
            $table->string('stripe_payment_id')->nullable();
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
        Schema::dropIfExists('monthly_pays');
    }
}
