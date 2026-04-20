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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('programme_id')->nullable()->constrained()->nullOnDelete();
            // Academic Info
            $table->string('matric_no')->unique();
            $table->enum('mode_of_admission', ['UTME','Direct Entry','Transfer','Pre-degree','Post-degree','Others'])->nullable();
            $table->integer('entry_level')->nullable();
            $table->integer('level')->nullable();
            $table->string('admission_session')->nullable();   // e.g. 2023/2024
            $table->string('graduation_session')->nullable();
            $table->enum('status', ['active', 'graduated', 'suspended', 'withdrawn'])->default('active');
            //  Personal Info
            $table->string('phone')->nullable();
            $table->enum('gender', ['male','female','other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('address')->nullable();
            $table->string('state_of_origin')->nullable();
            $table->string('lga_of_origin')->nullable();
            $table->string('nationality')->nullable();
            $table->string('profile_photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
