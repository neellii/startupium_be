<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            // Fields
            $table->increments('project_id');
            $table->string('description', 1000)->nullable()->default(null);
            $table->integer('project_type_id')->unsigned()->nullable();
            $table->string('project_rate', 45);
            $table->dateTime('project_date_created');
            $table->dateTime('project_date_updated')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('project_scope_id')->unsigned()->nullable()->default(null);
            $table->integer('project_author_id')->unsigned()->nullable()->default(null);
            $table->string('project_stage', 45)->nullable()->default(null);
            $table->integer('project_is_active')->default(1);

            $table->index('project_scope_id', 'fk_project_prscope_idx');
            $table->index('project_type_id', 'fk_project_prtype_idx');
            $table->index('project_author_id', 'fk_project_users_idx');

            $table->foreign('project_scope_id')
                ->references('project_scope_id')->on('projects_scope');

            $table->foreign('project_type_id')
                ->references('project_type_id')->on('projects_type');

            $table->foreign('project_author_id')
                ->references('id')->on('users');

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
        Schema::dropIfExists('projects');
    }
}
