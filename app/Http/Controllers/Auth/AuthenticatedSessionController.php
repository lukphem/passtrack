<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login page
     */
    public function create(): View
    {
        return view('auth.login', [
            'canResetPassword' => true,
            'status' => session('status'),
        ]);
    }

    /**
     * Handle login (EMAIL OR MATRIC)
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = $data['login'];

        // =========================
        // EMAIL LOGIN
        // =========================
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {

            if (!Auth::attempt([
                'email' => $loginInput,
                'password' => $data['password'],
            ], $request->boolean('remember'))) {

                throw ValidationException::withMessages([
                    'login' => 'Invalid login credentials.',
                ]);
            }

        } else {

            // =========================
            // MATRIC LOGIN
            // =========================
            $student = \App\Models\Student::where('matric_no', $loginInput)->first();

            if (!$student || !$student->user) {
                throw ValidationException::withMessages([
                    'login' => 'Invalid matric number.',
                ]);
            }

            if (!Auth::attempt([
                'email' => $student->user->email,
                'password' => $data['password'],
            ], $request->boolean('remember'))) {

                throw ValidationException::withMessages([
                    'login' => 'Invalid login credentials.',
                ]);
            }
        }

        // =========================
        // AUTH SUCCESS
        // =========================
        $request->session()->regenerate();

        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Block inactive users
        if ($user->status !== 'active') {
            Auth::logout();

            throw ValidationException::withMessages([
                'login' => 'Your account is not active.',
            ]);
        }

        // Force password change
        // if (!$user->is_password_changed) {
        //     return redirect()->route('password.change');
        // }

        // Update last login
        $user->update([
            'last_login_at' => now(),
        ]);

        // Role-based redirect
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'lecturer' => redirect()->route('lecturer.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => redirect()->route('login'),
        };
    }

    /**
     * Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
