<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTechnologyTableRefs extends Migration
{
    public function up()
    {
        Schema::create('user_technology_ref', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('technology_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('technology_id')->references('id')->on('technologies')->onDelete('CASCADE');
            $table->primary(['user_id', 'technology_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_technology_ref');
    }
}
