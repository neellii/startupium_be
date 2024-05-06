<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_complaints_ref', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->unsignedBigInteger('comment_id');
            $table->integer('complaint_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('comment_id')->references('id')->on('project_comments')->onDelete('CASCADE');
            $table->foreign('complaint_id')->references('id')->on('complaints')->onDelete('CASCADE');
            $table->primary(['user_id', 'complaint_id', 'comment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_complaints_ref');
    }
};
