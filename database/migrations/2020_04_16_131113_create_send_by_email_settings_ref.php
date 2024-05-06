<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendByEmailSettingsRef extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_by_email_settings_ref', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('send_email_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('send_email_id')->references('id')->on('send_by_email_settings')->onDelete('CASCADE');
            $table->primary(['user_id', 'send_email_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('send_by_email_settings_ref');
    }
}
