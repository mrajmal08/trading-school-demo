<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_details', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('blog_category_id');
            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->longText('detail')->nullable();
            $table->string('picture')->nullable();
            $table->date('date')->nullable();
            $table->boolean('publish')->nullable()->comment("1=publish ; 0=unpublish");
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
        Schema::dropIfExists('blog_details');
    }
}
