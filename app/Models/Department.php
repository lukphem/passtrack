<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
     use SoftDeletes;

    protected $fillable = [
        'dept_name',
        'dept_code',
        'description',
        'head_of_department',
        'established_year',
        'faculty_id',
        'status',
    ];


    /* =====================
        RELATIONSHIPS
    ====================== */

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

        public function programmes()
    {
        return $this->hasMany(Programme::class);
    }

}
