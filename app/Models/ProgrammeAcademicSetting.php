<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeAcademicSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'programme_id',
        'academic_session_id',
        'semester_id',
        'registration_allowed',
        'start_date',
        'end_date',
        'registration_start_date',
        'registration_end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
        'registration_start_date' => 'datetime',
        'registration_end_date' => 'datetime',
    ];

    // Relationship to Programme
    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    // Relationship to Academic Session
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    // Relationship to Semester
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

}
