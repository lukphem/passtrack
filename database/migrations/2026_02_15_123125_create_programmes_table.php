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
    Schema::create('programmes', function (Blueprint $table) {
        $table->id();
        $table->string('programme_name'); // Example: Bachelor of Science in Computer Science
        $table->string('programme_code')->unique(); // Example: CSC, ACC, BUS
        $table->unsignedInteger('programme_duration');// Duration in years
        $table->boolean('industrial_training_required')->default(false);
        $table->unsignedSmallInteger('industrial_training_level')->nullable();
        $table->text('programme_description')->nullable(); // Optional detailed info about the programme
        $table->string('programme_level_type')->nullable();// Undergraduate, Postgraduate
        $table->date('programme_start_date')->nullable();
        $table->string('accreditation_status')->nullable(); // Full, Interim, None
        $table->year('accreditation_year')->nullable();
        $table->boolean('programme_status')->default(true);// Active or inactive
                $table->foreignId('department_id')
              ->nullable()
              ->constrained('departments')
              ->onDelete('cascade');
        $table->timestamps();
        $table->softDeletes();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
