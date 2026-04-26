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
    // =========================
    // REGISTRATION WINDOW CHECK
    // =========================
    private function registrationIsOpen($student): bool
    {
        $programme = $student->programme;

        if (!$programme) return false;

        $session = AcademicSession::where('is_active', true)->first();
        $semester = Semester::where('is_active', true)->first();

        if (!$session || !$semester) return false;

        $setting = ProgrammeAcademicSetting::where('programme_id', $programme->id)
            ->where('academic_session_id', $session->id)
            ->where('semester_id', $semester->id)
            ->first();

        if (
            !$setting ||
            !$setting->registration_allowed ||
            !$setting->registration_start_date ||
            !$setting->registration_end_date
        ) {
            return false;
        }

        return now()->between(
            $setting->registration_start_date,
            $setting->registration_end_date
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

        $setting = ProgrammeAcademicSetting::with(['academicSession', 'semester'])
            ->where('programme_id', $programme->id)
            ->whereHas('academicSession', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->first();

        $currentSession = $setting?->academicSession;
        $currentSemester = $setting?->semester;

        $registeredCourseIds = $student->courses()
            ->pluck('courses.id')
            ->toArray();

        $query = Course::with(['lecturer', 'department'])
            ->where('status', 1);

        // PROGRAMME TYPE RULE
        $query->whereHas('programmes', function ($q) use ($programme) {
            $q->where('programme_level_type', $programme->programme_level_type);
        });

        $isExpand = $request->filled('expand') && $request->expand == 1;

        if (!$isExpand) {

            // NORMAL MODE
            $query->whereHas('programmes', function ($q) use ($programme) {
                $q->where('programmes.id', $programme->id);
            });

            $query->where('level', $student->level);

        } else {

            // EXPAND MODE (department + lower/equal level only)
            if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
            }

            if ($request->filled('level')) {
                $query->where('level', '<=', $request->level);
            } else {
                $query->where('level', '<=', $student->level);
            }
        }

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
            'isExpand' => $isExpand
        ]);
    }

    // =========================
    // EXPAND MODE ROUTE
    // =========================
    public function expandCourses(Request $request)
    {
        return redirect()->route('student.courses.available', [
            'expand' => 1,
            'department_id' => $request->department_id,
            'level' => $request->level
        ]);
    }

    // =========================
    // REGISTER COURSES (FULL SECURITY)
    // =========================
    public function registerCourses(Request $request)
    {
        $student = auth()->user()->student;

        if (!$this->registrationIsOpen($student)) {
            return back()->with('error', 'Registration window is closed.');
        }

        // VALIDATION (NO TRUST FROM FRONTEND)
        $request->validate([
            'course_ids' => ['required', 'array', 'min:1'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
        ]);

        $session = AcademicSession::where('is_active', true)->first();
        $semester = Semester::where('is_active', true)->first();

        if (!$session || !$semester) {
            return back()->with('error', 'Invalid academic calendar.');
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

                // LEVEL CONTROL (NO HIGHER LEVEL)
                if ($course->level > $student->level) {
                    throw new \Exception("Cannot register higher level course: {$course->course_code}");
                }

                // SEMESTER SAFETY CHECK
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

        return back()->with('success', 'Course removed successfully.');
    }
}
