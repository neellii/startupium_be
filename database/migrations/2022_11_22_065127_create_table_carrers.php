<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carrers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company')->unique()->nullable();
            $table->string('position')->unique()->nullable();
            $table->string('duty')->unique()->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->dateTime('start_date_at')->nullable();
            $table->dateTime('last_date_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrers');
    }
};
