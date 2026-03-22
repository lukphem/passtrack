<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Programme extends Model

{


    protected $dates = ['deleted_at'];
    protected $table = 'programmes';

    protected $fillable = [
        'programme_name',
        'programme_code',
        'programme_duration',
        'industrial_training_required',
        'industrial_training_level',
        'programme_description',
        'programme_level_type',
        'programme_start_date',
        'accreditation_status',
        'accreditation_year',
        'programme_status',
        'department_id',
        'use_custom_academic_settings'
    ];

    protected $casts = [
        'industrial_training_required' => 'boolean',
        'programme_status' => 'boolean',
        'programme_start_date' => 'date',
        'use_custom_academic_settings' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships (For Future Expansion)
    |--------------------------------------------------------------------------
    */

    // public function levels()
    // {
    //     return $this->hasMany(Level::class);
    // }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

        public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function academicSetting()
    {
        return $this->hasOne(ProgrammeAcademicSetting::class);
    }

        public function currentSession()
    {
        return $this->belongsTo(AcademicSession::class, 'current_session_id');
    }

    public function currentSemester()
    {
        return $this->belongsTo(Semester::class, 'current_semester_id');
    }

}
