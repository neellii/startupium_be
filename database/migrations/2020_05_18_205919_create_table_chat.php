<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableChat extends Migration
{
    public function up()
    {
        Schema::create('chat_with', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('companion_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('companion_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->primary(['user_id', 'companion_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_with');
    }
}
