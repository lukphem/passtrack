<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class AcademicSemesterController extends Controller
{
    /**
     * Display a listing of semesters with search.
     */
    public function index(Request $request)
    {
        $query = Semester::with('academicSession');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('semester_name', 'like', "%{$search}%")
                  ->orWhereHas('academicSession', function ($sub) use ($search) {
                      $sub->where('session_name', 'like', "%{$search}%");
                  });
            });
        }

        $semesters = $query->latest()->paginate(10)->withQueryString();
        $academicSessions = AcademicSession::orderByDesc('start_date')->get();

        return view('admin.semesters.index', compact('semesters', 'academicSessions'));
    }

    /**
     * Store a newly created semester.
     */
    public function store(Request $request)
    {
        try {
            $data = $this->validateRequest($request);
            $session = AcademicSession::findOrFail($data['academic_session_id']);

            if ($errors = $this->validateSemesterDates($data, $session)) {
                return redirect()->back()
                    ->withErrors($errors)
                    ->withInput()
                    ->with('add_semester_error', true);
            }
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('add_semester_error', true);
        }

        DB::transaction(fn() => Semester::create($data));

        return redirect()->route('admin.academic-semester.index')
            ->with('success', 'Semester created successfully.');
    }

    /**
     * Update the specified semester.
     */
    public function update(Request $request, Semester $academicSemester)
    {
        try {
            $data = $this->validateRequest($request);
            $session = AcademicSession::findOrFail($data['academic_session_id']);

            if ($errors = $this->validateSemesterDates($data, $session, $academicSemester->id)) {
                return redirect()->back()
                    ->withErrors($errors)
                    ->withInput()
                    ->with('edit_semester_error', true)
                    ->with('edit_semester_id', $academicSemester->id);
            }
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('edit_semester_error', true)
                ->with('edit_semester_id', $academicSemester->id);
        }

        DB::transaction(fn() => $academicSemester->update($data));

        return back()->with('success', 'Semester updated successfully.');
    }

    /**
     * Remove the specified semester.
     */
    public function destroy(Semester $academicSemester)
    {
        if ($academicSemester->is_active) {
            return back()->with('error', 'Cannot delete an active semester.');
        }

        // Optional: prevent deletion if semester has registrations, results, etc.
        if ($academicSemester->courseRegistrations()->exists()) {
            return back()->with('error', 'Cannot delete semester with course registrations.');
        }

        DB::transaction(fn() => $academicSemester->delete());

        return redirect()->route('admin.academic-semester.index')
            ->with('success', 'Semester deleted successfully.');
    }

    /**
     * Activate a semester.
     */
    public function activate(Semester $academicSemester)
    {
        // Ensure only currently running semester can be active
        $today = now();
        if ($today->lt($academicSemester->start_date) || $today->gt($academicSemester->end_date)) {
            return back()->with('error', 'Only a currently running semester can be activated.');
        }

        DB::transaction(function () use ($academicSemester) {
            Semester::where('academic_session_id', $academicSemester->academic_session_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $academicSemester->update(['is_active' => true]);
        });

        return back()->with('success', 'Semester activated.');
    }

    /**
     * Deactivate a semester.
     */
    public function deactivate(Semester $academicSemester)
    {
        $academicSemester->update(['is_active' => false]);
        return back()->with('success', 'Semester deactivated.');
    }

    /**
     * Validate incoming request.
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

        $data['is_active'] = $request->boolean('is_active');
        $data['registration_allowed'] = $request->boolean('registration_allowed');

        return $data;
    }

    /**
     * Business rules validation.
     */
    /**
 * Business rules validation for semesters.
 */
    private function validateSemesterDates(array $data, AcademicSession $session, $ignoreId = null)
    {
        $errors = [];

        // Normalize semester dates
        try {
            $semesterStart = Carbon::parse($data['start_date'])->startOfDay();
            $semesterEnd   = Carbon::parse($data['end_date'])->endOfDay();
        } catch (\Exception $e) {
            $errors['start_date'] = 'Invalid semester start or end date format.';
            return $errors;
        }

        // Normalize session dates
        $sessionStart = Carbon::parse($session->start_date)->startOfDay();
        $sessionEnd   = Carbon::parse($session->end_date)->endOfDay();

        // Semester must fall within session
        if ($semesterStart->lt($sessionStart) || $semesterEnd->gt($sessionEnd)) {
            $errors['start_date'] =
                "Semester must fall within session period ({$sessionStart->format('Y/m/d')} - {$sessionEnd->format('Y/m/d')}).";
        }

        // Registration window checks (if provided)
        if (!empty($data['registration_start_date']) || !empty($data['registration_end_date'])) {
            if (empty($data['registration_start_date']) || empty($data['registration_end_date'])) {
                $errors['registration_start_date'] =
                    'Both registration start and end dates must be provided.';
            } else {
                try {
                    $regStart = Carbon::parse($data['registration_start_date']);
                    $regEnd   = Carbon::parse($data['registration_end_date']);
                } catch (\Exception $e) {
                    $errors['registration_start_date'] = 'Invalid registration date format.';
                    return $errors;
                }

                // Ensure registration window is within semester
                if ($regStart->lt($semesterStart) || $regEnd->gt($semesterEnd)) {
                    $errors['registration_start_date'] =
                        'Registration window must fall within the semester period.';
                }

                // Ensure registration window is within session
                if ($regStart->lt($sessionStart) || $regEnd->gt($sessionEnd)) {
                    $errors['registration_end_date'] =
                        'Registration window must fall within the academic session period.';
                }

                // Ensure start < end
                if ($regEnd->lt($regStart)) {
                    $errors['registration_end_date'] =
                        'Registration end date must be after registration start date.';
                }
            }
        }

        // Semester name must be unique per session
        $exists = Semester::where('academic_session_id', $session->id)
            ->where('semester_name', $data['semester_name'])
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        if ($exists) {
            $errors['semester_name'] = 'This semester name already exists in the academic session.';
        }

        // Only one active semester per session
        if ($data['is_active']) {
            $activeExists = Semester::where('academic_session_id', $session->id)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->where('is_active', true)
                ->exists();

            if ($activeExists) {
                $errors['is_active'] = "Another semester is already active in this academic session({$session->session_name}).";
            }

            if (!$session->is_active) {
                $errors['is_active'] = "Cannot activate a semester under an inactive academic session({$session->session_name}).";
            }
        }

        // Prevent overlapping semesters
        $overlappingSemester = Semester::where('academic_session_id', $session->id)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('start_date', '<=', $semesterEnd)
            ->where('end_date', '>=', $semesterStart)
            ->first();

        if ($overlappingSemester) {
            $overlapStart = Carbon::parse($overlappingSemester->start_date)->format('Y/m/d');
            $overlapEnd   = Carbon::parse($overlappingSemester->end_date)->format('Y/m/d');

            $errors['overlap'] =
                "This semester overlaps with another semester: '{$overlappingSemester->semester_name}' ({$overlapStart} to {$overlapEnd}).";
        }

        return !empty($errors) ? $errors : null;
    }
}
