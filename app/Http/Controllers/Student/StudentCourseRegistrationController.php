<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Course;
use App\Models\Department;
use App\Models\ProgrammeAcademicSetting;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentCourseRegistrationController extends Controller
{

public function index()
{
    $student = auth()->user()->student;

    $registrations = DB::table('coursereg_student')
        ->join('academic_sessions', 'coursereg_student.session_id', '=', 'academic_sessions.id')
        ->join('semesters', 'coursereg_student.semester_id', '=', 'semesters.id')
        ->join('courses', 'coursereg_student.course_id', '=', 'courses.id')
        ->where('coursereg_student.student_id', $student->id)
        ->select(
            'academic_sessions.id as session_id',
            'academic_sessions.session_name',
            'semesters.id as semester_id',
            'semesters.semester_name',
            DB::raw('COUNT(coursereg_student.course_id) as total_courses'),
            DB::raw('SUM(courses.credit_unit) as total_units'),
            DB::raw('MIN(coursereg_student.created_at) as registered_at')
        )
        ->groupBy(
            'academic_sessions.id',
            'academic_sessions.session_name',
            'semesters.id',
            'semesters.semester_name'
        )
        ->orderByDesc('academic_sessions.id')
        ->orderByDesc('semesters.id')
        ->get();

    return view('student.courses.index', compact('registrations'));
}
    // =========================
    // GET ACTIVE SETTING (ARRAY BASED)
    // =========================
    private function getActiveSetting($programme)
    {
        if (!$programme) return null;

        // CASE 1: PROGRAMME CUSTOM
        if ($programme->use_custom_academic_settings) {

            $setting = ProgrammeAcademicSetting::with(['academicSession', 'semester'])
                ->where('programme_id', $programme->id)
                ->whereHas('academicSession', fn($q) => $q->where('is_active', true))
                ->whereHas('semester', fn($q) => $q->where('is_active', true))
                ->latest()
                ->first();

            if ($setting) {
                return [
                    'academicSession' => $setting->academicSession,
                    'semester' => $setting->semester,
                ];
            }
        }

        // CASE 2: GENERAL CALENDAR
        $currentSession = AcademicSession::where('is_active', true)->first();
        $currentSemester = Semester::where('is_active', true)->first();

        if ($currentSession && $currentSemester) {
            return [
                'academicSession' => $currentSession,
                'semester' => $currentSemester,
            ];
        }

        return null;
    }

    // =========================
    // REGISTRATION WINDOW CHECK
    // =========================
    private function registrationIsOpen($student): bool
    {
        $programme = $student->programme;

        $setting = $this->getActiveSetting($programme);

        if (!$setting) return false;

        $semester = $setting['semester'] ?? null;

        if (!$semester) return false;

        return $semester->registration_allowed
            && $semester->registration_start_date
            && $semester->registration_end_date
            && now()->between(
                $semester->registration_start_date,
                $semester->registration_end_date
            );
    }



    // =========================
    // AVAILABLE COURSES
    // =========================
    public function availableCourses(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        $programme = $student->programme;

        if (!$programme) {
            return back()->with('error', 'Programme not found.');
        }

        $departments = Department::orderBy('dept_name')->get();

        $setting = $this->getActiveSetting($programme);

        $currentSession = $setting['academicSession'] ?? null;
        $currentSemester = $setting['semester'] ?? null;

        // Get registered course IDs for the current session and semester
        $registeredCourses = $student->courses()
            ->wherePivot('session_id', $currentSession->id)
            ->wherePivot('semester_id', $currentSemester->id)
            ->get();

        $registeredCourseIds = $registeredCourses->pluck('id')->toArray();
        $totalRegisteredUnits = $registeredCourses->sum('credit_unit');
        $totalRegisteredCourses = $registeredCourses->count();


        $query = Course::with(['lecturer', 'department'])
            ->where('status', 1);

        // PROGRAMME TYPE FILTER
        $query->whereHas('programmes', function ($q) use ($programme) {
            $q->where('programme_level_type', $programme->programme_level_type);
        });

        $isExpand = $request->boolean('expand');

     if (!$isExpand) {

        $query->where(function ($q) use ($programme, $student, $registeredCourseIds) {

            // Programme courses for current level
            $q->where(function ($inner) use ($programme, $student) {
                $inner->whereHas('programmes', function ($q2) use ($programme) {
                    $q2->where('programmes.id', $programme->id);
                })
                ->where('level', '<=', $student->level);
            });

            // OR already registered courses (FORCE SHOW)
            if (!empty($registeredCourseIds)) {
                $q->orWhereIn('id', $registeredCourseIds);
            }

        });


        } else {
            // EXPAND MODE
            if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
            }

            $query->where('level', '<=', $student->level);

            if ($request->filled('level')) {
                $query->where('level', '<=', $request->level);
            }
        }

        // SEMESTER FILTER
        if ($currentSemester) {
            $query->where('semester', $currentSemester->semester_name);
        }

        $courses = $query->orderBy('level')->get();

        return view('student.courses.available', [
            'user_names' => $user->first_name . ' ' . $user->last_name,
            'student' => $student,
            'programme' => $programme,
            'departments' => $departments,
            'courses' => $courses,
            'registeredCourseIds' => $registeredCourseIds,
            'currentSession' => $currentSession,
            'currentSemester' => $currentSemester,
            'isExpand' => $isExpand,
            'isOpen' => $this->registrationIsOpen($student),
            'totalRegisteredUnits' => $totalRegisteredUnits,
            'totalRegisteredCourses' => $totalRegisteredCourses,
        ]);
    }

    // =========================
    // REGISTER COURSES
    // =========================
    public function registerCourses(Request $request)
    {
        $student = auth()->user()->student;

        if (!$this->registrationIsOpen($student)) {
            return back()->with('error', 'Registration window is closed.');
        }

        $request->validate([
            'course_ids' => ['required', 'array', 'min:1'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
        ]);

        $programme = $student->programme;
        $setting = $this->getActiveSetting($programme);

        if (!$setting) {
            return back()->with('error', 'Academic setting not found.');
        }

        $session = $setting['academicSession'] ?? null;
        $semester = $setting['semester'] ?? null;

        if (!$session || !$semester) {
            return back()->with('error', 'Invalid academic session or semester.');
        }

        $courses = Course::whereIn('id', $request->course_ids)->get();

        if ($courses->isEmpty()) {
            return back()->with('error', 'Invalid course selection.');
        }

        // CREDIT LIMIT
        $totalUnits = $courses->sum('credit_unit');
        $maxUnits = ($student->level == 500) ? 28 : 24;


        if ($totalUnits > $maxUnits) {
            return back()->with('error', "Maximum credit exceeded ({$maxUnits}).");
        }

        DB::beginTransaction();

        try {

            $attachData = [];

            foreach ($courses as $course) {

                if ($course->level > $student->level) {
                    throw new \Exception("Cannot register higher level course: {$course->course_code}");
                }

                if ($course->semester !== $semester->semester_name) {
                    throw new \Exception("Semester mismatch: {$course->course_code}");
                }

                $attachData[$course->id] = [
                    'session_id' => $session->id,
                    'semester_id' => $semester->id,
                ];
            }

            $student->courses()->syncWithoutDetaching($attachData);

            DB::commit();

            return back()->with('success', 'Courses registered successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    // =========================
    // DROP COURSE
    // =========================
     public function dropCourse($course_id)
    {
        $student = auth()->user()->student;

        if (!$this->registrationIsOpen($student)) {
            return back()->with('error', 'Registration window is closed.');
        }

        $student->courses()->detach($course_id);

        return back()->with('success', 'Course dropped successfully.');
    }


    public function printCourses()
    {
        $student = auth()->user()->student;
        $programme = $student->programme;

        $setting = $this->getActiveSetting($programme);

        $session = $setting['academicSession'] ?? null;
        $semester = $setting['semester'] ?? null;

        $registeredCourses = $student->courses()
            ->wherePivot('session_id', $session->id)
            ->wherePivot('semester_id', $semester->id)
            ->with('lecturer')
            ->get();

        $totalUnits = $registeredCourses->sum('credit_unit');

        return view('student.courses.print', compact(
            'student',
            'programme',
            'session',
            'semester',
            'registeredCourses',
            'totalUnits'
        ));
    }
}
