<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnShowMessages extends Migration
{
    public function up()
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->string('showMessages')->default(true)->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->string('showMessages')->default(false)->nullable(false)->change();
        });
    }
}
