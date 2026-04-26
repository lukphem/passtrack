<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();

            // ================= STUDENT =================
            $table->foreignId('student_id')
                ->constrained()
                ->onDelete('cascade');

            // ================= COURSE =================
            $table->foreignId('course_id')
                ->constrained()
                ->onDelete('cascade');

            // ================= ACADEMIC CONTEXT =================
            $table->foreignId('session_id')
                ->constrained('academic_sessions')
                ->onDelete('cascade');

            $table->foreignId('semester_id')
                ->constrained()
                ->onDelete('cascade');

            // ================= SNAPSHOT (IMPORTANT FOR HISTORY) =================
            $table->unsignedInteger('course_level'); // 100, 200, etc
            $table->string('course_type'); // Core / Elective (snapshot)

            // ================= REGISTRATION STATUS =================
            $table->enum('status', ['registered', 'dropped'])
                ->default('registered');

            $table->boolean('is_carry_over')->default(false);

            // ================= TIMESTAMPS =================
            $table->timestamps();

            // ================= UNIQUE CONSTRAINT =================
            $table->unique(
                ['student_id', 'course_id', 'session_id', 'semester_id'],
                'course_student_unique'
            );

            // Optional: faster queries
            $table->index(['student_id', 'session_id']);
            $table->index(['course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_student');
    }
};
