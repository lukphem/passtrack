<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // Link to users table (login account)
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Link to departments table
            $table->foreignId('department_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Academic Information
            $table->string('matric_number')->unique();
            $table->integer('level'); // 100, 200, 300, 400
            $table->string('programme')->nullable(); // BSc Computer Science
            $table->year('admission_year');
            $table->year('graduation_year')->nullable();

            // Status
            $table->enum('status', ['active', 'graduated', 'suspended', 'withdrawn'])
                  ->default('active');

            // Optional Extra Info
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
