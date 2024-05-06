<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSkillTableRefs extends Migration
{
    public function up()
    {
        Schema::create('user_skills_ref', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('skill_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('CASCADE');
            $table->primary(['user_id', 'skill_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_skills_ref');
    }
}
