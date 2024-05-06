<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_complaints_ref', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->integer('complaint_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
            $table->foreign('complaint_id')->references('id')->on('complaints')->onDelete('CASCADE');
            $table->primary(['user_id', 'project_id', 'complaint_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_complaints_ref');
    }
};
