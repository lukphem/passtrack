<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'gender',
        'first_name',
        'last_name',
        'middle_name',
        'phone',
        'staff_id',
        'faculty_id',
        'department_id',
        'specialization',
        'rank',
        'employment_type',
        'staff_category',
        'attendance_rate',
        'student_feedback_score',
        'status'
    ];

    // Relation to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation to Faculty
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    // Relation to Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
