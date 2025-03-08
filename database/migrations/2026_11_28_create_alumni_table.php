<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->year('year_graduated');
            $table->integer('age')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('marital_status')->nullable();
            $table->string('current_location')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('degree_program');
            $table->string('major')->nullable();
            $table->string('minor')->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->string('employment_status')->nullable();
            $table->string('job_title')->nullable();
            $table->string('company')->nullable();
            $table->string('industry')->nullable();
            $table->string('nature_of_work')->nullable();
            $table->string('employment_sector')->nullable();
            $table->string('tenure_status')->nullable();
            $table->decimal('monthly_salary', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumni');
    }
};