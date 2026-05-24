<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicSession extends Model
{
    use HasFactory;
     use SoftDeletes;

    protected $fillable = [
        'session_name',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
        'registration_start_date' => 'datetime',
        'registration_end_date' => 'datetime',
    ];

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

        public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
