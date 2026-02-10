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
        // Ordered by the most recent start date
        $academic_sessions = AcademicSession::orderBy('start_date', 'desc')->get();

        return view('admin.academic_sessions.index', compact('academic_sessions'));
    }

    /**
     * Store a newly created academic session.
     */
    public function store(Request $request)
    {
        $request->validate([
            'session_name' => 'required|string|max:255',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after:start_date',
            'is_active'    => 'sometimes|boolean',
        ]);

        DB::transaction(function () use ($request) {
            $is_active = $request->has('is_active');

            // If the new session is set to active, deactivate all others
            if ($is_active) {
                AcademicSession::where('is_active', true)->update(['is_active' => false]);
            }

            // Create the new session
            AcademicSession::create([
                'session_name' => $request->session_name,
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
                'is_active'    => $is_active,
            ]);

            // Increment student levels logic
            // Note: Usually, this is done via a specific 'Promotion' button rather than on creation,
            // but keeping your logic here:
            if ($is_active && AcademicSession::count() > 1) {
                User::where('role', 'student')->increment('level', 100);
            }
        });

        return redirect()->route('admin.academic-sessions.index')
            ->with('success', 'Academic session created successfully.');
    }

    /**
     * Update the specified academic session.
     */
    public function update(Request $request, AcademicSession $academicSession)
    {
        $request->validate([
            'session_name' => 'required|string|max:255',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after:start_date',
            'is_active'    => 'sometimes|boolean',
        ]);

        DB::transaction(function () use ($request, $academicSession) {
            $is_active = $request->has('is_active');

            if ($is_active) {
                AcademicSession::where('is_active', true)
                    ->where('id', '!=', $academicSession->id)
                    ->update(['is_active' => false]);
            }

            $academicSession->update([
                'session_name' => $request->session_name,
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
                'is_active'    => $is_active,
            ]);
        });

        return redirect()->route('admin.academic-sessions.index')
            ->with('success', 'Academic session updated successfully.');
    }

    /**
     * Remove the specified academic session.
     */
    public function destroy(AcademicSession $academicSession)
    {
        if ($academicSession->is_active) {
            return redirect()->route('admin.academic-sessions.index')
                ->with('error', 'Cannot delete the active academic session.');
        }

        $academicSession->delete();

        return redirect()->route('admin.academic-sessions.index')
            ->with('success', 'Academic session deleted successfully.');
    }
}
