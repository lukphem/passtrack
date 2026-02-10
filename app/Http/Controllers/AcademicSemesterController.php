<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AcademicSemesterController extends Controller
{
    /**
     * Display a listing of semesters.
     */
    public function index()
    {
        return view('admin.semesters.index', [
            'semesters' => Semester::with('academicSession')
                ->orderByDesc('start_date')
                ->get(),
            'academicSessions' => AcademicSession::orderByDesc('start_date')->get(),
        ]);
    }

    /**
     * Store a newly created semester.
     */
    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        $session = AcademicSession::findOrFail($data['academic_session_id']);

        // Validate semester dates and business rules
        if ($errors = $this->validateSemesterDates($data, $session)) {
            return back()
                ->withInput()
                ->withErrors($errors)
                ->with('add_semester_error', true); // optional flag for Add modal
        }

        DB::transaction(function () use ($data) {
            Semester::create($data);
        });

        return redirect()
            ->route('admin.academic-semester.index')
            ->with('success', 'Semester created successfully.');
    }

    /**
     * Update the specified semester.
     */
    public function update(Request $request, Semester $academicSemester)
    {
        $data = $this->validateRequest($request);

        $session = AcademicSession::findOrFail($data['academic_session_id']);

        // Validate semester dates and business rules
        if ($errors = $this->validateSemesterDates($data, $session, $academicSemester->id)) {
            return back()
                ->withInput()
                ->withErrors($errors)
                ->with('edit_semester_id', $academicSemester->id); // Pass ID to open correct modal
        }

        DB::transaction(function () use ($academicSemester, $data) {
            $academicSemester->update($data);
        });

        return redirect()
            ->route('admin.academic-semester.index')
            ->with('success', 'Semester updated successfully.');
    }

    /**
     * Remove the specified semester.
     */
    public function destroy(Semester $academicSemester)
    {
        if ($academicSemester->is_active) {
            return redirect()
                ->route('admin.academic-semester.index')
                ->with('error', 'Cannot delete an active semester.');
        }

        $academicSemester->delete();

        return redirect()
            ->route('admin.academic-semester.index')
            ->with('success', 'Semester deleted successfully.');
    }

    /**
     * Shared request validation for store/update.
     */
    private function validateRequest(Request $request): array
    {
        $data = $request->validate([
            'academic_session_id'     => 'required|exists:academic_sessions,id',
            'semester_name'           => 'required|string|max:255',
            'start_date'              => 'required|date',
            'end_date'                => 'required|date|after:start_date',
            'registration_start_date' => 'nullable|date',
            'registration_end_date'   => 'nullable|date|after:registration_start_date',
            'is_active'               => 'nullable|boolean',
            'registration_allowed'    => 'nullable|boolean',
        ]);

        // Normalize checkbox values
        $data['is_active'] = $request->boolean('is_active');
        $data['registration_allowed'] = $request->boolean('registration_allowed');

        return $data;
    }

    /**
     * Validate semester date logic and business rules.
     */
    private function validateSemesterDates(array $data, AcademicSession $session, $ignoreId = null)
    {
        $errors = [];

        $semesterStart = Carbon::parse($data['start_date']);
        $semesterEnd   = Carbon::parse($data['end_date']);

        $sessionStart  = Carbon::parse($session->start_date);
        $sessionEnd    = Carbon::parse($session->end_date);

        // 1. Semester must be within Academic Session
        if ($semesterStart->lt($sessionStart) || $semesterEnd->gt($sessionEnd)) {
            $errors['start_date'] =
                "Semester must start and end within the academic session period ({$sessionStart->format('M d, Y')} - {$sessionEnd->format('M d, Y')}).";
        }

        // 2. Registration window must be within Semester
        if (!empty($data['registration_start_date']) || !empty($data['registration_end_date'])) {

            if (empty($data['registration_start_date']) || empty($data['registration_end_date'])) {
                $errors['registration_start_date'] =
                    'Both registration start and end dates must be provided for the registration window.';
            } else {
                $regStart = Carbon::parse($data['registration_start_date']);
                $regEnd   = Carbon::parse($data['registration_end_date']);

                if ($regStart->lt($semesterStart) || $regEnd->gt($semesterEnd)) {
                    $errors['registration_start_date'] =
                        'Registration window must be within the semester start and end dates.';
                }
            }
        }

        // 3. Registration allowed requires valid dates
        if (!empty($data['registration_allowed'])) {
            if (empty($data['registration_start_date']) || empty($data['registration_end_date'])) {
                $errors['registration_allowed'] =
                    'You must set registration start and end dates before enabling course registration.';
            }
        }

        // 4. Only one active semester per session
        if (!empty($data['is_active'])) {
            $activeExists = Semester::where('academic_session_id', $session->id)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('is_active', true)
                ->exists();

            if ($activeExists) {
                $errors['is_active'] =
                    'Another semester is already active in this academic session. Only one active semester is allowed.';
            }
        }

        // 5. Overlap check (same session)
        $overlap = Semester::where('academic_session_id', $session->id)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where(function ($query) use ($semesterStart, $semesterEnd) {
                $query
                    ->whereBetween('start_date', [$semesterStart, $semesterEnd])
                    ->orWhereBetween('end_date', [$semesterStart, $semesterEnd])
                    ->orWhere(function ($q) use ($semesterStart, $semesterEnd) {
                        $q->where('start_date', '<=', $semesterStart)
                          ->where('end_date', '>=', $semesterEnd);
                    });
            })
            ->exists();

        if ($overlap) {
            $errors['overlap'] =
                'This semester overlaps with another semester in the same academic session. Please adjust the dates.';
        }

        return !empty($errors) ? $errors : null;
    }
}
