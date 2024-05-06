<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableUserReports extends Migration
{
    public function up()
    {
        Schema::table('user_reports', function (Blueprint $table) {
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('user_reports', function (Blueprint $table) {
            $table->dropColumn('reason');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
