<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('qualities_ref', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('quality_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('quality_id')->references('id')->on('qualities')->onDelete('CASCADE');
            $table->primary(['user_id', 'quality_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('qualities_ref');
    }
};
