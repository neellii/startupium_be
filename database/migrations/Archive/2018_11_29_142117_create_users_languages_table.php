<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_languages', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            // Fields
            $table->integer('user_id')->unsigned();
            $table->integer('language_id')->unsigned();
            $table->enum('language_level', ['1',  '2'])->nullable()->default(null);

            $table->primary('user_id', 'language_id');

            $table->index('language_id', 'fk_userslang_lang_idx');

            $table->foreign('language_id')
                ->references('language_id')->on('languages');

            $table->foreign('user_id')
                ->references('id')->on('users');

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
        Schema::dropIfExists('users_languages');
    }
}
