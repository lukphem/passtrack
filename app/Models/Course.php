<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['code', 'title', 'department_id', 'semester'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}

