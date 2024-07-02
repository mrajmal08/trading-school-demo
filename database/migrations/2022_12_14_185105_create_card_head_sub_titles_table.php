<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardHeadSubTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_head_sub_titles', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('card_head_id');
            $table->foreign('card_head_id')->references('id')->on('card_heads')->onDelete('cascade');
            $table->foreignId('card_sub_head_title_id')->nullable();
            $table->foreign('card_sub_head_title_id')->references('id')->on('card_sub_head_titles')->onDelete('cascade')->nullable();
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
        Schema::dropIfExists('card_head_sub_titles');
    }
}
