<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curator_id');
            $table->string('slug');
            $table->string('game');
            $table->string('broadcast_id');
            $table->string('title');
            $table->integer('views_at_import');
            $table->integer('duration');
            $table->string('thumbnail_medium');
            $table->string('thumbnail_small');
            $table->string('thumbnail_tiny');
            $table->string('video_file_path');
            $table->timestamps();
            $table->foreign('curator_id')->references('id')->on('curators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clips');
    }
}
