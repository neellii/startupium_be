<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsSpecialityNeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams_speciality_needs', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('team_id')->unsigned();
            $table->integer('speciality_id')->unsigned();

            $table->primary('team_id', 'speciality_id');

            $table->index('speciality_id', 'fk_specialityneeds_speciality_idx');

            $table->foreign('speciality_id')
                ->references('speciality_id')->on('speciality');

            $table->foreign('team_id')
                ->references('team_id')->on('teams');

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
        Schema::dropIfExists('teams_speciality_needs');
    }
}
