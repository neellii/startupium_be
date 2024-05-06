<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableNotificationSettings extends Migration
{
    public function up()
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->boolean('showMessages')->default(false)->nullable(false);
            $table->boolean('showBookmarks')->default(false)->nullable(false);
            $table->boolean('showReports')->default(false)->nullable(false);
        });
    }

    public function down()
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->dropColumn('showMessages');
            $table->dropColumn('showBookmarks');
            $table->dropColumn('showReports');
        });
    }
}
