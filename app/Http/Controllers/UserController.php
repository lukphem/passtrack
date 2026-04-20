<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display users
     */
    public function index()
    {
        $users = User::with(['department', 'programme'])
            ->latest()
            ->paginate(10);

        return view('users.index', compact('users'));
         return view('admin.students.index');
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('users.create', [
            'departments' => Department::all(),
            'programmes' => Programme::all(),
            'roles' => ['student', 'lecturer', 'admin'],
            'levels' => [100, 200, 300, 400, 500, 600, 700],
            'admissionModes' => ['UTME', 'Direct Entry', 'Transfer', 'Pre-degree', 'Post-degree', 'Others'],
        ]);
    }

    /**
     * Store user
     */
public function store(Request $request)
    {
        // Validation
        $request->validate([
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'department_id'    => 'required|exists:departments,id',
            'programme_id'     => 'nullable|exists:programmes,id',
            'mode_of_admission'=> 'required|in:UTME,Direct Entry,Transfer,Pre-degree',
            'entry_level'      => 'nullable|integer|in:100,200',
            'level'            => 'nullable|integer|in:100,200,300,400,500,600,700',
            'admission_year'   => 'nullable|digits:4',
            'matric_no'        => 'nullable|unique:users,matric_no',
            'gender'           => 'nullable|in:male,female,other',
            'date_of_birth'    => 'nullable|date',
            'phone'            => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->only([
                'first_name',
                'middle_name',
                'last_name',
                'email',
                'phone',
                'gender',
                'date_of_birth',
                'programme_id',
                'department_id',
                'mode_of_admission',
                'entry_level',
                'level',
                'admission_year',
                'matric_no',
            ]);

            // Role & default password
            $data['role'] = 'student';
            $data['password'] = bcrypt('password'); // default, user should reset later
            $data['status'] = 'active'; // default

            // Entry level logic
            $data['entry_level'] = $data['entry_level'] ?? match ($data['mode_of_admission']) {
                'Direct Entry' => 200,
                default => 100,
            };

            // Current level logic
            $data['level'] = $data['level'] ?? $data['entry_level'];

            // Admission year
            $data['admission_year'] = $data['admission_year'] ?? date('Y');

            // Generate matric number if not provided
            $data['matric_no'] = $data['matric_no'] ?? $this->generateMatricNo($data['department_id']);

            // Staff-specific fields should be null
            $data['staff_no'] = null;
            $data['profile_photo'] = null;
            $data['access_level'] = null;

            // Save student
            User::create($data);

            DB::commit();
            return back()->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Generate a unique matric number for a student based on department and year
     */
    protected function generateMatricNo($department_id)
    {
        $year = date('y'); // last two digits of current year
        $count = User::where('department_id', $department_id)->count() + 1;

        return strtoupper("DEP{$department_id}{$year}" . str_pad($count, 4, '0', STR_PAD_LEFT));
    }

    /**
     * Edit form
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
            'departments' => Department::all(),
            'programmes' => Programme::all(),
            'roles' => ['student', 'lecturer', 'admin'],
            'levels' => [100, 200, 300, 400, 500, 600, 700],
            'admissionModes' => ['UTME', 'Direct Entry', 'Transfer', 'Pre-degree'],
        ]);
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',

            'role' => 'required|in:student,lecturer,admin',
            'status' => 'required|in:active,inactive,suspended',
            'access_level' => 'nullable|in:super_admin,admin,manager,staff,viewer',
            'department_id' => 'required|exists:departments,id',
            'programme_id' => 'nullable|exists:programmes,id',

            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',

            'mode_of_admission' => 'nullable|in:UTME,Direct Entry,Transfer,Pre-degree',
            'entry_level' => 'nullable|integer|in:100,200',
            'level' => 'nullable|integer|in:100,200,300,400,500,600,700',
            'admission_year' => 'nullable|digits:4',

            'matric_no' => 'nullable|unique:users,matric_no,' . $user->id,
            'staff_no' => 'nullable|unique:users,staff_no,' . $user->id,
        ]);

        DB::beginTransaction();

        try {

            $data = $request->all();

            // Prevent role switch issues
            if ($user->role !== $request->role) {
                return back()->withErrors('Changing user role is not allowed.');
            }

            // STUDENT CONTROL
            if ($user->role === 'student') {

                $data['entry_level'] = $request->entry_level ?? $user->entry_level;
                $data['level'] = $request->level ?? $user->level;

                // Protect matric
                if (!$user->matric_no) {
                    $data['matric_no'] = $this->generateMatricNo($request->department_id);
                }

                $data['staff_no'] = null;

            } else {

                // STAFF CONTROL
                if (!$user->staff_no) {
                    $data['staff_no'] = $this->generateStaffNo();
                }

                // Clear student fields
                $data['matric_no'] = null;
                $data['entry_level'] = null;
                $data['mode_of_admission'] = null;
                $data['level'] = null;
                $data['admission_year'] = null;
            }

            $user->update($data);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        // Prevent deleting admin
        if ($user->role === 'admin') {
            return back()->withErrors('Admin cannot be deleted.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * 🔢 Generate Matric Number
     */
    private function generateMatricNo($departmentId)
    {
        $dept = Department::find($departmentId);
        $code = strtoupper($dept->code ?? 'GEN');

        $year = date('Y');

        $last = User::where('role', 'student')
            ->where('department_id', $departmentId)
            ->whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        $number = $last ? ((int) substr($last->matric_no, -3)) + 1 : 1;

        return sprintf("%s/%s/%03d", $code, $year, $number);
    }

    /**
     * 🔢 Generate Staff Number
     */
    private function generateStaffNo()
    {
        $year = date('Y');

        $last = User::whereNotNull('staff_no')
            ->orderByDesc('id')
            ->first();

        $number = $last ? ((int) substr($last->staff_no, -3)) + 1 : 1;

        return sprintf("STF/%s/%03d", $year, $number);
    }
}
