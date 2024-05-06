<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_to_subjects_ref', function (Blueprint $table) {
            $table->uuid('blog_id')->nullable();
            $table->integer('blog_subject_id')->unsigned();
            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
            $table->foreign('blog_subject_id')->references('id')->on('blog_subjects')->onDelete('CASCADE');
            $table->primary(['blog_id', 'blog_subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_to_subjects_ref');
    }
};
