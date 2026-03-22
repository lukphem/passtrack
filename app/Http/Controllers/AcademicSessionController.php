<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AcademicSessionController extends Controller
{
    /**
     * Display sessions with search and pagination
     */
    public function index(Request $request)
    {
        $query = AcademicSession::query()->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('session_name', 'like', "%{$search}%")
                  ->orWhere('start_date', 'like', "%{$search}%")
                  ->orWhere('end_date', 'like', "%{$search}%");
            });
        }

        $academic_sessions = $query
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('admin.academic_sessions.index', compact('academic_sessions'));
    }

    /**
     * Store new academic session
     */
    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'session_name' => 'unique:academic_sessions|required|string|max:255',
                'start_date'   => 'required|date',
                'end_date'     => 'required|date|after:start_date',
                'is_active'    => 'nullable|boolean',
            ]);

            if ($errors = $this->validateSessionDates($data['start_date'], $data['end_date'])) {
                return redirect()
                    ->back()
                    ->withErrors($errors)
                    ->withInput()
                    ->with('add_session_error', true);
            }

        } catch (ValidationException $e) {

            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('add_session_error', true);
        }

        DB::transaction(function () use ($data) {

            $is_active = $data['is_active'] ?? false;

            if ($is_active) {
                AcademicSession::where('is_active', true)
                    ->update(['is_active' => false]);
            }

            AcademicSession::create([
                'session_name' => $data['session_name'],
                'start_date'   => $data['start_date'],
                'end_date'     => $data['end_date'],
                'is_active'    => $is_active,
            ]);
        });

        return redirect()
            ->route('admin.academic-sessions.index')
            ->with('success', 'Academic session created successfully.');
    }

    /**
     * Update academic session
     */
    public function update(Request $request, AcademicSession $academicSession)
    {
        try {

            $data = $request->validate([
                'session_name' => 'required|string|max:255',
                'start_date'   => 'required|date',
                'end_date'     => 'required|date|after:start_date',
                'is_active'    => 'nullable|boolean',
            ]);

            if ($errors = $this->validateSessionDates(
                $data['start_date'],
                $data['end_date'],
                $academicSession->id
            )) {
                return redirect()
                    ->back()
                    ->withErrors($errors)
                    ->withInput()
                    ->with('edit_session_id', $academicSession->id);
            }

        } catch (ValidationException $e) {

            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('edit_session_id', $academicSession->id);
        }

        DB::transaction(function () use ($academicSession, $data) {

            $is_active = $data['is_active'] ?? false;

            if ($is_active) {
                AcademicSession::where('is_active', true)
                    ->where('id', '!=', $academicSession->id)
                    ->update(['is_active' => false]);
            }

            $academicSession->update([
                'session_name' => $data['session_name'],
                'start_date'   => $data['start_date'],
                'end_date'     => $data['end_date'],
                'is_active'    => $is_active,
            ]);
        });

        return back()->with('success', 'Academic session updated successfully.');
    }

    /**
     * Delete session
     */
    public function destroy(AcademicSession $academicSession)
    {
        // Prevent deleting active session
        if ($academicSession->is_active) {
            return back()->with('error', 'Cannot delete an active academic session.');
        }

        // Prevent if linked to semesters
        if ($academicSession->semesters()->exists()) {
            return back()->with('error', 'Cannot delete session because it is linked to semesters.');
        }

        // Prevent if used in custom academic settings
        if ($academicSession->programmeAcademicSettings()->exists()) {
            return back()->with('error', 'Cannot delete session because it is used in programme academic settings.');
        }

        // Safe to delete
        DB::transaction(function () use ($academicSession) {
            $academicSession->delete();
        });

        return redirect()
            ->route('admin.academic-sessions.index')
            ->with('success', 'Academic session deleted successfully.');
    }

    /**
     * Activate session
     */
    public function activate(AcademicSession $academicSession)
    {
        DB::transaction(function () use ($academicSession) {

            AcademicSession::where('is_active', true)
                ->update(['is_active' => false]);

            $academicSession->update(['is_active' => true]);
        });

        return back()->with('success', 'Academic session activated.');
    }

    /**
     * Deactivate session
     */
    public function deactivate(AcademicSession $academicSession)
    {
        $academicSession->update(['is_active' => false]);

        return back()->with('success', 'Academic session deactivated.');
    }

    /**
     * Prevent overlapping sessions
     */
    private function validateSessionDates(string $start, string $end, int $ignoreId = null): ?array
    {
        $startDate = \Carbon\Carbon::parse($start);
        $endDate   = \Carbon\Carbon::parse($end);

        $overlap = AcademicSession::when($ignoreId, fn ($q) =>
                $q->where('id', '!=', $ignoreId)
            )
            ->where(function ($q) use ($startDate, $endDate) {

                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                        $q2->where('start_date', '<=', $startDate)
                           ->where('end_date', '>=', $endDate);
                  });

            })->first();

        if ($overlap) {

            return [
                'start_date' =>
                    "This session overlaps with '{$overlap->session_name}' ".
                    "({$overlap->start_date->format('Y/m/d')} - ".
                    "{$overlap->end_date->format('Y/m/d')})."
            ];
        }

        return null;
    }
}
