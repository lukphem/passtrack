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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('lecturer_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('semester_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();

            $table->string('type')->default('lecture_note');
            $table->integer('week')->nullable();

            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_size')->nullable();

            $table->string('external_link')->nullable();

            $table->enum('visibility', ['draft', 'published', 'hidden'])->default('published');
            $table->integer('download_count')->default(0);

            $table->timestamp('available_from')->nullable();
            $table->timestamp('available_to')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
