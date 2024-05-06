<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 500);
            $table->text('description', 500)->nullable()->default(null);
            $table->json('text', 10000)->nullable()->default(null);
            $table->enum('status', ['draft', 'published', 'deleted', 'awaiting', 'rejected']);
            $table->integer('user_id')->unsigned();
            $table->json('tags', 500)->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('projects');
    }
}
