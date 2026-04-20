<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Department;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('student.dashboard.index', [
            'student' => $user,

            // future-ready placeholders
            'totalCourses' => Course::count(),
            'departments'  => Department::count(),

            // you will connect later:
            'enrolledCourses' => 0,
            'attendanceRate'  => 0,
            'cgpa'            => 0.00,
        ]);
    }
}
