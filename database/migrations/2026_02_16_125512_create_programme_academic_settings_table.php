<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('programme_academic_settings', function (Blueprint $table) {
        $table->id();

        // Foreign key to programmes
        $table->foreignId('programme_id')->constrained('programmes')->onDelete('cascade');
        // Foreign key to academic_sessions
        $table->foreignId('academic_session_id')->constrained('academic_sessions')->onDelete('cascade');
        // Foreign key to semesters
        $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
        // Academic settings
        $table->boolean('registration_allowed')->default(false);
        $table->date('start_date');
        $table->date('end_date');
        $table->dateTime('registration_start_date')->nullable();
        $table->dateTime('registration_end_date')->nullable();
        // Audit fields
        $table->foreignId('created_by')->nullable()->constrained('users');
        $table->foreignId('updated_by')->nullable()->constrained('users');
        $table->softDeletes();
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programme_academic_settings');
    }
};
