<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStatusForTableSkills extends Migration
{
    public function up()
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->string('status')->nullable();
        });
    }

    public function down()
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
