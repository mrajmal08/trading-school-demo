<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_heads', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('card_challenge_id');
            $table->foreign('card_challenge_id')->references('id')->on('card_challenges')->onDelete('cascade');
            $table->foreignId('card_head_title_id');
            $table->foreign('card_head_title_id')->references('id')->on('card_head_titles')->onDelete('cascade');
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
        Schema::dropIfExists('card_heads');
    }
}
