<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Programme;
use Illuminate\Http\Request;


class CourseController extends Controller
{
    /**
     * Display a listing of courses
     */
 public function index(Request $request)
{
    // Start query with eager loading
    $query = Course::with(['programmes', 'department', 'lecturer']);

    // Search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('course_code', 'like', "%{$search}%")
              ->orWhere('course_title', 'like', "%{$search}%")
              ->orWhere('level', 'like', "%{$search}%")
              ->orWhere('semester', 'like', "%{$search}%")
              ->orWhere('course_type', 'like', "%{$search}%")
              ->orWhereHas('programmes', function ($q2) use ($search) {
                  $q2->where('programme_name', 'like', "%{$search}%");
              });
        });
    }

    // Order by most recent
    $courses = $query->orderBy('created_at', 'desc')
                     ->paginate(10)
                     ->withQueryString();

    // All programmes for Add/Edit modal dropdowns
    $programmes = Programme::orderBy('programme_name')->with('department')->get();
    $departments = $programmes->pluck('department')->unique()->sortBy('department_name');
    $lecturers = Lecturer::orderBy('first_name')->get();
    return view('admin.courses.index', compact('courses', 'programmes', 'departments', 'lecturers'));
}



    /**
     * Store new course
     */
    public function store(Request $request)
    {
        // Validate request including department and lecturer
        $validated = $request->validate([
            'course_code' => 'required|string|max:255|unique:courses,course_code',
            'course_title' => 'required|string|max:255',
            'course_description' => 'nullable|string',
            'level' => 'required|integer|in:100,200,300,400,500,600,700',
            'semester' => 'required|in:First,Second,Third,Fourth',
            'credit_unit' => 'required|integer|min:1|max:10',
            'course_type' => 'required|in:Core,Elective,General',
            'status' => 'required|boolean',
            'department_id' => 'required|exists:departments,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'programmes' => 'required|array',
            'programmes.*' => 'exists:programmes,id',
        ]);

        try {
            // Create the course
            $course = Course::create([
                'course_code' => strtoupper($validated['course_code']),
                'course_title' => $validated['course_title'],
                'course_description' => $validated['course_description'] ?? null,
                'level' => $validated['level'],
                'semester' => $validated['semester'],
                'credit_unit' => $validated['credit_unit'],
                'course_type' => $validated['course_type'],
                'status' => $validated['status'],
                'department_id' => $validated['department_id'],
                'lecturer_id' => $validated['lecturer_id'],
            ]);

// attach programmes
$course->programmes()->sync($validated['programmes']);

            // Sync many-to-many relationship with programmes
            $course->programmes()->sync($validated['programmes']);

            return redirect()->back()->with('success', 'Course added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['general_error' => $e->getMessage()])
                ->with('add_course_error', true);
        }
    }

    /**
     * Show single course
     */
    public function show(Course $course)
    {
        $course->load('programmes');

        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show edit form
     */
    public function edit(Course $course)
    {
        $programmes = Programme::orderBy('programme_name')->get();

        $course->load('programmes');

        return view('admin.courses.edit', compact('course', 'programmes'));
    }

    /**
     * Update course
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        // Validate EVERYTHING you will use
        $validated = $request->validate([
            'course_code' => 'required|string|max:255|unique:courses,course_code,' . $course->id,
            'course_title' => 'required|string|max:255',
            'course_description' => 'nullable|string',
            'level' => 'required|integer|in:100,200,300,400,500,600,700',
            'semester' => 'required|in:First,Second,Third,Fourth',
            'credit_unit' => 'required|integer|min:1|max:10',
            'course_type' => 'required|in:Core,Elective,General',
            'status' => 'required|boolean',

            // ADD THESE (YOU MISSED THEM)
            'department_id' => 'required|exists:departments,id',
            'lecturer_id' => 'required|exists:lecturers,id',

            'programmes' => 'required|array',
            'programmes.*' => 'exists:programmes,id',
        ]);

        try {
            // Update course
            $course->update([
                'course_code' => strtoupper($validated['course_code']),
                'course_title' => $validated['course_title'],
                'course_description' => $validated['course_description'] ?? null,
                'level' => $validated['level'],
                'semester' => $validated['semester'],
                'credit_unit' => $validated['credit_unit'],
                'course_type' => $validated['course_type'],
                'status' => $validated['status'],
                'department_id' => $validated['department_id'],
                'lecturer_id' => $validated['lecturer_id'],
            ]);

            // Sync pivot (ONLY ONCE)
            $course->programmes()->sync($validated['programmes']);

            return redirect()->back()->with('success', 'Course updated successfully.');

        } catch (\Exception $e) {

            return redirect()->back()
                ->withInput()
                ->withErrors(['general_error' => $e->getMessage()])
                ->with('edit_course_error', true)
                ->with('edit_course_id', $course->id);
        }
    }
    /**
     * Delete course
     */
        public function destroy(Course $course)
    {
        try {
            // Detach related programmes (pivot table cleanup)
            $course->programmes()->detach();

            // Delete the course (soft delete in your case)
            $course->delete();

            return redirect()
                ->route('admin.courses.index')
                ->with('success', 'Course deleted successfully.');

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withErrors(['delete_error' => $e->getMessage()]);
        }
    }
}
