<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_challenges', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('service_id');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->string('title')->comment("Futures Pro Challenge,Futures Rookie Challenge");
            $table->double('buying_power')->comment("$60,000");
            $table->double('price')->comment("$145,$165");
            $table->boolean('is_feature')->default(0)->comment("1=as feature,0=non feature");
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
        Schema::dropIfExists('card_challenges');
    }
}
