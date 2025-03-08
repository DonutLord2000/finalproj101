<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('thread_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['upvote', 'heart']);
            $table->timestamps();

            $table->unique(['user_id', 'thread_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reactions');
    }
};