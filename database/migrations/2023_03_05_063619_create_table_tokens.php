<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('access_token_id')->nullable();
            $table->foreign('access_token_id')->references('id')->on('oauth_access_tokens')->onDelete('cascade');
            $table->text('refresh_token')->nullable();
            $table->timestamps();
            $table->timestamp('expires_at')->nullable()->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tokens');
    }
};
