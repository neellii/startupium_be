<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('user_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->integer('speciality_id')->unsigned()->nullable()->default(null);

            $table->primary('user_id', 'team_id');

            $table->index('team_id', 'fk_teamsuser_teams_idx');
            $table->index('speciality_id', 'fk_teamuser_speciality_idx');

            $table->foreign('team_id')
                ->references('team_id')->on('teams');

            $table->foreign('user_id')
                ->references('id')->on('users');

            $table->foreign('speciality_id')
                ->references('speciality_id')->on('speciality');

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
        Schema::dropIfExists('team_users');
    }
}
