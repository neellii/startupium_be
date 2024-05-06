<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wiki_sections', function (Blueprint $table) {
            $table->integer('nesting')->nullable()->default(0);
            $table->uuid('parent_id')->nullable()->after('project_id');
            $table->foreign('parent_id')->references('id')->on('wiki_sections')->onDelete('cascade');
            $table->string('type', 10)->nullable()->after('parent_id');
        });
    }

    public function down(): void
    {
        Schema::table('wiki_sections', function (Blueprint $table) {
            $table->dropColumn('nesting');
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->dropColumn('type');
        });
    }
};
