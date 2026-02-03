<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display all courses
     */
    public function index()
    {
        return view('courses.index', [
            'courses' => Course::with('department')->get()
        ]);
    }

    /**
     * Show the form to create a new course
     */
    public function create()
    {
        return view('courses.create', [
            'departments' => Department::all(),
            'semesters' => ['rain', 'harmattan']
        ]);
    }

    /**
     * Store a new course
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:courses,code',
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'semester' => 'required|in:rain,harmattan'
        ]);

        Course::create([
            'code' => strtoupper($request->code),
            'title' => $request->title,
            'department_id' => $request->department_id,
            'semester' => $request->semester
        ]);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course created successfully');
    }

    /**
     * Show the form to edit a course
     */
    public function edit(Course $course)
    {
        return view('courses.edit', [
            'course' => $course,
            'departments' => Department::all(),
            'semesters' => ['rain', 'harmattan']
        ]);
    }

    /**
     * Update an existing course
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:courses,code,' . $course->id,
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'semester' => 'required|in:rain,harmattan'
        ]);

        $course->update([
            'code' => strtoupper($request->code),
            'title' => $request->title,
            'department_id' => $request->department_id,
            'semester' => $request->semester
        ]);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course updated successfully');
    }

    /**
     * Delete a course
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course deleted successfully');
    }
}
