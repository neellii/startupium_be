<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendByEmailSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_by_email_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('commentAnswer')->default(false)->nullable(false);
            $table->boolean('likeProject')->default(false)->nullable(false);
            $table->boolean('popularProjects')->default(false)->nullable(false);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
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
        Schema::dropIfExists('send_by_email_settings');
    }
}
