<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->string('alias');
            $table->text('page_title');
            $table->text('description');
            $table->text('content');
            $table->text('meta_name')->nullable();
            $table->text('meta_content')->nullable();
            $table->text('keywords')->nullable();
            $table->string('image')->nullable();
            $table->string('image_thumb')->nullable();
            $table->boolean('active');
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
        Schema::dropIfExists('pages');
    }
}
