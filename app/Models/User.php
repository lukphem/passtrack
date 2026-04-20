<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'password',
        'matric_no',
        'staff_no',
        'role',
        'status',
        'mode_of_admission',
        'entry_level',
        'admission_year',
        'level',
        'programme_id',
        'department_id',
        'profile_photo',
        'access_level',
    ];

    protected $hidden = ['password', 'remember_token'];

        public function lecturer()
    {
        return $this->hasOne(Lecturer::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

     public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
