<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->string('city', 250)->change();
            $table->renameColumn('city', 'title');
            $table->unsignedInteger('region_id')->nullable()->after('country_id');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('CASCADE');
        });
    }

    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropColumn('region_id');
            $table->renameColumn('title', 'city');
        });
    }
};
