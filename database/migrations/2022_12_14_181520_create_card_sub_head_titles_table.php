<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardSubHeadTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_sub_head_titles', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('card_head_title_id');
            $table->foreign('card_head_title_id')->references('id')->on('card_head_titles')->onDelete('cascade');
            $table->tinyText('value')->comment("115,1:200,7500")->nullable();
            $table->string('title')->comment("Standard,Phase 1,Open Trades")->nullable();
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
        Schema::dropIfExists('card_sub_head_titles');
    }
}
