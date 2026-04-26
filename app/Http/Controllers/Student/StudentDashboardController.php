<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Course;
use App\Models\CoursePrediction;
use App\Models\Department;
use App\Models\ProgrammeAcademicSetting;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;
        $user_names = $user->first_name . ' ' . $user->last_name;

        //Check student programme calendar type and return current session and semester accordingly
        $programme = $student->programme;

        if (!$programme) {
            return back()->with('error', 'Student programme not found.');
        }

            //  CASE 1: Custom calendar
        if ($programme->use_custom_academic_settings) {

            $setting = ProgrammeAcademicSetting::with([
                'academicSession',
                'semester'
            ])
            ->where('programme_id', $programme->id)
            ->whereHas('academicSession', function ($q) {
                $q->where('is_active', true);
            })
            ->whereHas('semester', function ($q) {
                $q->where('is_active', true);
            })
            ->first();

            if (!$setting) {
                return back()->with('error', 'No active custom academic setting found for this programme.');
            }

            $currentSession = $setting->academicSession;
            $currentSemester = $setting->semester;

        } else {

            // 🔀 CASE 2: General calendar
            $currentSession = AcademicSession::where('is_active', true)->first();
            $currentSemester = Semester::where('is_active', true)->first();

            if (!$currentSession || !$currentSemester) {
                return back()->with('error', 'No active academic session or semester found.');
            }
        }



        // =========================
        // BASIC STATS
        // =========================
        $enrolledCourses = $student->courses()->count();

        // =========================
        // PRE-EXAM PREDICTION DATA
        // =========================
        $predictions = CoursePrediction::with('course')
            ->where('student_id', $student->id)
            ->get()
            ->map(function ($item) {

                $attendance = $item->attendance ?? 0;
                $mock = $item->mock_score ?? 0;
                $progress = $item->study_progress ?? 0;

                // =========================
                // SIMPLE PREDICTION MODEL
                // =========================
                $predictedScore = (
                    ($attendance * 0.3) +
                    ($mock * 0.4) +
                    ($progress * 0.3)
                );

                // Risk logic
                if ($predictedScore >= 70) {
                    $risk = 'LOW';
                } elseif ($predictedScore >= 50) {
                    $risk = 'MEDIUM';
                } else {
                    $risk = 'HIGH';
                }

                // Readiness
                $readiness = $predictedScore;

                // Attach computed values
                $item->predicted_score = round($predictedScore, 1);
                $item->risk_level = $risk;
                $item->readiness_score = round($readiness, 1);

                return $item;
            });

        // =========================
        // ATTENDANCE RATE (SAFE)
        // =========================
        $attendanceRate = 0;

        if (method_exists($student, 'attendances')) {
            $total = $student->attendances()->count();
            $present = $student->attendances()->where('status', 'present')->count();

            if ($total > 0) {
                $attendanceRate = round(($present / $total) * 100, 1);
            }
        }


        return view('student.dashboard.index', [
            'student' => $student,

            // Core stats
            'enrolledCourses' => $enrolledCourses,
            'attendanceRate'  => $attendanceRate,
            'cgpa'            => 0.00, // placeholder for now

            // System stats
            'totalCourses' => Course::count(),
            'departments'  => Department::count(),

            // Academic context
            'currentSession' => $currentSession,
            'currentSemester' => $currentSemester,

            // NEW: Prediction Engine
            'predictions' => $predictions,
            'user_names' => $user_names,
        ]);
    }
}
