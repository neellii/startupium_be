<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateSettingsRef extends Migration
{
    public function up()
    {
        Schema::create('private_settings_ref', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('pri_set_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('pri_set_id')->references('id')->on('private_settings')->onDelete('CASCADE');
            $table->primary(['user_id', 'pri_set_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('private_settings_ref');
    }
}
