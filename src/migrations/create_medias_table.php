<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediasTable extends Migration
{
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->morphs('mediable');
            $table->string('collection');
            $table->integer('position')->unsigned()->default(0);
            $table->string('filepath');
            $table->string('filename');
            $table->string('title');
            $table->string('type');
            $table->string('mime');
            $table->string('extension');
            $table->integer('size');
            $table->integer('width');
            $table->integer('height');
        });
    }

    public function down()
    {
        Schema::dropIfExists('medias');
    }
}
