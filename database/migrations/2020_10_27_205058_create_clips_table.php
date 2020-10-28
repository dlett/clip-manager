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
            $table->foreignId('broadcaster_id');
            $table->string('slug');
            $table->string('game');
            $table->string('broadcast_id');
            $table->string('title');
            $table->integer('views_at_import');
            $table->integer('duration');
            $table->string('thumbnail_medium')->nullable();
            $table->string('thumbnail_small')->nullable();
            $table->string('thumbnail_tiny')->nullable();
            $table->string('video_file_path');
            $table->string('video_file_disk');
            $table->timestamps();
            $table->foreign('curator_id')->references('id')->on('curators');
            $table->foreign('broadcaster_id')->references('id')->on('broadcasters');
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
