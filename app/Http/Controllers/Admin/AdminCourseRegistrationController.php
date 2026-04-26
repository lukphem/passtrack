<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;

class AdminCourseRegistrationController extends Controller
{
    /**
     * View student registered courses
     */
    public function viewStudentCourses($studentId)
    {
        $student = Student::with(['courses'])->findOrFail($studentId);

        return view('admin.course-registrations.view', compact('student'));
    }

    /**
     * Add a course to a student (Admin override)
     */
    public function addCourse(Request $request, $studentId)
    {
        $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'session_id'  => 'required',
            'semester_id' => 'required',
        ]);

        $student = Student::findOrFail($studentId);

        // prevent duplicate registration for same session/semester
        $exists = $student->courses()
            ->where('course_id', $request->course_id)
            ->wherePivot('session_id', $request->session_id)
            ->wherePivot('semester_id', $request->semester_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Student already registered this course for this session.');
        }

        $student->courses()->attach($request->course_id, [
            'session_id'  => $request->session_id,
            'semester_id' => $request->semester_id,
        ]);

        return back()->with('success', 'Course added successfully (Admin override)');
    }

    /**
     * Remove a course from student
     */
    public function removeCourse(Request $request, $studentId, $courseId)
    {
        $student = Student::findOrFail($studentId);

        // if session/semester passed, remove only that instance
        if ($request->filled('session_id') && $request->filled('semester_id')) {
            $student->courses()
                ->wherePivot('session_id', $request->session_id)
                ->wherePivot('semester_id', $request->semester_id)
                ->detach($courseId);
        } else {
            // fallback: remove all instances
            $student->courses()->detach($courseId);
        }

        return back()->with('success', 'Course removed successfully');
    }

    /**
     * Full overwrite of student course registration
     */
    public function syncCourses(Request $request, $studentId)
    {
        $request->validate([
            'course_ids'  => 'required|array',
            'session_id'  => 'required',
            'semester_id' => 'required',
        ]);

        $student = Student::findOrFail($studentId);

        $syncData = [];

        foreach ($request->course_ids as $courseId) {
            $syncData[$courseId] = [
                'session_id'  => $request->session_id,
                'semester_id' => $request->semester_id,
            ];
        }

        $student->courses()->sync($syncData);

        return back()->with('success', 'Courses synced successfully (Admin override)');
    }
}
