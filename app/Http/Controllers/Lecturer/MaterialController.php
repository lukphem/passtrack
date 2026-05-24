<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Course;
use App\Models\Material;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /**
     * Show all materials for a course
     */
    public function index(Request $request)
    {
        $lecturerId = auth()->user()->lecturer->id;

        // Lecturer courses
        $courses = Course::where('lecturer_id', $lecturerId)
            ->orderBy('course_code')
            ->get();

        // All sessions (for dropdown)
        $sessions = AcademicSession::orderBy('start_date', 'desc')->get();

        // Active session (default fallback)
        $activeSession = AcademicSession::where('is_active', true)->first();

        // Selected session (request → fallback → active)
        $selectedSessionId = $request->academic_session_id
            ?? $activeSession?->id;

        // Get semester tied to selected session
        $semester = Semester::where('academic_session_id', $selectedSessionId)
            ->where('is_active', true)
            ->first();

        // Selected course (safe)
        $selectedCourse = null;

        if ($request->course_id) {
            $selectedCourse = Course::where('id', $request->course_id)
                ->where('lecturer_id', $lecturerId)
                ->first();
        }

        // MATERIAL QUERY (CLEAN + PAGINATED)
        $materials = Material::with('course', 'semester')
            ->where(function ($q) use ($lecturerId) {
                $q->where('lecturer_id', $lecturerId);
            })

            ->when($request->course_id, function ($q) {
                $q->where('course_id', request('course_id'));
            })

            ->when($selectedSessionId, function ($q) use ($selectedSessionId) {
                $q->where('academic_session_id', $selectedSessionId);
            })

            ->when($request->search, function ($q) {
                $q->where('title', 'like', '%' . request('search') . '%');
            })

            ->when($request->type, function ($q) {
                $q->where('type', request('type'));
            })

            ->when($request->week, function ($q) {
                $q->where('week', request('week'));
            })

            ->latest()
            ->paginate(10);


        return view('lecturer.materials.index', [
            'courses' => $courses,
            'materials' => $materials,
            'course' => $selectedCourse,

            'sessions' => $sessions,
            'activeSession' => $activeSession,
            'selectedSessionId' => $selectedSessionId,
            'semester' => $semester,
        ]);
    }


    public function store(Request $request, Course $course)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'nullable|string',
        'week' => 'nullable|integer',
        'file' => 'nullable|file|max:20480',
        'external_link' => 'nullable|url',
    ]);

    if (!$request->file('file') && !$request->external_link) {
        return back()->withErrors([
            'file' => 'You must upload a file or provide an external link.'
        ]);
    }

    $activeSession = AcademicSession::where('is_active', true)->first();

    if (!$activeSession) {
        return back()->withErrors([
            'session' => 'No active academic session found.'
        ]);
    }
    $course = Course::where('id', $request->course_id)
        ->where('lecturer_id', auth()->user()->lecturer->id)
        ->first();


    // GET semester FROM COURSE (correct logic)
    $semester = Semester::where('semester_name', $course->semester)
        ->where('academic_session_id', $activeSession->id)
        ->first();
        
    if ($semester === null) {
        return back()->with('error', 'The course is not for this current semester.');
    }

    $filePath = null;
    $fileType = null;
    $fileSize = null;

    if ($request->hasFile('file')) {
        $file = $request->file('file');

        $filePath = $file->store('materials', 'public');
        $fileType = $file->getClientOriginalExtension();
        $fileSize = round($file->getSize() / 1024, 2) . ' KB';
    }

    Material::create([
        'course_id' => $course->id,
        'lecturer_id' => auth()->user()->lecturer->id,
        'academic_session_id' => $activeSession->id,

        'title' => $request->title,
        'description' => $request->description,
        'type' => $request->type ?? 'lecture_note',
        'week' => $request->week,

        // semester assignment
        'semester_id' => $semester->id,

        'file_path' => $filePath,
        'file_type' => $fileType,
        'file_size' => $fileSize,

        'external_link' => $request->external_link,
        'visibility' => 'published',
    ]);

    return back()->with('success', 'Material uploaded successfully.');
}



    public function update(Request $request, Material $material)
    {
        $lecturerId = auth()->user()->lecturer->id;

        if ($material->lecturer_id !== $lecturerId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'week' => 'nullable|integer',
            'file' => 'nullable|file|max:20480',
            'external_link' => 'nullable|url',
        ]);

        $filePath = $material->file_path;
        $fileType = $material->file_type;
        $fileSize = $material->file_size;

        // handle new file upload
        if ($request->hasFile('file')) {

            // delete old file
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }

            $file = $request->file('file');

            $filePath = $file->store('materials', 'public');
            $fileType = $file->getClientOriginalExtension();
            $fileSize = round($file->getSize() / 1024, 2) . ' KB';
        }

        $material->update([
            'course_id' => $request->course_id,
            'academic_session_id' => $request->academic_session_id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'week' => $request->week,

            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_size' => $fileSize,

            'external_link' => $request->external_link,
        ]);

        return redirect()
            ->route('lecturer.materials.index')
            ->with('success', 'Material updated successfully.');
    }

    /**
     * Delete material
     */
    public function destroy(Material $material)
    {
        $lecturerId = auth()->user()->lecturer->id;

        if ($material->lecturer_id !== $lecturerId) {
            abort(403, 'Unauthorized action.');
        }

        // delete file if exists
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()
            ->route('lecturer.materials.index')
            ->with('success', 'Material deleted successfully.');
    }
}
