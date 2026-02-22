<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Programme;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgrammeController extends Controller
{
    /**
     * Display a listing of the resource with search and pagination.
     */
    public function index(Request $request)
    {
        try {
            $query = Programme::with('department')->latest();

            // Handle soft delete filtering
            if ($request->status === 'trashed') {
                $query->onlyTrashed();
            } elseif ($request->status === 'all') {
                $query->withTrashed();
            }

            // Search filter
            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('programme_name', 'like', "%{$search}%")
                      ->orWhere('programme_code', 'like', "%{$search}%")
                      ->orWhere('programme_level_type', 'like', "%{$search}%")
                      ->orWhere('accreditation_status', 'like', "%{$search}%")
                      ->orWhereHas('department', function ($q2) use ($search) {
                          $q2->where('dept_name', 'like', "%{$search}%");
                      });
                });
            }

            $programmes = $query->paginate(10)->withQueryString();
            $departments = Department::all();

            return view('admin.programmes.index', compact('programmes', 'departments'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to fetch programmes: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'programme_name'               => 'required|string|max:255',
        'programme_code'               => 'required|string|max:50|unique:programmes,programme_code',
        'programme_duration'           => 'required|integer|min:1|max:10',
        'industrial_training_required' => 'sometimes|boolean',
        'industrial_training_level'    => 'nullable|integer|min:1|max:500',
        'programme_level_type'         => 'nullable|in:Undergraduate,Postgraduate',
        'programme_start_date'         => 'nullable|date',
        'programme_description'        => 'nullable|string',
        'accreditation_status'         => 'nullable|in:Full,Interim,None',
        'accreditation_year'           => 'nullable|digits:4|integer|min:2000|max:' . date('Y'),
        'programme_status'             => 'sometimes|boolean',
        'department_id'                => 'required|exists:departments,id',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('add_programme_error', true); // <-- must set this
    }

    $data = $validator->validated();
    $data['industrial_training_required'] = $request->has('industrial_training_required');
    $data['programme_status'] = $request->has('programme_status');

    Programme::create($data);

    return redirect()->route('admin.programmes.index')->with('success', 'Programme created successfully.');
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Programme $programme)
    {
        $validated = $request->validate([
            'programme_name'               => 'required|string|max:255',
            'programme_code'               => 'required|string|max:50|unique:programmes,programme_code,' . $programme->id,
            'programme_duration'           => 'required|integer|min:1|max:10',
            'industrial_training_required' => 'sometimes|boolean',
            'industrial_training_level'    => 'nullable|integer|min:1|max:500',
            'programme_level_type'         => 'nullable|in:Undergraduate,Postgraduate',
            'programme_start_date'         => 'nullable|date',
            'programme_description'        => 'nullable|string',
            'accreditation_status'         => 'nullable|in:Full,Interim,None',
            'accreditation_year'           => 'nullable|digits:4|integer|min:2000|max:' . date('Y'),
            'programme_status'             => 'sometimes|boolean',
            'department_id'                => 'required|exists:departments,id',
        ]);

        try {
            $validated['industrial_training_required'] = $request->has('industrial_training_required');
            $validated['programme_status'] = $request->has('programme_status');

            $programme->update($validated);

            return redirect()
                ->route('admin.programmes.index')
                ->with('success', 'Programme updated successfully.');

        } catch (QueryException $qe) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Database error: ' . $qe->getMessage())
                ->with('edit_programme_error', true)
                ->with('programme_id', $programme->id);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update programme: ' . $e->getMessage())
                ->with('edit_programme_error', true)
                ->with('programme_id', $programme->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Programme $programme)
    {
        try {
            $programme->delete();

            return redirect()
                ->route('admin.programmes.index')
                ->with('success', 'Programme deleted successfully.');

        } catch (QueryException $qe) {
            return redirect()->back()
                ->with('error', 'Database error: ' . $qe->getMessage());

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete programme: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Programme $programme)
    {
        try {
            $programme->load('department');

            return view('admin.programmes.show', compact('programme'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to fetch programme details: ' . $e->getMessage());
        }
    }
}
