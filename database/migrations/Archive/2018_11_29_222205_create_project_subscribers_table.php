<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_subscribers', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('project_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->primary('project_id', 'user_id');

            $table->index('user_id', 'fk_subscr_user_idx');

            $table->foreign('project_id')
                ->references('project_id')->on('projects');

            $table->foreign('user_id')
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
        Schema::dropIfExists('project_subscribers');
    }
}
