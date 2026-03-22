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
    'credit_unit',
    'level',
    'semester',
    'course_type',
    'status',
];


    public function department()
    {
        return $this->belongsTo(Department::class);
    }

        public function programmes()
    {
        return $this->belongsToMany(Programme::class);
    }
}

