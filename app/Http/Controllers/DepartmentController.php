<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('faculty')
            ->withCount(['students', 'courses'])
            ->latest()
            ->get();

        $faculties = Faculty::all();

        return view(
            'admin.departments.index',
            compact('departments', 'faculties')
        );
    }

    public function store(Request $request)
    {


        $validated = $request->validate([
            'dept_name'           => 'required|string|max:255|unique:departments,dept_name',
            'dept_code'           => 'required|string|max:20|unique:departments,dept_code',
            'description'         => 'nullable|string',
            'head_of_department'  => 'nullable|string|max:255',
            'faculty_id'          => 'required|exists:faculties,id',
        ]);

        Department::create($validated);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department created successfully');
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'dept_name'           => 'required|string|max:255|unique:departments,dept_name,' . $department->id,
            'dept_code'           => 'required|string|max:20|unique:departments,dept_code,' . $department->id,
            'description'         => 'nullable|string',
            'head_of_department'  => 'nullable|string|max:255',
            'faculty_id'          => 'required|exists:faculties,id',
        ]);

        $department->update($validated);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department updated successfully');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department deleted successfully');
    }

    public function getByFaculty($facultyId)
    {
        $departments = Department::where('faculty_id', $facultyId)->get();
        dd($departments);
        return response()->json($departments);
    }


}
