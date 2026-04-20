<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StudentsExport;
use App\Exports\StudentsTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use App\Models\AcademicSession;
use App\Models\Department;
use App\Models\Programme;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;



class StudentController extends Controller


{
    /**
     * Show list of students
     */
    public function index(Request $request)
    {
        $countries = config('location.countries') ?? [];
        $nigeriaStates = config('location.nigeria_states') ?? [];
        $query = User::with(['student.programme.department'])
            ->where('role', 'student');

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhereHas('student', function ($q2) use ($search) {
                    $q2->where('matric_no', 'like', "%$search%");
                });
            });
        }

        // FILTER BY PROGRAMME
        if ($request->filled('programme_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('programme_id', $request->programme_id);
            });
        }

        // FILTER BY DEPARTMENT
        if ($request->filled('department_id')) {
            $query->whereHas('student.programme', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // FILTER BY LEVEL
        if ($request->filled('level')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('level', $request->level);
            });
        }

        // FILTER BY ENTRY MODE
        if ($request->filled('mode_of_admission')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('mode_of_admission', $request->mode_of_admission);
            });
        }

        // FILTER BY STATUS
        if ($request->filled('status')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }
        $students = $query->latest()->paginate(10)->withQueryString();
        $departments = Department::all();
        $programmes = Programme::with('department')->get();
        $session = AcademicSession::all();

        return view('admin.students.index', compact(
            'students',
            'departments',
            'programmes',
            'session',
            'countries',
            'nigeriaStates'
        ));

    }

    /**
     * Store a new student
     */

    public function store(Request $request)
{
    try {
        $data = $request->validate([
            // USER (LOGIN)
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',

            // STUDENT (ACADEMIC)
            'programme_id' => 'required|exists:programmes,id',
            'mode_of_admission' => 'required|in:UTME,Direct Entry,Transfer,Pre-degree,Post-degree,Others',
            'entry_level' => 'nullable|integer|in:100,200',
            'level' => 'nullable|integer|in:100,200,300,400,500,600,700',
            'admission_session' => 'nullable|string',
            'matric_no' => 'nullable|unique:students,matric_no',
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',

            // PERSONAL
            'phone' => 'required|regex:/^\+?[1-9]\d{7,14}$/',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
            'nationality' => 'required|string|max:100',
            'state_of_origin' => 'required|string|max:100',
            'lga_of_origin' => 'required|string|max:100',
            'address' => 'required|string|max:250',
        ]);
    } catch (ValidationException $e) {
        return redirect()
            ->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('add_student_error', true);
    }

    try {
        DB::transaction(function () use ($data, $request) {
            $programme = Programme::findOrFail($data['programme_id']);

            $user = User::create([
                'first_name' => $data['first_name'],
                'middle_name'=> $data['middle_name'] ?? null,
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password' => Hash::make(strtolower($data['last_name'])),
                'role'       => 'student',
                'status'     => 'active',
            ]);

            $entryLevel = $data['entry_level'] ?? match ($data['mode_of_admission']) {
                'Direct Entry' => 200,
                default => 100,
            };

            $level = $data['level'] ?? $entryLevel;

            // Handle profile photo upload
            $photoPath = $request->file('profile_photo')->store('students', 'public');

            Student::create([
                'user_id' => $user->id,
                'programme_id' => $programme->id,
                'matric_no' => $data['matric_no'] ?? $this->generateMatricNo($programme),
                'mode_of_admission' => $data['mode_of_admission'],
                'entry_level' => $entryLevel,
                'level' => $level,
                'admission_session' => $data['admission_session'] ?? null,
                'status' => 'active',
                'phone' => $data['phone'],
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
                'address' => $data['address'],
                'state_of_origin' => $data['state_of_origin'],
                'lga_of_origin' => $data['lga_of_origin'],
                'nationality' => $data['nationality'],
                'profile_photo' => $photoPath,
            ]);
        });

        return redirect()->back()->with('success', 'Student added successfully.');
    } catch (\Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with('add_student_error', true)
            ->with('error_message', $e->getMessage());
    }
}

    /**
     * Generate unique matric number
     */
    private function generateMatricNo($programme)
    {
        $year = date('Y');

        // Example prefix (you can customize)
        $prefix = strtoupper(substr($programme->programme_name, 0, 3)); // e.g. CSC

        // Count existing students for that year
        $count = Student::whereYear('created_at', $year)->count() + 1;

        // Format number to 3 digits
        $number = str_pad($count, 3, '0', STR_PAD_LEFT);

        return "{$prefix}/{$year}/{$number}";
    }
/**
 * Display a single student
 */
public function show($id)
{
    try {
        $student = User::with(['student.programme.department'])
            ->where('role', 'student')
            ->findOrFail($id);

        return view('admin.students.show', compact('student'));

    } catch (\Exception $e) {
        return redirect()
            ->route('admin.students.index')
            ->withErrors(['error' => 'Student not found.']);
    }
}

