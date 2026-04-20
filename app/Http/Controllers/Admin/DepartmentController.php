<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index(Request $request)
    {
        $query = Department::with('faculty')->withCount(['students', 'courses']);

        // Optional: add search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('dept_name', 'like', "%{$search}%")
                  ->orWhere('dept_code', 'like', "%{$search}%")
                  ->orWhere('head_of_department', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $departments = $query->latest()->paginate(6)->withQueryString();
        $faculties = Faculty::all();

        return view('admin.departments.index', compact('departments', 'faculties'));
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'dept_name'          => 'required|string|max:255|unique:departments,dept_name',
                'dept_code'          => 'required|string|max:20|unique:departments,dept_code',
                'description'        => 'nullable|string',
                'head_of_department' => 'nullable|string|max:255',
                'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
                'faculty_id'         => 'required|exists:faculties,id',
            ]);
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('add_department_error', true);
        }

        DB::transaction(function () use ($data) {
            Department::create($data + [
                'status' => 'active',
            ]);
        });

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, Department $department)
    {
        try {
            $data = $request->validate([
                'dept_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('departments')->ignore($department->id),
                ],
                'dept_code' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('departments')->ignore($department->id),
                ],
                'description'        => 'nullable|string',
                'head_of_department' => 'nullable|string|max:255',
                'faculty_id'         => 'required|exists:faculties,id',
                'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
                'status'             => 'required|in:active,inactive',
            ]);
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('edit_department_error', $department->id);
        }

        DB::transaction(function () use ($department, $data) {
            $department->update($data);
        });

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department.
     */
    public function destroy(Department $department)
    {
        DB::transaction(function () use ($department) {
            $department->delete();
        });

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }

}
