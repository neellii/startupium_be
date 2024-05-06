<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_role_in_project', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('mentor')->default(false)->nullable(false);
            $table->boolean('investor')->default(false)->nullable(false);
            $table->boolean('trainee')->default(false)->nullable(false);
            $table->boolean('seeker')->default(false)->nullable(false);
            $table->boolean('founder')->default(false)->nullable(false);
            $table->integer('user_id')->unsigned()->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_role_in_project');
    }
};
