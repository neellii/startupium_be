<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carrers', function (Blueprint $table) {
            $table->dropUnique(['duty']);
            $table->dropUnique(['position']);
            $table->dropUnique(['company']);
        });
    }

    public function down()
    {
        Schema::table('carrers', function (Blueprint $table) {
            $table->unique(['duty']);
            $table->unique(['position']);
            $table->unique(['company']);
        });
    }
};
