<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectFavoritesTable extends Migration
{
    public function up()
    {
        Schema::create('project_favorites', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
            $table->primary(['user_id', 'project_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_favorites');
    }
}
