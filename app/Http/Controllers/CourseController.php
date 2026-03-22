<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Programme;
use Illuminate\Http\Request;


class CourseController extends Controller
{
    /**
     * Display a listing of courses
     */
    public function index(Request $request)
    {
        $query = Course::with('programmes');

        // Search input
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

        $courses = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $programmes = Programme::orderBy('programme_name')->get();

        return view('admin.courses.index', compact('courses', 'programmes'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $programmes = Programme::orderBy('programme_name')->get();

        return view('admin.courses.create', compact('programmes'));
    }

    /**
     * Store new course
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_code'     => 'required|string|max:20|unique:courses,course_code',
            'course_title'    => 'required|string|max:255',
            'credit_unit'     => 'required|integer|min:1|max:10',
            'level'           => 'required|integer|min:100|max:900',
            'semester'        => 'required|in:First,Second',
            'course_type'     => 'required|in:Core,Elective',
            'programme_ids'   => 'required|array',
            'programme_ids.*' => 'exists:programmes,id',
            'status'          => 'required|boolean',
        ]);

        $course = Course::create([
            'course_code'  => $request->course_code,
            'course_title' => $request->course_title,
            'credit_unit'  => $request->credit_unit,
            'level'        => $request->level,
            'semester'     => $request->semester,
            'course_type'  => $request->course_type,
            'status'       => $request->status,
        ]);

        // Attach programmes
        $course->programmes()->attach($request->programme_ids);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
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
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'course_code'     => 'required|string|max:20|unique:courses,course_code,' . $course->id,
            'course_title'    => 'required|string|max:255',
            'credit_unit'     => 'required|integer|min:1|max:10',
            'level'           => 'required|integer|min:100|max:900',
            'semester'        => 'required|in:First,Second',
            'course_type'     => 'required|in:Core,Elective',
            'programme_ids'   => 'required|array',
            'programme_ids.*' => 'exists:programmes,id',
            'status'          => 'required|boolean',
        ]);

        $course->update([
            'course_code'  => $request->course_code,
            'course_title' => $request->course_title,
            'credit_unit'  => $request->credit_unit,
            'level'        => $request->level,
            'semester'     => $request->semester,
            'course_type'  => $request->course_type,
            'status'       => $request->status,
        ]);

        // Sync programmes (remove old + attach new)
        $course->programmes()->sync($request->programme_ids);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Delete course
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
