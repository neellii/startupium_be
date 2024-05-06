<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wiki_articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->uuid('section_id')->nullable();
            $table->foreign('section_id')->references('id')->on('wiki_sections')->onDelete('cascade');
            $table->text('title')->nullable()->default(null);
            $table->text('text')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wiki_articles');
    }
};
