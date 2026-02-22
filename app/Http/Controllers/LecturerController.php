<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LecturerController extends Controller
{
    /**
     * Display a listing of lecturers with search.
     */
    public function index(Request $request)
    {
        $query = Lecturer::with(['faculty', 'department', 'user']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('staff_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('faculty', function ($q2) use ($search) {
                      $q2->where('faculty_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('department', function ($q3) use ($search) {
                      $q3->where('dept_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($q4) use ($search) {
                      $q4->where('email', 'like', "%{$search}%");
                  });
            });
        }

        $lecturers = $query->latest()->paginate(15)->withQueryString();

        $faculties = Faculty::all();
        $departments = Department::all();

        return view('admin.lecturers.index', compact(
            'lecturers',
            'faculties',
            'departments'
        ));
    }

    /**
     * Store a newly created lecturer.
     */
 public function store(Request $request)
{
    try {

        $data = $request->validate([
            'title' => 'nullable|string|max:10',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => ['required', 'regex:/^\+?[1-9]\d{7,14}$/'],
            'staff_id' => 'required|string|max:50|unique:lecturers,staff_id',
            'faculty_id' => 'required|exists:faculties,id',
            'department_id' => [
                'required',
                'exists:departments,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('faculty_id')) {
                        $department = Department::find($value);
                        if (!$department || $department->faculty_id != $request->faculty_id) {
                            $fail('The selected department does not belong to the selected faculty.');
                        }
                    }
                }
            ],
            'specialization' => 'nullable|string|max:255',
            'rank' => 'required|string|max:100',
            'employment_type' => 'required|in:full_time,part_time,contract,visiting',
            'gender' => 'required|in:male,female',
            'staff_category' => 'required|in:academic',
            'attendance_rate' => 'nullable|numeric|min:0|max:100',
            'student_feedback_score' => 'nullable|numeric|min:0|max:5',
        ], [
            'phone.regex' => 'Phone number must be valid and include country code (e.g. +2348012345678).'
        ]);

    } catch (ValidationException $e) {

        return redirect()
            ->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('add_lecturer_error', true);
    }

    DB::transaction(function () use ($data) {

        $user = User::create([
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt('password'),
            'role' => 'lecturer',
            'status' => 'active',
        ]);

        $user->lecturer()->create([
            'title' => $data['title'] ?? null,
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'staff_id' => $data['staff_id'],
            'phone' => $data['phone'] ?? null,
            'faculty_id' => $data['faculty_id'],
            'department_id' => $data['department_id'],
            'specialization' => $data['specialization'] ?? null,
            'rank' => $data['rank'],
            'employment_type' => $data['employment_type'],
            'gender' => $data['gender'],
            'staff_category' => $data['staff_category'],
            'attendance_rate' => $data['attendance_rate'] ?? 0,
            'student_feedback_score' => $data['student_feedback_score'] ?? 0,
            'status' => 'active',
        ]);
    });

    return redirect()
        ->route('admin.lecturers.index')
        ->with('success', 'Lecturer created successfully.');
}

    /**
     * Display a single lecturer.
     */
    public function show(Lecturer $lecturer)
    {
        $lecturer->load(['faculty', 'department', 'user']);
        return view('admin.lecturers.show', compact('lecturer'));
    }

    /**
     * Update the specified lecturer.
     */
 public function update(Request $request, Lecturer $lecturer)
{
    try {

        $data = $request->validate([
            'title' => 'nullable|string|max:10',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => ['nullable', 'regex:/^\+?[1-9]\d{7,14}$/'],
            'staff_id' => [
                'required',
                Rule::unique('lecturers')->ignore($lecturer->id),
            ],
            'faculty_id' => 'required|exists:faculties,id',
            'department_id' => [
                'required',
                'exists:departments,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('faculty_id')) {
                        $department = Department::find($value);
                        if (!$department || $department->faculty_id != $request->faculty_id) {
                            $fail('The selected department does not belong to the selected faculty.');
                        }
                    }
                }
            ],
            'specialization' => 'nullable|string|max:255',
            'rank' => 'required|string|max:100',
            'employment_type' => 'required|in:full_time,part_time,contract,visiting',
            'gender' => 'required|in:male,female',
            'staff_category' => 'required|in:academic',
            'attendance_rate' => 'nullable|numeric|min:0|max:100',
            'student_feedback_score' => 'nullable|numeric|min:0|max:5',
            'status' => 'required|in:active,inactive',
        ], [
            'phone.regex' => 'Phone number must be valid and include country code (e.g. +2348012345678).'
        ]);

    } catch (ValidationException $e) {

        return redirect()
            ->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('edit_lecturer_error', $lecturer->id);
    }

    DB::transaction(function () use ($lecturer, $data) {

        $lecturer->update([
            'title' => $data['title'] ?? null,
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'staff_id' => $data['staff_id'],
            'phone' => $data['phone'] ?? null,
            'faculty_id' => $data['faculty_id'],
            'department_id' => $data['department_id'],
            'specialization' => $data['specialization'] ?? null,
            'rank' => $data['rank'],
            'employment_type' => $data['employment_type'],
            'gender' => $data['gender'],
            'staff_category' => $data['staff_category'],
            'attendance_rate' => $data['attendance_rate'] ?? 0,
            'student_feedback_score' => $data['student_feedback_score'] ?? 0,
            'status' => $data['status'],
        ]);

        $lecturer->user()->update([
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'status' => $data['status'],
        ]);
    });

    return back()->with('success', 'Lecturer updated successfully.');
}



    /**
     * Remove the specified lecturer.
     */
    public function destroy(Lecturer $lecturer)
    {
        DB::transaction(function () use ($lecturer) {
            if ($lecturer->user) {
                $lecturer->user->delete();
            }
            $lecturer->delete();
        });

        return redirect()
            ->route('admin.lecturers.index')
            ->with('success', 'Lecturer removed successfully.');
    }

    /**
     * Activate a lecturer.
     */
    public function activate(Lecturer $lecturer)
    {
        DB::transaction(function () use ($lecturer) {
            $lecturer->update(['status' => 'active']);
            if ($lecturer->user) {
                $lecturer->user->update(['status' => 'active']);
            }
        });

        return back()->with('success', 'Lecturer activated.');
    }

    /**
     * Deactivate a lecturer.
     */
    public function deactivate(Lecturer $lecturer)
    {
        DB::transaction(function () use ($lecturer) {
            $lecturer->update(['status' => 'inactive']);
            if ($lecturer->user) {
                $lecturer->user->update(['status' => 'inactive']);
            }
        });

        return back()->with('success', 'Lecturer deactivated.');
    }
}
