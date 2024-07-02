<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_records', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->dateTime('trade_time')->nullable();
            $table->string('account')->nullable();
            $table->string('symbol')->nullable();
            $table->string('balance')->nullable();
            $table->string('sale')->nullable();
            $table->double('quantity')->nullable();
            $table->double('price')->nullable();
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
        Schema::dropIfExists('trade_records');
    }
}
