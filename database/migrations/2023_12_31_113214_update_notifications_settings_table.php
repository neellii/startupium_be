<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->boolean('showCommentsAnswer')->default(true)->nullable(false)->change();
            $table->boolean('showComments')->default(true)->nullable(false)->change();
            $table->boolean('showLikes')->default(true)->nullable(false)->change();
            $table->boolean('showPublicProjects')->default(true)->nullable(false)->change();
            $table->boolean('showRejectProjects')->default(true)->nullable(false)->change();
            $table->boolean('showMessages')->default(true)->nullable(false)->change();
            $table->boolean('showBookmarks')->default(true)->nullable(false)->change();
            $table->boolean('showReports')->default(true)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->boolean('showCommentsAnswer')->default(false)->nullable(false)->change();
            $table->boolean('showComments')->default(false)->nullable(false)->change();
            $table->boolean('showLikes')->default(false)->nullable(false)->change();
            $table->boolean('showPublicProjects')->default(false)->nullable(false)->change();
            $table->boolean('showRejectProjects')->default(false)->nullable(false)->change();
            $table->boolean('showMessages')->default(false)->nullable(false)->change();
            $table->boolean('showBookmarks')->default(false)->nullable(false)->change();
            $table->boolean('showReports')->default(false)->nullable(false)->change();
        });
    }
};
