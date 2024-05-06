<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnProjectTags extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('tags');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('tags', 500)->nullable();
        });
    }
}
