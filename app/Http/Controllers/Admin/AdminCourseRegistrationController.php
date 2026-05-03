<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Course;
use App\Models\Department;
use App\Models\Programme;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Http\Request;


class AdminCourseRegistrationController extends Controller
{
    // =========================
    // INDEX - SEARCH STUDENT
    // =========================
    public function index(Request $request)
    {
        $query = Student::with(['user', 'programme']);

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('matric_no', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        // FILTER PROGRAMME
        if ($request->filled('programme_id')) {
            $query->where('programme_id', $request->programme_id);
        }

        return view('admin.course-registration.index', [
            'students' => $query->paginate(20),
            'programmes' => Programme::orderBy('programme_name')->get(),
            'departments' => Department::orderBy('dept_name')->get(),
            'sessions' => AcademicSession::latest()->get(),
            'semesters' => Semester::all(),
        ]);
    }

    // =========================
    // MANAGE STUDENT COURSES
    // =========================
    public function manageStudent($student_id, Request $request)
    {
        $student = Student::with(['user', 'programme'])->findOrFail($student_id);

        // SESSION & SEMESTER
        $session = $request->session_id
            ? AcademicSession::find($request->session_id)
            : AcademicSession::where('is_active', 1)->first();

        $semester = $request->semester_id
            ? Semester::find($request->semester_id)
            : Semester::where('is_active', 1)->first();

        if (!$session || !$semester) {
            return back()->with('error', 'Session or Semester not found.');
        }

        // ================= REGISTERED COURSES =================
        $registeredCourses = $student->courses()
            ->wherePivot('session_id', $session->id)
            ->wherePivot('semester_id', $semester->id)
            ->with('lecturer')
            ->get();

        $registeredCourseIds = $registeredCourses->pluck('id')->toArray();
        $totalUnits = $registeredCourses->sum('credit_unit');

        // ================= COURSE QUERY =================
        $query = Course::with(['lecturer', 'department'])
            ->where('status', 1);

        // PROGRAMME TYPE FILTER
        $query->whereHas('programmes', function ($q) use ($student) {
            $q->where('programme_level_type', $student->programme->programme_level_type);
        });

        $isExpand = $request->boolean('expand');

        // ================= DEFAULT MODE =================
        if (!$isExpand) {

            $query->where(function ($q) use ($student, $registeredCourseIds) {

                // Programme courses for student level
                $q->where(function ($inner) use ($student) {
                    $inner->whereHas('programmes', function ($q2) use ($student) {
                        $q2->where('programmes.id', $student->programme->id);
                    })
                    ->where('level', '=', $student->level);
                });

                // Always include registered
                if (!empty($registeredCourseIds)) {
                    $q->orWhereIn('id', $registeredCourseIds);
                }
            });

        } else {

            // ================= EXPAND MODE =================

            if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
            }

            $query->where('level', '<=', $student->level);

            if ($request->filled('level')) {
                $query->where('level', '<=', $request->level);
            }
        }

        // SEMESTER FILTER
        if ($semester) {
            $query->where('semester', $semester->semester_name);
        }

        $courses = $query->orderBy('level')->get();

        return view('admin.course-registration.manage', [
            'student' => $student,
            'session' => $session,
            'semester' => $semester,
            'sessions' => AcademicSession::latest()->get(),
            'semesters' => Semester::all(),
            'registeredCourses' => $registeredCourses,
            'registeredCourseIds' => $registeredCourseIds,
            'courses' => $courses,
            'totalUnits' => $totalUnits,
            'programme' => $student->programme,
            'departments' => Department::orderBy('dept_name')->get(),
        ]);
    }

    // =========================
    // REGISTER COURSES
    // =========================
    public function registerCourses($student_id, Request $request)
    {
        $student = Student::findOrFail($student_id);

        $request->validate([
            'course_ids' => 'required|array',
            'session_id' => 'required',
            'semester_id' => 'required',
        ]);

        foreach ($request->course_ids as $courseId) {

            $exists = $student->courses()
                ->where('course_id', $courseId)
                ->wherePivot('session_id', $request->session_id)
                ->wherePivot('semester_id', $request->semester_id)
                ->exists();

            if (!$exists) {
                $student->courses()->attach($courseId, [
                    'session_id' => $request->session_id,
                    'semester_id' => $request->semester_id,
                ]);
            }
        }

        return back()->with('success', 'Courses updated successfully.');
    }

    // =========================
    // DROP COURSE
    // =========================
    public function dropCourse($student_id, $course_id)
    {
        $student = Student::findOrFail($student_id);

        $student->courses()->detach($course_id);

        return back()->with('success', 'Course dropped successfully.');
    }

    // =========================
    // PRINT SLIP
    // =========================
    public function printCourses($student_id, Request $request)
    {
        $student = Student::with(['user', 'programme'])->findOrFail($student_id);

        $session = $request->session_id
            ? AcademicSession::find($request->session_id)
            : AcademicSession::where('is_active', 1)->first();

        $semester = $request->semester_id
            ? Semester::find($request->semester_id)
            : Semester::where('is_active', 1)->first();

        if (!$session || !$semester) {
            return back()->with('error', 'Session or Semester not found.');
        }

        $registeredCourses = $student->courses()
            ->wherePivot('session_id', $session->id)
            ->wherePivot('semester_id', $semester->id)
            ->with('lecturer')
            ->get();

        return view('admin.course-registration.print', [
            'student' => $student,
            'programme' => $student->programme,
            'session' => $session,
            'semester' => $semester,
            'registeredCourses' => $registeredCourses,
            'totalUnits' => $registeredCourses->sum('credit_unit'),
        ]);
    }
}
