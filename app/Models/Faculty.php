<?php

namespace App\Models;

use App\Models\Department;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'faculty_name',
        'faculty_code',
        'dean',
        'established_year',
        'description',
        'status',
    ];

    /**
     * Faculty has many departments
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Faculty has many students through departments
     */
    public function students()
    {
        return $this->hasManyThrough(
            Student::class,     // Final model
            Department::class,  // Intermediate model
            'faculty_id',       // Foreign key on departments table
            'department_id',    // Foreign key on students table
            'id',               // Local key on faculties table
            'id'                // Local key on departments table
        );
    }
}
