<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::with('department')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show form to create a new user
     */
    public function create()
    {
        $departments = Department::all();
        $roles = ['student', 'lecturer', 'admin'];
        $levels = [100, 200, 300, 400]; // for students

        return view('users.create', compact('departments', 'roles', 'levels'));
    }

    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:student,lecturer,admin',
            'department_id' => 'required|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'level' => 'nullable|integer',
            'matric_number' => $request->role === 'student'
                                ? 'nullable|unique:users,matric_number'
                                : 'nullable|unique:users,matric_number',
        ]);

// Only generate matric number for students
$matric_number = null;
if ($request->role === 'student') {
    $matric_number = $request->matric_number;

    if (empty($matric_number)) {
        $dept = Department::find($request->department_id);
        $dept_code = strtoupper($dept->code ?? 'XXX'); // fallback code

        $year = date('Y');

        // Get last student in this dept and year
        $lastStudent = User::where('role', 'student')
            ->where('department_id', $request->department_id)
            ->whereYear('created_at', $year)
            ->latest()
            ->first();

        $number = $lastStudent ? ((int)substr($lastStudent->matric_number, -3)) + 1 : 1;

        $matric_number = sprintf("%s/%s/%03d", $dept_code, $year, $number);
    }
}


        // Create user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'), // default password
            'role' => $request->role,
            'department_id' => $request->department_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'level' => $request->level ?? 100,
            'matric_number' => $matric_number,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show form to edit a user
     */
    public function edit(User $user)
    {
        $departments = Department::all();
        $roles = ['student', 'lecturer', 'admin'];
        $levels = [100, 200, 300, 400];

        return view('users.edit', compact('user', 'departments', 'roles', 'levels'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:student,lecturer,admin',
            'department_id' => 'required|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'level' => 'nullable|integer',
            'matric_number' => $request->role === 'student'
                                ? 'required|unique:users,matric_number,' . $user->id
                                : 'nullable|unique:users,matric_number,' . $user->id,
        ]);

        // Auto-generate matric if missing for student
        $matric_number = $request->matric_number;
        if ($request->role === 'student' && empty($matric_number)) {
            $latestStudent = User::where('role','student')->latest()->first();
            $number = $latestStudent ? $latestStudent->id + 1 : 1;
            $matric_number = 'MAT' . date('Y') . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        }

        $user->update([
            'name' => $request->name,
            'role' => $request->role,
            'department_id' => $request->department_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'level' => $request->level ?? $user->level,
            'matric_number' => $matric_number,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
