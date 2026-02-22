<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            // Foreign Key to Users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Basic Info
            $table->string('title')->nullable(); // e.g., Dr., Prof., Mr.
            $table->string('gender');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('staff_id');
            // Academic/Work Info
            $table->foreignId('faculty_id')->constrained();
            $table->foreignId('department_id')->constrained();
            $table->string('specialization')->nullable();
            $table->string('rank'); // e.g., Senior Lecturer, Associate Prof
            $table->string('employment_type'); // e.g., Full-time, Part-time
            $table->string('staff_category');

            // Performance Metrics
            $table->decimal('attendance_rate', 5, 2)->default(0);
            $table->integer('classes_held')->default(0);
            $table->integer('classes_attended')->default(0);
            $table->decimal('student_feedback_score', 3, 2)->default(0);

            // Status
            $table->string('status')->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
