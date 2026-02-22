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
    ];

    protected $casts = [
        'industrial_training_required' => 'boolean',
        'programme_status' => 'boolean',
        'programme_start_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships (For Future Expansion)
    |--------------------------------------------------------------------------
    */

    public function levels()
    {
        return $this->hasMany(Level::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

        public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
