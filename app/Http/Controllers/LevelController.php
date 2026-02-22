<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Programme;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Display a listing of levels.
     * Optional: Filter by programme if passed.
     */
    public function index(Request $request)
    {
        // If a programme filter exists
        $programmeId = $request->query('programme_id');

        $levels = Level::when($programmeId, function ($query, $programmeId) {
            return $query->where('programme_id', $programmeId);
        })
        ->with('programme')
        ->latest()
        ->paginate(10);

        $programmes = Programme::where('programme_status', true)->get();

        return view('admin.levels.index', compact('levels', 'programmes'));
    }

    /**
     * Show the form for creating a new level.
     */
    public function create()
    {
        $programmes = Programme::where('programme_status', true)->get();

        return view('admin.levels.create', compact('programmes'));
    }

    /**
     * Store a newly created level in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'level_name' => 'required|string|max:255',
            'level_code' => 'required|string|max:50|unique:levels,level_code',
            'programme_id' => 'required|exists:programmes,id',
        ]);

        Level::create($request->all());

        return redirect()
            ->route('levels.index')
            ->with('success', 'Level created successfully.');
    }

    /**
     * Display the specified level.
     */
    public function show(Level $level)
    {
        $level->load('programme');

        return view('admin.levels.show', compact('level'));
    }

    /**
     * Show the form for editing the specified level.
     */
    public function edit(Level $level)
    {
        $programmes = Programme::where('programme_status', true)->get();

        return view('admin.levels.edit', compact('level', 'programmes'));
    }

    /**
     * Update the specified level in storage.
     */
    public function update(Request $request, Level $level)
    {
        $request->validate([
            'level_name' => 'required|string|max:255',
            'level_code' => 'required|string|max:50|unique:levels,level_code,' . $level->id,
            'programme_id' => 'required|exists:programmes,id',
        ]);

        $level->update($request->all());

        return redirect()
            ->route('levels.index')
            ->with('success', 'Level updated successfully.');
    }

    /**
     * Remove the specified level from storage.
     */
    public function destroy(Level $level)
    {
        $level->delete();

        return redirect()
            ->route('levels.index')
            ->with('success', 'Level deleted successfully.');
    }
}
