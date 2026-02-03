<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'dean',
        'established_year',
        'description',
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
        return $this->hasManyThrough(User::class, Department::class);
    }
}
