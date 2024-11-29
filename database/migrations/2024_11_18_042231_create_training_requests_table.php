<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_requests', function (Blueprint $table) {
            $table->id('training_request_id');
            $table->string('status');
            $table->json('approves');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('job_vacancy_id');
            $table->unsignedBigInteger('college_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('institution_id');
            $table->timestamps();
        
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('job_vacancy_id')->references('job_vacancy_id')->on('job_vacancies')->onDelete('cascade');
            $table->foreign('college_id')->references('college_id')->on('colleges')->onDelete('cascade');
            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('cascade');
            $table->foreign('institution_id')->references('institution_id')->on('institutions')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_requests');
    }
};
