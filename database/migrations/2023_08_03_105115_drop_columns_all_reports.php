<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('message_report');
        Schema::dropIfExists('comment_report');
        Schema::dropIfExists('user_reports');
    }

    public function down(): void
    {
        //
    }
};
