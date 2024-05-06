<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_subscribers_refs', function (Blueprint $table) {
            $table->integer('require_team_id')->unsigned();
            $table->foreign('require_team_id')->references('id')->on('require_teams')->onDelete('CASCADE');
            $table->timestamp('subscribed_at')->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('project_subscribers_refs', function (Blueprint $table) {
            $table->dropForeign(['require_team_id']);
            $table->dropColumn('require_team_id');
            $table->dropColumn('subscribed_at');
        });
    }
};
