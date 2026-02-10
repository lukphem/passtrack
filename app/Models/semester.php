<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Semester extends Model
{
    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'academic_session_id',
        'semester_name',
        'start_date',
        'end_date',
        'registration_start_date',
        'registration_end_date',
        'is_active',
        'registration_allowed',
    ];

    /**
     * Attribute casting
     * Ensures dates are Carbon instances and booleans are normalized
     */
    protected $casts = [
        'start_date'              => 'date',
        'end_date'                => 'date',
        'registration_start_date' => 'datetime',
        'registration_end_date'   => 'datetime',
        'is_active'               => 'boolean',
        'registration_allowed'    => 'boolean',
    ];

    /**
     * Relationship: Semester belongs to an Academic Session
     */
    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    /**
     * Check if course registration is currently open
     */
    public function isCourseRegistrationOpen(): bool
    {
        if (!$this->is_active || !$this->registration_allowed) {
            return false;
        }

        if (!$this->registration_start_date || !$this->registration_end_date) {
            return false;
        }

        return now()->between(
            $this->registration_start_date,
            $this->registration_end_date
        );
    }

    /**
     * Scope: Get active semester
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Accessor: Semester duration for UI display
     */
    public function getDurationAttribute(): string
    {
        return sprintf(
            '%s - %s',
            $this->start_date->format('M d'),
            $this->end_date->format('M d, Y')
        );
    }

    /**
     * Boot method to enforce business rules
     */
    protected static function booted()
    {
        static::saving(function ($semester) {

            // Ensure only one active semester per academic session
            if ($semester->is_active) {
                static::where('academic_session_id', $semester->academic_session_id)
                    ->where('id', '!=', $semester->id)
                    ->update(['is_active' => false]);
            }

            // Automatically disable registration if dates are missing
            if (
                !$semester->registration_start_date ||
                !$semester->registration_end_date
            ) {
                $semester->registration_allowed = false;
            }
        });
    }
}
