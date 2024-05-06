<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMessageReport extends Migration
{
    public function up()
    {
        Schema::create('message_report', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->bigInteger('message_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('CASCADE');
            $table->string('reason')->nullable();
            $table->primary(['user_id', 'message_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('message_report');
    }
}
