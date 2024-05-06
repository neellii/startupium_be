<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProjectSubscribersRefs extends Migration
{
    public function up()
    {
        Schema::create('project_subscribers_refs', function (Blueprint $table) {
            $table->integer('project_id')->unsigned();
            $table->integer('subscriber_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
            $table->foreign('subscriber_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->primary(['project_id', 'subscriber_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_subscribers_refs');
    }
}
