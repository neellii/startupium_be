<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('default_article_ref', function (Blueprint $table) {
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
            $table->uuid('article_id')->nullable();
            $table->foreign('article_id')->references('id')->on('wiki_articles')->onDelete('cascade');
            $table->primary(['project_id', 'article_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('default_article_ref');
    }
};
