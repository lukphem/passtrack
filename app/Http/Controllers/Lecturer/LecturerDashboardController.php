<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('lecturer.dashboard.index', [
            'lecturer' => $user,

            // placeholders (replace with real logic later)
            'coursesCount'   => 0,
            'studentsCount'  => 0,
            'attendanceRate' => 0,
            'reportsCount'   => 0,
        ]);
    }
}
