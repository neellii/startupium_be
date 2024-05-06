<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTagsRef extends Migration
{
    public function up()
    {
        Schema::create('tags_ref', function (Blueprint $table) {
            $table->integer('project_id')->unsigned();
            $table->bigInteger('tag_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('CASCADE');
            $table->primary(['project_id', 'tag_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tags_ref');
    }
}
