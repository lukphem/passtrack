<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'programme_id',
        'matric_no',
        'mode_of_admission',
        'entry_level',
        'level',
        'admission_session',
        'status',
        'phone',
        'gender',
        'date_of_birth',
        'address',
        'state_of_origin',
        'lga_of_origin',
        'nationality',
        'profile_photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}
