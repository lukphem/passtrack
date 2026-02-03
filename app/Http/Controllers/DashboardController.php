<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Course;
use Illuminate\Support\Facades\DB;





class DashboardController extends Controller
{
    /**
     * Main Admin Dashboard
     */
    public function index()
    {
        return view('admin.dashboard.dashboard', [
            'totalUsers'       => User::count(),
            'totalFaculties'   => Faculty::count(),
            'totalDepartments' => Department::count(),
            'totalCourses'     => Course::count(),
        ]);
    }

    /**
     * Summary stats (AJAX / Chart.js / cards refresh)
     */
    public function stats()
    {
        return response()->json([
            'users'       => User::count(),
            'faculties'   => Faculty::count(),
            'departments' => Department::count(),
            'courses'     => Course::count(),
        ]);
    }

    /**
     * Students / Courses distribution per Faculty
     */
    public function enrollmentChart()
    {
        $data = Faculty::withCount('departments')->get();

        return response()->json([
            'labels' => $data->pluck('name'),
            'data'   => $data->pluck('departments_count'),
        ]);
    }

    /**
     * Users by role (Admin, Lecturer, Student)
     */
    public function userChart()
    {
        $users = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get();

        return response()->json([
            'labels' => $users->pluck('role'),
            'data'   => $users->pluck('total'),
        ]);
    }
}