/**
 * Update student
 */
public function update(Request $request, Student $student)
{
    $validated = $request->validate([
        // USER
        'first_name'        => 'required|string|max:255',
        'middle_name'       => 'nullable|string|max:255',
        'last_name'         => 'required|string|max:255',
        'email'             => ['required','email','max:255', Rule::unique('users','email')->ignore($student->user_id)],

        // STUDENT
        'programme_id'      => 'required|exists:programmes,id',
        'mode_of_admission' => 'required|in:UTME,Direct Entry,Transfer,Pre-degree,Post-degree,Others',
        'entry_level'       => 'nullable|integer|in:100,200',
        'level'             => 'nullable|integer|in:100,200,300,400,500,600,700',
        'admission_session' => 'nullable|string',
        'matric_no'         => ['nullable', Rule::unique('students','matric_no')->ignore($student->id)],
        'profile_photo'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        // PERSONAL
        'phone'             => 'nullable|regex:/^\+?[1-9]\d{7,14}$/',
        'gender'            => 'required|in:male,female,other',
        'date_of_birth'     => 'required|date',
        'nationality'       => 'required|string|max:100',
        'state_of_origin'   => 'required|string|max:100',
        'lga_of_origin'     => 'required|string|max:100',
        'address'           => 'nullable|string|max:250',
        'status'            => 'required|in:active,graduated,suspended,withdrawn',
    ]);

    DB::transaction(function () use ($validated, $student, $request) {

        // Update User
        $student->user->update([
            'first_name' => $validated['first_name'],
            'middle_name'=> $validated['middle_name'] ?? null,
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            if ($student->profile_photo && Storage::exists('public/'.$student->profile_photo)) {
                Storage::delete('public/'.$student->profile_photo);
            }
            $photoPath = $request->file('profile_photo')->store('students', 'public');
        } else {
            $photoPath = $student->profile_photo;
        }

        // Determine entry_level / level if not provided
        $entryLevel = $validated['entry_level'] ?? match ($validated['mode_of_admission']) {
            'Direct Entry' => 200,
            default => 100,
        };
        $level = $validated['level'] ?? $entryLevel;

        // Update Student
        $student->update([
            'programme_id'      => $validated['programme_id'],
            'mode_of_admission' => $validated['mode_of_admission'],
            'entry_level'       => $entryLevel,
            'level'             => $level,
            'admission_session' => $validated['admission_session'] ?? null,
            'matric_no'         => $validated['matric_no'] ?? $student->matric_no,
            'phone'             => $validated['phone'] ?? null,
            'gender'            => $validated['gender'],
            'date_of_birth'     => $validated['date_of_birth'],
            'nationality'       => $validated['nationality'],
            'state_of_origin'   => $validated['state_of_origin'],
            'lga_of_origin'     => $validated['lga_of_origin'],
            'address'           => $validated['address'] ?? null,
            'status'            => $validated['status'],
            'profile_photo'     => $photoPath,
        ]);
    });

    return redirect()->back()->with('success', 'Student updated successfully!');
}

/**
 * Delete a student (User + Student profile)
 */
public function destroy($id)
{
    try {
        DB::transaction(function () use ($id) {

            // Fetch user with student relationship
            $user = User::with('student')->findOrFail($id);

            // Ensure it's actually a student
            if ($user->role !== 'student') {
                throw new \Exception('Invalid operation. User is not a student.');
            }

            // Delete student profile first (if exists)
            if ($user->student) {
                $user->student->delete();
            }

            // Delete user
            $user->delete();
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');

    } catch (\Exception $e) {

        return redirect()
            ->back()
            ->withErrors(['error' => $e->getMessage()]);
    }
}



    public function downloadTemplate()
{
    return Excel::download(
        new StudentsTemplateExport,
        'students_template.xlsx'
    );
}

        // ===============================
    // BULK UPLOAD (FIXED)
    // ===============================
    public function importStudents(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,csv|max:5120',
    ]);

    try {
        $import = new StudentsImport();

        Excel::import($import, $request->file('file'));

        // Handle validation failures
        if ($import->failures()->isNotEmpty()) {

            $errors = [];

            foreach ($import->failures() as $failure) {
                foreach ($failure->errors() as $errorMsg) {
                    $errors[] = [
                        'row' => $failure->row(),
                        'column' => $failure->attribute(),
                        'value' => $failure->values()[$failure->attribute()] ?? null,
                        'message' => $errorMsg,
                    ];
                }
            }

            return back()
                ->with('import_errors', $errors)
                ->with('warning', 'Some rows failed. Successful rows were imported.');
        }

        return back()->with('success', 'All students imported successfully!');

    } catch (\Exception $e) {

        return back()->with('error', 'Import failed: ' . $e->getMessage());
    }
}



public function export(Request $request)
{
    return Excel::download(
        new StudentsExport($request),
        'students_' . date('Y_m_d_H_i_s') . '.xlsx'
    );
}




}
