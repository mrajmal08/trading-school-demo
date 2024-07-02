<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengeStripesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenge_stripes', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('card_challenge_id');
            $table->foreign('card_challenge_id')->references('id')->on('card_challenges')->onDelete('cascade');
            $table->string('stripe_product_id');
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
        Schema::dropIfExists('challenge_stripes');
    }
}
