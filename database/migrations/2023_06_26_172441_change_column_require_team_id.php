<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_subscribers_refs', function (Blueprint $table) {
            $table->unsignedInteger('require_team_id')->nullable()->change();
        });
    }


    public function down(): void
    {
        Schema::table('project_subscribers_refs', function (Blueprint $table) {
            $table->integer('require_team_id')->unsigned();
        });
    }
};
