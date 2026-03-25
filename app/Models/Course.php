<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'course_code',
    'course_title',
    'course_description',
    'level',
    'semester',
    'credit_unit',
    'course_type',
    'status',
    'department_id',
    'lecturer_id',
];


    public function department()
    {
        return $this->belongsTo(Department::class);
    }

        public function programmes()
    {
        return $this->belongsToMany(Programme::class);
    }
    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }




    
}

