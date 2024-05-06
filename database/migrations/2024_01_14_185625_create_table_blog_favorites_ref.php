<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_favorites_ref', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->uuid('blog_id')->nullable();
            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->primary(['user_id', 'blog_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_favorites_ref');
    }
};
