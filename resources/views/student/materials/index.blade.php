@extends('student.layouts.app')

@section('content')

<div class="container py-3">

    {{-- HEADER --}}
    <div class="mb-4 border-bottom pb-2">
        <h4 class="fw-bold mb-1">Student Learning Portal</h4>

        <small class="text-muted">
            Session: {{ $activeSession->session_name ?? 'N/A' }} |
            Semester: {{ $activeSemester->semester_name ?? 'N/A' }}
        </small>
    </div>

    {{-- LEVEL SELECTOR --}}
    <form method="GET" class="mb-4">
        <label class="form-label fw-semibold">Select Level</label>

        <select name="level"
                class="form-select w-25"
                onchange="this.form.submit()">

            @foreach([100,200,300,400,500] as $lvl)
                <option value="{{ $lvl }}"
                    {{ $selectedLevel == $lvl ? 'selected' : '' }}>
                    {{ $lvl }} Level
                </option>
            @endforeach

        </select>
    </form>

    <div class="row g-3">

        {{-- LEFT PANEL --}}
        <div class="col-md-4">

            <div class="card shadow-sm border-0 p-3">

                <h6 class="fw-bold mb-3">📚 Courses</h6>

                @if($courses->count())

                    <div class="list-group list-group-flush">

                        @foreach($courses as $course)

                            <a href="?level={{ $selectedLevel }}&course_id={{ $course->id }}"
                               class="list-group-item list-group-item-action border-0">

                                <div class="fw-bold text-dark">
                                    {{ $course->course_code }}
                                </div>

                                <small class="text-muted">
                                    {{ $course->course_title }}
                                </small>

                            </a>

                        @endforeach

                    </div>

                @else
                    <div class="text-muted">
                        No courses found for this level.
                    </div>
                @endif

            </div>

        </div>

        {{-- RIGHT PANEL --}}
        <div class="col-md-8">

            @if($selectedCourse)

                {{-- COURSE HEADER --}}
                <div class="card shadow-sm border-0 p-3 mb-3">

                    <h5 class="fw-bold mb-0">
                        {{ $selectedCourse->course_code }} - {{ $selectedCourse->course_title }}
                    </h5>

                </div>

                {{-- TABS --}}
                <ul class="nav nav-tabs mb-3">

                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#materials">
                            Materials
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#assignments">
                            Assignments
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#quizzes">
                            Quizzes
                        </a>
                    </li>

                </ul>

                <div class="tab-content">

                    {{-- ================= MATERIALS ================= --}}
                    <div class="tab-pane fade show active" id="materials">

                        @forelse($materials as $week => $items)

                            <h6 class="mt-3 fw-bold text-primary">
                                Week {{ $week }}
                            </h6>

                            @foreach($items as $material)

                                @php
                                    $progress = $progressMap[$material->id] ?? null;
                                @endphp

                                <div class="card border-0 shadow-sm mb-2">

                                    <div class="card-body d-flex justify-content-between align-items-center">

                                        {{-- LEFT --}}
                                        <div style="max-width:70%">

                                            <div class="fw-bold">
                                                {{ $material->title }}
                                            </div>

                                            <small class="text-muted">
                                                {{ strtoupper($material->type) }} • Week {{ $material->week ?? 'N/A' }}
                                            </small>

                                            {{-- META --}}
                                            <div class="small text-muted mt-1">
                                                📅 Uploaded: {{ $material->created_at->format('d M Y') }}
                                                |
                                                ✏️ Updated: {{ $material->updated_at->format('d M Y') }}
                                            </div>

                                            {{-- PROGRESS BAR --}}
                                            <div class="mt-2 d-flex align-items-center gap-2">

                                                <div class="progress" style="height:6px; width:150px;">
                                                    <div class="progress-bar
                                                        @if(!$progress) bg-secondary
                                                        @elseif($progress->status === 'completed') bg-success
                                                        @elseif($progress->status === 'in_progress') bg-warning
                                                        @else bg-dark @endif"
                                                        style="width: {{ $progress->progress_percent ?? 0 }}%">
                                                    </div>
                                                </div>

                                                <small>
                                                    {{ $progress->progress_percent ?? 0 }}%
                                                </small>

                                            </div>

                                            {{-- SESSION COUNT --}}
                                            <small class="text-muted">
                                                🔁 Sessions: {{ $progress->session_count ?? 0 }}
                                            </small>

                                        </div>

                                        {{-- RIGHT --}}
                                        <div class="text-end">

                                            <span class="badge
                                                @if(!$progress) bg-secondary
                                                @elseif($progress->status === 'completed') bg-success
                                                @elseif($progress->status === 'in_progress') bg-warning
                                                @else bg-dark @endif">

                                                {{ ucfirst($progress->status ?? 'not_started') }}

                                            </span>

                                            <div class="mt-2">
                                                <a href="{{ route('student.material.view', $material->id) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-primary">
                                                    View
                                                </a>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        @empty
                            <p class="text-muted">No materials available.</p>
                        @endforelse

                    </div>

                    {{-- ================= ASSIGNMENTS ================= --}}
                    <div class="tab-pane fade" id="assignments">

                        @forelse($assignments as $assignment)

                            <div class="card border-0 shadow-sm mb-2 p-3">

                                <div class="fw-bold">
                                    {{ $assignment->title }}
                                </div>

                                <small class="text-muted">
                                    {{ $assignment->description }}
                                </small>

                                <div class="mt-2">
                                    <span class="badge bg-warning text-dark">
                                        Due: {{ $assignment->due_date ?? 'N/A' }}
                                    </span>
                                </div>

                            </div>

                        @empty
                            <p class="text-muted">No assignments available.</p>
                        @endforelse

                    </div>

                    {{-- ================= QUIZZES ================= --}}
                    <div class="tab-pane fade" id="quizzes">

                        @forelse($quizzes as $quiz)

                            <div class="card border-0 shadow-sm mb-2 p-3">

                                <div class="fw-bold">
                                    {{ $quiz->title }}
                                </div>

                                <small class="text-muted">
                                    {{ $quiz->description }}
                                </small>

                                <div class="mt-2">
                                    <button class="btn btn-sm btn-success">
                                        Start Quiz
                                    </button>
                                </div>

                            </div>

                        @empty
                            <p class="text-muted">No quizzes available.</p>
                        @endforelse

                    </div>

                </div>

            @else

                <div class="alert alert-info">
                    Select a course from the left panel to view learning materials.
                </div>

            @endif

        </div>

    </div>

</div>

@endsection
