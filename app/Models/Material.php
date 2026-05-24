<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
        protected $fillable = [
            'course_id',
            'lecturer_id',
            'academic_session_id',
            'title',
            'description',
            'type',
            'week',
            'file_path',
            'file_type',
            'file_size',
            'external_link',
            'visibility',
            'semester_id',
        ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session_id');
    }

        public function semester()
    {
        return $this->belongsTo(Semester::class, 'academic_session_id');
    }
}
