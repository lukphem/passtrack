<?php

namespace App\Http\Controllers\Lecturer;

use App\Exports\CourseStudentsExport;
use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Course;
use App\Models\Programme;
use App\Models\Semester;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LecturerCourseController extends Controller
{
    // =========================
    // 1. LIST LECTURER COURSES
    // =========================
public function index()
{
    $user = auth()->user();

    if (!$user) {
        abort(403, 'Unauthenticated user.');
    }

    $lecturer = $user->lecturer;

    if (!$lecturer) {
        abort(403, 'Lecturer profile not found for this user.');
    }

    // CURRENT ACADEMIC CONTEXT
    $session = AcademicSession::where('is_active', true)->first();
    $semester = Semester::where('is_active', true)->first();

    if (!$session || !$semester) {
        abort(403, 'Active academic session or semester not set.');
    }

    $courses = Course::with(['department', 'programmes'])
        ->withCount([
            'students as students_count' => function ($query) use ($session, $semester) {
                $query->where('coursereg_student.session_id', $session->id)
                      ->where('coursereg_student.semester_id', $semester->id);
            }
        ])
        ->where('lecturer_id', $lecturer->id)
        ->latest()
        ->get();

    return view('lecturer.courses.index', compact(
        'courses',
        'lecturer',
        'session',
        'semester'
    ));
}



public function students(Request $request, Course $course)
{
    $currentSession = AcademicSession::where('is_active', true)->first();
    $currentSemester = Semester::where('is_active', true)->first();

    $sessionId = $request->session_id ?? ($currentSession?->id);
    $semesterId = $request->semester_id ?? ($currentSemester?->id);

    $sessionName = AcademicSession::find($sessionId)?->session_name ?? 'No Session';
    $semesterName = Semester::find($semesterId)?->semester_name ?? 'No Semester';

    $students = $course->students()
        ->with(['user', 'programme'])
        ->when($sessionId, function ($q) use ($sessionId) {
            $q->where('coursereg_student.session_id', $sessionId);
        })
        ->when($semesterId, function ($q) use ($semesterId) {
            $q->where('coursereg_student.semester_id', $semesterId);
        })
        ->when($request->programme_id, function ($q) use ($request) {
            $q->where('students.programme_id', $request->programme_id);
        })
        ->when($request->level, function ($q) use ($request) {
            $q->where('students.level', $request->level);
        })
        ->when($request->search, function ($q) use ($request) {
            $q->where(function ($q2) use ($request) {
                $q2->whereHas('user', function ($u) use ($request) {
                    $u->where('first_name', 'like', "%{$request->search}%")
                      ->orWhere('last_name', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%");
                })
                ->orWhere('matric_no', 'like', "%{$request->search}%");
            });
        })
        ->paginate(20)
        ->withQueryString();

    $programmes = Programme::all();

    return view('lecturer.students.course-students', compact(
        'course',
        'students',
        'programmes',
        'sessionName',
        'semesterName'
    ));
}



        public function export(Request $request, Course $course)
    {
        $students = $course->students()
            ->with(['user', 'programme'])
            ->get();

        $currentSession = AcademicSession::where('is_active', true)->first();
        $currentSemester = Semester::where('is_active', true)->first();

        $sessionId = $request->session_id ?? ($currentSession?->id);
        $semesterId = $request->semester_id ?? ($currentSemester?->id);

        return Excel::download(
            new CourseStudentsExport(
                $students,
                $course,
                [
                    'session_name' => optional(AcademicSession::find($sessionId))->session_name,
                    'semester_name' => optional(Semester::find($semesterId))->semester_name,
                ]
            ),
            'course_students_' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}
