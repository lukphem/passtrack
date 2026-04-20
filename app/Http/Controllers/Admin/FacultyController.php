<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FacultyController extends Controller
{
    /**
     * Display a listing of faculties with search.
     */
    public function index(Request $request)
    {
        $query = Faculty::withCount([
            'departments',
            'departments as students_count' => function ($q) {
                $q->join('programmes', 'programmes.department_id', '=', 'departments.id')
                ->join('students', 'students.programme_id', '=', 'programmes.id');
            }
        ]);


        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('faculty_name', 'like', "%{$search}%")
                ->orWhere('faculty_code', 'like', "%{$search}%")
                ->orWhere('dean', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $faculties = $query
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('admin.faculties.index', compact('faculties'));
    }

    /**
     * Store a newly created faculty.
     */
    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'faculty_name'     => 'required|string|max:255',
                'faculty_code'     => 'required|string|max:50|unique:faculties,faculty_code',
                'dean'             => 'nullable|string|max:255',
                'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
                'description'      => 'nullable|string',
            ]);

        } catch (ValidationException $e) {

            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('add_faculty_error', true);
        }

        DB::transaction(function () use ($data) {
            Faculty::create($data + [
                'status' => 'active',
            ]);
        });

        return redirect()
            ->route('admin.faculties.index')
            ->with('success', 'Faculty created successfully.');
    }

    /**
     * Display a single faculty.
     */
    public function show(Faculty $faculty)
    {
        $faculty->loadCount(['departments', 'students']);
        return view('admin.faculties.show', compact('faculty'));
    }

    /**
     * Update the specified faculty.
     */
    public function update(Request $request, Faculty $faculty)
    {
        try {

            $data = $request->validate([
                'faculty_name'     => 'required|string|max:255',
                'faculty_code'     => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('faculties')->ignore($faculty->id),
                ],
                'dean'             => 'nullable|string|max:255',
                'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
                'description'      => 'nullable|string',
                'status'           => 'required|in:active,inactive',
            ]);

        } catch (ValidationException $e) {

            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('edit_faculty_error', $faculty->id);
        }

        DB::transaction(function () use ($faculty, $data) {
            $faculty->update($data);
        });

        return back()->with('success', 'Faculty updated successfully.');
    }

    /**
     * Remove the specified faculty.
     */
    public function destroy(Faculty $faculty)
    {
        DB::transaction(function () use ($faculty) {
            $faculty->delete();
        });

        return redirect()
            ->route('admin.faculties.index')
            ->with('success', 'Faculty removed successfully.');
    }

    /**
     * Activate a faculty.
     */
    public function activate(Faculty $faculty)
    {
        $faculty->update(['status' => 'active']);

        return back()->with('success', 'Faculty activated.');
    }

    /**
     * Deactivate a faculty.
     */
    public function deactivate(Faculty $faculty)
    {
        $faculty->update(['status' => 'inactive']);

        return back()->with('success', 'Faculty deactivated.');
    }
}
