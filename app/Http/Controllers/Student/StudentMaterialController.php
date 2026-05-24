<?php

namespace App\Http\Controllers\Student;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicSession;
use App\Models\Semester;
use App\Models\Material;
use App\Models\MaterialProgress;
use App\Models\Assignment;
use App\Models\Quiz;

class StudentMaterialController extends Controller
{

public function index(Request $request)
{
    $student = auth()->user()->student;

    if (!$student) abort(403);

    $activeSession = AcademicSession::where('is_active', true)->first();

    $activeSemester = Semester::where('is_active', true)
        ->where('academic_session_id', $activeSession?->id)
        ->first();

    $selectedLevel = $request->level ?? $student->level;

    $allowedLevels = [100, 200, 300, 400, 500];

    if (!in_array($selectedLevel, $allowedLevels)) {
        $selectedLevel = $student->level;
    }

    // COURSES
    $courses = $student->courses()
        ->wherePivot('session_id', $activeSession?->id)
        ->wherePivot('semester_id', $activeSemester?->id)
        ->where('level', $selectedLevel)
        ->get();

    $selectedCourse = null;


    if ($request->filled('course_id')) {
        $selectedCourse = $student->courses()
            ->where('courses.id', $request->course_id)
            ->wherePivot('session_id', $activeSession?->id)
            ->wherePivot('semester_id', $activeSemester?->id)
            ->where('level', $selectedLevel)
            ->first();
    }

    $materials = collect();
    $progressMap = collect();
    $assignments = collect();
    $quizzes = collect();
    $progressMap = collect();

    if ($selectedCourse) {

        //  MATERIALS ORDERED PROPERLY
        $materials = Material::where('course_id', $selectedCourse->id)
            ->where('academic_session_id', $activeSession?->id)
            ->where('semester_id', $activeSemester?->id)
            ->orderBy('week', 'asc')
            ->orderBy('created_at', 'asc') // IMPORTANT
            ->get()
            ->groupBy('week');

        // BULK LOAD PROGRESS (NO N+1)
        $progressMap = MaterialProgress::where('student_id', $student->id)
            ->whereIn('material_id',
                Material::where('course_id', $selectedCourse->id)->pluck('id')
            )
            ->get()
            ->keyBy('material_id');
    }

    return view('student.materials.index', compact(
        'courses',
        'selectedCourse',
        'materials',
        'activeSession',
        'activeSemester',
        'selectedLevel',
        'progressMap',
        'assignments',
        'quizzes'
    ));
}

public function show($id)
{
    $student = auth()->user()->student;

    $material = Material::with(['course', 'academicSession', 'semester'])
        ->findOrFail($id);

    $allowed = $student->courses()
        ->where('courses.id', $material->course_id)
        ->exists();

    if (!$allowed) abort(403);

    $progress = MaterialProgress::firstOrCreate(
        [
            'student_id' => $student->id,
            'material_id' => $material->id,
        ],
        [
            'status' => 'not_started',
            'progress_percent' => 0,
            'first_viewed_at' => now(),
            'session_count' => 0,
        ]
    );

    // ✅ UPDATE VIEW SESSION DATA
    $progress->increment('session_count');

    $progress->update([
        'status' => 'in_progress',
        'last_viewed_at' => now(),
        'last_session_started_at' => now(),
    ]);

    return view('student.materials.view', compact('material', 'progress'));
}

   public function track(Request $request, $id)
{
    $student = auth()->user()->student;

    $progress = MaterialProgress::firstOrCreate(
        [
            'student_id' => $student->id,
            'material_id' => $id,
        ],
        [
            'status' => 'not_started',
            'progress_percent' => 0,
            'first_viewed_at' => now(),
            'last_session_started_at' => now(),
            'session_count' => 1,
        ]
    );

    $timeSpent = (int) $request->input('time_spent', 0);
    $scrollDepth = (int) $request->input('scroll_depth', 0);

    /**
     *
     * Only increment session if it's a new visit context
     */
    if (!$progress->last_session_started_at ||
        $progress->last_session_started_at->diffInMinutes(now()) > 30) {

        $progress->increment('session_count');

        $progress->update([
            'last_session_started_at' => now()
        ]);
    }

    /**
     *  ENGAGEMENT UPDATE
     */
    $progress->updateEngagement($timeSpent, $scrollDepth);

    return response()->json(['status' => 'ok']);
    $progress->increment('time_spent_seconds', $time);


}
}
