<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tracer_study_additional_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pending_response_id')->constrained('pending_responses')->onDelete('cascade');
            $table->json('additional_data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tracer_study_additional_answers');
    }
};
