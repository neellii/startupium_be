<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('require_teams_ref', function (Blueprint $table) {
            $table->integer('project_id')->unsigned();
            $table->integer('require_team_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
            $table->foreign('require_team_id')->references('id')->on('require_teams')->onDelete('CASCADE');
            $table->primary(['project_id', 'require_team_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('require_teams_ref');
    }
};
