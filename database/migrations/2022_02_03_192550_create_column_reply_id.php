<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColumnReplyId extends Migration
{
    public function up()
    {
        Schema::table('project_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('reply_id')->nullable();
            $table->foreign('reply_id')->references('id')->on('project_comments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('project_comments', function (Blueprint $table) {
            $table->dropForeign(['reply_id']);
            $table->dropColumn('reply_id');
        });
    }
}
