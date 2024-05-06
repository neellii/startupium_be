<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects_tags', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('project_id')->unsigned();
            $table->integer('project_tag_id')->unsigned();

            $table->primary('project_id', 'project_tag_id');

            $table->index('project_tag_id', 'fk_projecttags_tags_idx');

            $table->foreign('project_id')
                ->references('project_id')->on('projects');

            $table->foreign('project_tag_id')
                ->references('tag_id')->on('tags');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects_tags');
    }
}
