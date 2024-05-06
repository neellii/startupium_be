<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableSendByMailSettings extends Migration
{
    public function up()
    {
        Schema::table('send_by_email_settings', function (Blueprint $table) {
            $table->boolean('newMessage')->default(false)->nullable(false);
        });
    }

    public function down()
    {
        Schema::table('send_by_email_settings', function (Blueprint $table) {
            $table->dropColumn('newMessage');
        });
    }
}
