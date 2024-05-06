<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('showCommentsAnswer')->default(false)->nullable(false);
            $table->boolean('showComments')->default(false)->nullable(false);
            $table->boolean('showLikes')->default(false)->nullable(false);
            $table->boolean('showPublicProjects')->default(false)->nullable(false);
            $table->boolean('showRejectProjects')->default(false)->nullable(false);
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
        Schema::dropIfExists('notification_settings');
    }
}
