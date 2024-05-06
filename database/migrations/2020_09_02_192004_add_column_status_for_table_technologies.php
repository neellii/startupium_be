<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStatusForTableTechnologies extends Migration
{
    public function up()
    {
        Schema::table('technologies', function (Blueprint $table) {
            $table->string('status')->nullable();
        });
    }

    public function down()
    {
        Schema::table('technologies', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
