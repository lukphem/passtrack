<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Programme;
use App\Models\ProgrammeAcademicSetting;
use App\Models\AcademicSession;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgrammeController extends Controller
{
    /**
     * Display a listing of programmes.
     */
    public function index(Request $request)
    {
        $query = Programme::with(['department', 'currentSession', 'currentSemester']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('programme_name', 'like', "%{$search}%")
                  ->orWhere('programme_code', 'like', "%{$search}%")
                  ->orWhere('programme_level_type', 'like', "%{$search}%")
                  ->orWhere('accreditation_status', 'like', "%{$search}%")
                  ->orWhereHas('department', fn($q2) => $q2->where('dept_name', 'like', "%{$search}%"));
            });
        }

        $programmes = $query->latest()->paginate(6)->withQueryString();
        $departments = Department::all();
        $sessions = AcademicSession::all();
        $semesters = Semester::all();

        return view('admin.programmes.index', compact('programmes', 'departments', 'sessions', 'semesters'));
    }

    /**
     * Store a newly created programme.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'programme_name'        => 'required|string|max:255',
                'programme_code'        => 'required|string|max:50|unique:programmes,programme_code',
                'programme_duration'    => 'required|integer|min:1|max:10',
                'programme_level_type'  => 'nullable|in:Undergraduate,Postgraduate',
                'programme_start_date'  => 'nullable|date',
                'programme_description' => 'nullable|string',
                'industrial_training_required' => 'nullable|boolean',
                'industrial_training_level'    => 'nullable|integer|min:1|max:500|required_if:industrial_training_required,1',
                'accreditation_status' => 'nullable|in:Full,Interim,None',
                'accreditation_year'   => 'nullable|integer|digits:4|min:2000|max:' . date('Y'),
                'programme_status' => 'nullable|boolean',
                'department_id' => 'required|exists:departments,id',
                'use_custom_academic_settings' => 'nullable|boolean',
            ]);
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('add_programme_error', true);
        }

        DB::transaction(function () use ($request, $data) {
            // ALWAYS GET DEFAULT ACTIVE
            $currentSemester = Semester::where('is_active', true)->first();
            $data['industrial_training_required'] = $request->boolean('industrial_training_required');
            $data['programme_status'] = $request->boolean('programme_status');
            $data['use_custom_academic_settings'] = $request->boolean('use_custom_academic_settings');
            // (optional: store on programme table if needed)
            $data['current_session_id'] = $currentSemester->academic_session_id ?? null;
            $data['current_semester_id'] = $currentSemester->id ?? null;

            // CREATE PROGRAMME
            $programme = Programme::create($data);

            // ALWAYS CREATE SETTINGS
            ProgrammeAcademicSetting::create([
                'programme_id' => $programme->id,
                'academic_session_id' => $currentSemester->academic_session_id,
                'semester_id' => $currentSemester->id,
                'registration_allowed' => $currentSemester->registration_allowed,
                'start_date' => $currentSemester->start_date,
                'end_date' => $currentSemester->end_date,
                'registration_start_date' => $currentSemester->registration_start_date,
                'registration_end_date' => $currentSemester->registration_end_date,
            ]);
        });

            return redirect()
                ->route('admin.programmes.index')
                ->with('success', 'Programme created successfully.');
    }

    /**
     * Update the specified programme.
     */
    public function update(Request $request, Programme $programme)
    {
        try {
            $data = $request->validate([
                'programme_name' => 'required|string|max:255',
                'programme_code' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('programmes')->ignore($programme->id),
                ],
                'programme_duration'   => 'required|integer|min:1|max:10',
                'programme_level_type' => 'nullable|in:Undergraduate,Postgraduate',
                'programme_start_date' => 'nullable|date',
                'programme_description'=> 'nullable|string',
                'industrial_training_required' => 'nullable|boolean',
                'industrial_training_level'    => 'nullable|integer|min:1|max:500|required_if:industrial_training_required,1',
                'accreditation_status' => 'nullable|in:Full,Interim,None',
                'accreditation_year'   => 'nullable|integer|digits:4|min:2000|max:' . date('Y'),
                'programme_status' => 'nullable|boolean',
                'department_id' => 'required|exists:departments,id',
                'use_custom_academic_settings' => 'nullable|boolean',
            ]);
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('edit_programme_error', $programme->id);
        }

        DB::transaction(function () use ($programme, $request, $data) {

        $currentSemester = Semester::where('is_active', true)->first();

        $data['industrial_training_required'] = $request->boolean('industrial_training_required');
        $data['programme_status'] = $request->boolean('programme_status');
        $data['use_custom_academic_settings'] = $request->boolean('use_custom_academic_settings');

        // Default system session (fallback)
        $data['current_session_id'] = $currentSemester->academic_session_id;
        $data['current_semester_id'] = $currentSemester->id;

        // PROGRAMME
        $programme->update($data);

        /*
        |--------------------------------------------------------------------------
        | CUSTOM SETTINGS LOGIC
        |--------------------------------------------------------------------------
        */

        if (!$data['use_custom_academic_settings']) {

            // IF DISABLED → DELETE SETTINGS
            ProgrammeAcademicSetting::where('programme_id', $programme->id)->delete();

        } else {
        
            ProgrammeAcademicSetting::updateOrCreate(
                ['programme_id' => $programme->id],
                [
                    'academic_session_id' => $request->academic_session_id ?? $currentSemester->academic_session_id,
                    'semester_id' => $request->semester_id ?? $currentSemester->id,
                    'registration_allowed' => $request->boolean('registration_allowed'),
                    'start_date' => $request->start_date ?? $currentSemester->start_date,
                    'end_date' => $request->end_date ?? $currentSemester->end_date,
                    'registration_start_date' => $request->registration_start_date ?? $currentSemester->registration_start_date,
                    'registration_end_date' => $request->registration_end_date ?? $currentSemester->registration_end_date,
                ]
            );


        }


    });

        return redirect()
            ->route('admin.programmes.index')
            ->with('success', 'Programme updated successfully.');
    }



    /**
     * Remove the specified programme.
     */
    public function destroy(Programme $programme)
    {
        DB::transaction(function () use ($programme) {
            $programme->delete();
        });

        return redirect()
            ->route('admin.programmes.index')
            ->with('success', 'Programme deleted successfully.');
    }



    /**
     * Display the specified programme.
     */
    public function show(Programme $programme)
    {
        $programme->load(['department', 'currentSession', 'currentSemester']);
        return view('admin.programmes.show', compact('programme'));
    }



        public function customSettings(Request $request)
    {
       $query = ProgrammeAcademicSetting::with(['programme.department','academicSession','semester',
    ])
    ->join('programmes', 'programme_academic_settings.programme_id', '=', 'programmes.id')
    ->select(
        'programme_academic_settings.*',
        'programmes.programme_name',
        'programmes.programme_code',
        'programmes.programme_level_type',
        'programmes.accreditation_status'
    );

if ($request->filled('search')) {
    $search = $request->search;

    $query->where(function ($q) use ($search) {
        $q->where('programmes.programme_name', 'like', "%{$search}%")
          ->orWhere('programmes.programme_code', 'like', "%{$search}%")
          ->orWhere('programmes.programme_level_type', 'like', "%{$search}%")
          ->orWhere('programmes.accreditation_status', 'like', "%{$search}%")
          ->orWhereHas('programme.department', function ($q2) use ($search) {
              $q2->where('dept_name', 'like', "%{$search}%");
          });
    });
    }

        $programmes = $query->latest()->with([
            'programme.department',
            'semester.academicSession',
        ])->paginate(12);

        $sessions = AcademicSession::all();
        $semesters =  Semester::all();

        return view('admin.programmes.custom-settings', compact('programmes', 'sessions', 'semesters'));
    }



        public function updateCustomSettings(Request $request, $programmeId)
    {
        // VALIDATION
        $validator = Validator::make($request->all(), [
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'semester_id' => 'required|exists:semesters,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'registration_start_date' => 'required|date',
            'registration_end_date' => 'required|date|after_or_equal:registration_start_date',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_programme_id', $programmeId);
        }

        // FETCH EXISTING SETTING
        $setting = ProgrammeAcademicSetting::find($programmeId);
        if (!$setting) {
            return back()->with('error', 'No academic settings found for this programme.');
        }

        // PARSE DATES
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : null;
        $regStart = $request->registration_start_date ? Carbon::parse($request->registration_start_date) : null;
        $regEnd = $request->registration_end_date ? Carbon::parse($request->registration_end_date) : null;

        // ENSURE SEMESTER BELONGS TO SESSION
        if ($request->semester_id && $request->academic_session_id) {
            $semester = Semester::find($request->semester_id);
            if ($semester && $semester->academic_session_id != $request->academic_session_id) {
                return back()
                    ->withErrors(['semester_id' => 'Selected semester does not belong to the chosen academic session.'])
                    ->withInput()
                    ->with('error_programme_id', $programmeId);
            }
        }

        // ENSURE REGISTRATION IS WITHIN ACADEMIC PERIOD
        if ($startDate && $endDate) {
            if ($regStart && $regStart->lt($startDate)) {
                return back()
                    ->withErrors(['registration_start_date' => 'Registration cannot start before academic start date.'])
                    ->withInput()
                    ->with('error_programme_id', $programmeId);
            }

            if ($regEnd && $regEnd->gt($endDate)) {
                return back()
                    ->withErrors(['registration_end_date' => 'Registration cannot end after academic end date.'])
                    ->withInput()
                    ->with('error_programme_id', $programmeId);
            }
        }

        // UPDATE EXISTING RECORD
        $setting->academic_session_id = $request->academic_session_id;
        $setting->semester_id = $request->semester_id;
        $setting->start_date = $startDate;
        $setting->end_date = $endDate;
        $setting->registration_allowed = $request->boolean('registration_allowed') && $regStart && $regEnd;
        $setting->registration_start_date = $regStart;
        $setting->registration_end_date = $regEnd;
        $setting->save();

        return back()->with('success', 'Custom academic settings updated successfully.');
    }
}
