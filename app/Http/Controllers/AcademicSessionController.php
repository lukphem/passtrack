<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicSessionController extends Controller
{
    /**
     * Display a listing of academic sessions.
     */
    public function index()
    {
        $academic_sessions = AcademicSession::orderBy('start_year', 'desc')->get();

        return view('academic_sessions.index', compact('academic_sessions'));
    }

    /**
     * Show the form for creating a new session.
     */
    public function create()
    {
        return view('academic_sessions.create');
    }

    /**
     * Store a newly created academic session.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_year' => 'required|integer|min:2000',
            'end_year'   => 'required|integer|gt:start_year',
        ]);

        $session = DB::transaction(function () use ($request) {

            // Deactivate currently active session
            AcademicSession::where('is_active', true)
                ->update(['is_active' => false]);

            // Create the new session
            $newSession = AcademicSession::create([
                'start_year' => $request->start_year,
                'end_year'   => $request->end_year,
                'is_active'  => true,
            ]);

            // Increment student levels if this is NOT the first session
            if (AcademicSession::count() > 1) {
                User::where('role', 'student')->increment('level', 100);
            }

            return $newSession;
        });

        return redirect()->route('academic-sessions.index')
            ->with('success', 'Academic session created successfully.');
    }

    /**
     * Show the form for editing the specified session.
     */
    public function edit(AcademicSession $academicSession)
    {
        return view('academic_sessions.edit', compact('academicSession'));
    }

    /**
     * Update the specified academic session.
     */
    public function update(Request $request, AcademicSession $academicSession)
    {
        $request->validate([
            'start_year' => 'required|integer|min:2000',
            'end_year'   => 'required|integer|gt:start_year',
            'is_active'  => 'boolean',
        ]);

        DB::transaction(function () use ($request, $academicSession) {

            // If setting this session active, deactivate others
            if ($request->boolean('is_active')) {
                AcademicSession::where('is_active', true)
                    ->where('id', '!=', $academicSession->id)
                    ->update(['is_active' => false]);
            }

            $academicSession->update($request->only('start_year', 'end_year', 'is_active'));
        });

        return redirect()->route('academic-sessions.index')
            ->with('success', 'Academic session updated successfully.');
    }

    /**
     * Remove the specified academic session.
     */
    public function destroy(AcademicSession $academicSession)
    {
        if ($academicSession->is_active) {
            return redirect()->route('academic-sessions.index')
                ->with('error', 'Cannot delete the active academic session.');
        }

        $academicSession->delete();

        return redirect()->route('academic-sessions.index')
            ->with('success', 'Academic session deleted successfully.');
    }
}
