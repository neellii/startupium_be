<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('require_teams_ref', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->nullable(false);
        });
    }

    public function down(): void
    {
        Schema::table('require_teams_ref', function (Blueprint $table) {
            $table->dropColumn("is_hidden");
        });
    }
};
