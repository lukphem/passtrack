<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Course;
use App\Models\Programme;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Exports\LecturerStudentsExport;
use Maatwebsite\Excel\Facades\Excel;

class LecturerStudentController extends Controller
{

  public function index(Request $request)
{
    $lecturer = auth()->user()->lecturer;

    $activeSession = AcademicSession::where('is_active', true)->first();
    $activeSemester = Semester::where('is_active', true)->first();

    $courses = Course::where('lecturer_id', $lecturer->id)
        ->orderBy('course_code')
        ->get();

    $sessionId = $request->session_id ?? $activeSession?->id;
    $semesterId = $request->semester_id ?? $activeSemester?->id;
    $courseId = $request->course_id;

    $query = \DB::table('coursereg_student')
        ->join('students', 'students.id', '=', 'coursereg_student.student_id')
        ->join('users', 'users.id', '=', 'students.user_id')
        ->join('courses', 'courses.id', '=', 'coursereg_student.course_id')
        ->leftJoin('programmes', 'programmes.id', '=', 'students.programme_id')

        ->where('courses.lecturer_id', $lecturer->id)

        ->when($courseId, fn($q) =>
            $q->where('coursereg_student.course_id', $courseId)
        )

        ->when($sessionId, fn($q) =>
            $q->where('coursereg_student.session_id', $sessionId)
        )

        ->when($semesterId, fn($q) =>
            $q->where('coursereg_student.semester_id', $semesterId)
        )

        ->when($request->programme_id, fn($q) =>
            $q->where('students.programme_id', $request->programme_id)
        )

        ->when($request->level, fn($q) =>
            $q->where('students.level', $request->level)
        )

        ->when($request->search, function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('students.matric_no', 'like', "%{$request->search}%")
                    ->orWhere('users.first_name', 'like', "%{$request->search}%")
                    ->orWhere('users.last_name', 'like', "%{$request->search}%");
            });
        })

        ->select(
            'students.id',
            'students.matric_no',
            'students.level',
            'users.first_name',
            'users.last_name',
            'programmes.programme_name',

            // 🔥 KEY FIX: merge courses per student
            \DB::raw("GROUP_CONCAT(DISTINCT courses.course_code SEPARATOR ', ') as courses"),

            \DB::raw("GROUP_CONCAT(DISTINCT courses.course_title SEPARATOR ', ') as course_titles")
        )

        ->groupBy(
            'students.id',
            'students.matric_no',
            'students.level',
            'users.first_name',
            'users.last_name',
            'programmes.programme_name'
        )

        ->paginate(20)
        ->withQueryString();

    return view('lecturer.students.index', [
        'students' => $query,
        'courses' => $courses,
        'activeSession' => $activeSession,
        'activeSemester' => $activeSemester,
        'programmes' => Programme::all(),
        'sessions' => AcademicSession::all(),
        'semesters' => Semester::all(),
    ]);
}

    public function export(Request $request)
    {
        return Excel::download(
            new LecturerStudentsExport($request->all()),
            'lecturer_students_' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}
