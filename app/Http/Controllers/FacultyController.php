<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{


    /**
     * Display a listing of the faculties.
     */
    public function index()
    {
        // Eager load departments and students count for display
        $faculties = Faculty::withCount(['departments', 'students'])->get();

        return view('admin.faculties.index', compact('faculties'));
    }

    /**
     * Show the form for creating a new faculty.
     */
    public function create()
    {
        return view('admin.dashboard.faculties.create');
    }

    /**
     * Store a newly created faculty in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'code'             => 'required|string|max:50|unique:faculties,code',
            'dean'             => 'nullable|string|max:255',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'description'      => 'nullable|string',
        ]);

        Faculty::create($validated);

        return redirect()->route('admin.faculties.index')
                         ->with('success', 'Faculty created successfully.');
    }

    /**
     * Display the specified faculty.
     */
    public function show(Faculty $faculty)
    {
        $faculty->loadCount(['departments', 'students']);
        return view('admin.dashboard.faculties.show', compact('faculty'));
    }

    /**
     * Show the form for editing the specified faculty.
     */
    public function edit(Faculty $faculty)
    {
        return view('admin.dashboard.faculties.edit', compact('faculty'));
    }

    /**
     * Update the specified faculty in storage.
     */
    public function update(Request $request, Faculty $faculty)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'code'             => 'required|string|max:10|unique:faculties,code,' . $faculty->id,
            'dean'             => 'nullable|string|max:255',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'description'      => 'nullable|string',
        ]);

        $faculty->update($validated);

        return redirect()->route('admin.faculties.index')
                         ->with('success', 'Faculty updated successfully.');
    }

    /**
     * Remove the specified faculty from storage.
     */
    public function destroy(Faculty $faculty)
    {
        $faculty->delete();

        return redirect()->route('admin.faculties.index')
                         ->with('success', 'Faculty deleted successfully.');
    }


}
