<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_skills', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('user_id')->unsigned();
            $table->integer('skill_id')->unsigned();
            $table->integer('haveskill')->nullable()->default(null);

            $table->primary('user_id', 'skill_id');

            $table->index('skill_id', 'fk_userskills_skills_idx');

            $table->foreign('skill_id')
                ->references('skill_id')->on('skills');

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
        Schema::dropIfExists('users_skills');
    }
}
