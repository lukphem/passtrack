@extends('lecturer.layouts.app')

@section('content')

@php
    $selectedCourse = $courses->firstWhere('id', request('course_id'));
@endphp

<div class="container py-4">

    {{-- ================= HEADER ================= --}}
    <div class="mb-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">

            <div>

                <h4 class="fw-bold mb-1">
                    @if($selectedCourse)
                        {{ $selectedCourse->course_code }} — {{ $selectedCourse->course_title }}
                    @else
                        All My Courses
                    @endif
                </h4>

                <small class="text-muted">
                    Students offering your courses
                </small>

            </div>

            <div class="alert alert-info py-2 mb-3">

                <div>
                    <strong>Session:</strong> {{ $activeSession->session_name ?? 'N/A' }} |
                    <strong>Semester:</strong> {{ $activeSemester->semester_name ?? 'N/A' }}
                </div>

            </div>

        </div>

        {{-- ================= SUMMARY CARDS ================= --}}
        <div class="row mt-3 g-3">

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Total Students</h6>
                        <h3 class="fw-bold mb-0">{{ $students->total() }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Filtered Result</h6>
                        <h3 class="fw-bold mb-0">{{ $students->count() }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex align-items-stretch">
                <div class="card shadow-sm border-0 w-100">
                    <div class="card-body d-flex align-items-center justify-content-between">

                        <div>
                            <h6 class="text-muted mb-1">Export Data</h6>
                            <small class="text-muted">Download current filter</small>
                        </div>

                        <a href="{{ route('lecturer.students.export', request()->all()) }}"
                           class="btn btn-success btn-sm">
                            Export
                        </a>

                    </div>
                </div>
            </div>

        </div>

    </div>


    {{-- ================= FILTERS ================= --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-3">
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search name or matric..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <select name="course_id" class="form-select">
                            <option value="">All My Courses</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}"
                                    {{ request('course_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->course_code }} — {{ $c->course_title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="programme_id" class="form-select">
                            <option value="">Programme</option>
                            @foreach($programmes as $programme)
                                <option value="{{ $programme->id }}"
                                    {{ request('programme_id') == $programme->id ? 'selected' : '' }}>
                                    {{ $programme->programme_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="level" class="form-select">
                            <option value="">Level</option>
                            @foreach([100,200,300,400] as $lvl)
                                <option value="{{ $lvl }}" {{ request('level') == $lvl ? 'selected' : '' }}>
                                    {{ $lvl }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="session_id" class="form-select">
                            <option value="">Session</option>
                            @foreach($sessions as $s)
                                <option value="{{ $s->id }}"
                                    {{ request('session_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->session_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-1 d-grid">
                        <button class="btn btn-primary">
                            Filter
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>


    {{-- ================= TABLE ================= --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr class="text-uppercase small text-muted">
                            <th>#</th>
                            <th>Matric</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Programme</th>
                            <th>Level</th>
                            <th>Session</th>
                            <th>Semester</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($students as $index => $student)

                            <tr>

                                <td>{{ $students->firstItem() + $index }}</td>

                                <td class="fw-semibold">{{ $student->matric_no }}</td>

                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>

                                {{-- ================= COURSES (FIXED) ================= --}}
                                <td>
                                    <div class="fw-semibold">

                                        @php
                                            $codes = array_filter(explode(', ', $student->courses ?? ''));
                                            $titles = explode(', ', $student->course_titles ?? '');
                                        @endphp

                                        @forelse($codes as $i => $code)
                                            <div class="mb-1">
                                                {{ $code }}
                                                <span class="text-muted">
                                                    - {{ $titles[$i] ?? '' }}
                                                </span>
                                            </div>
                                        @empty
                                            <span class="text-muted">No courses</span>
                                        @endforelse

                                    </div>
                                </td>

                                <td>{{ $student->programme_name ?? 'N/A' }}</td>

                                <td>{{ $student->level }}</td>

                                <td>{{ $activeSession->session_name ?? 'N/A' }}</td>

                                <td>{{ $activeSemester->semester_name ?? 'N/A' }}</td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    No students found for your courses.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ================= PAGINATION ================= --}}
    <div class="mt-3">
        {{ $students->links() }}
    </div>

</div>

@endsection
