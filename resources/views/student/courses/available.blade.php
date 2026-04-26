@extends('student.layouts.app')

@section('title', 'Course Registration')

@section('content')

<div class="container py-4">

{{-- ================= HEADER ================= --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <div class="text-center mb-3">
            <h4 class="fw-bold mb-0">Course Registration</h4>
            <small class="text-muted">Register your courses for the current semester</small>
        </div>

        <div class="row g-2 small">

            <div class="col-md-3"><strong>Student:</strong> {{ $user_names }}</div>
            <div class="col-md-3"><strong>Matric:</strong> {{ $student->matric_no }}</div>
            <div class="col-md-3"><strong>Level:</strong> {{ $student->level }}</div>
            <div class="col-md-3"><strong>Type:</strong> {{ $programme->programme_level_type }}</div>

            <div class="col-md-3"><strong>Programme:</strong> {{ $programme->programme_name }}</div>
            <div class="col-md-3"><strong>Session:</strong> {{ $currentSession->session_name ?? 'N/A' }}</div>
            <div class="col-md-3"><strong>Semester:</strong> {{ $currentSemester->semester_name ?? 'N/A' }}</div>

            <div class="col-md-3">
                <strong>Status:</strong>
                <span class="badge bg-{{ isset($setting) && $setting->registration_allowed ? 'success' : 'danger' }}">
                    {{ isset($setting) && $setting->registration_allowed ? 'OPEN' : 'CLOSED' }}
                </span>
            </div>

        </div>

    </div>
</div>

{{-- ================= FLASH MESSAGES ================= --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
{{-- ================= MODE SWITCH ================= --}}
<div class="d-flex justify-content-between align-items-center mb-3">

    <div class="btn-group">

        <a href="{{ route('student.courses.available') }}"
           class="btn btn-sm {{ !request()->has('expand') ? 'btn-primary' : 'btn-outline-primary' }}">
            My Programme Courses
        </a>

        <a href="{{ route('student.courses.available', ['expand' => 1]) }}"
           class="btn btn-sm {{ request()->has('expand') ? 'btn-warning' : 'btn-outline-warning' }}">
            Expand Mode (Carry Over / Extra Courses)
        </a>

    </div>

</div>

{{-- ================= FILTER (EXPAND ONLY) ================= --}}
@if(request()->has('expand'))

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <form method="GET" action="{{ route('student.courses.available') }}">
            <input type="hidden" name="expand" value="1">

            <div class="row g-2 align-items-end">

                {{-- DEPARTMENT --}}
                <div class="col-md-4">
                    <label class="form-label small">Department</label>
                    <select name="department_id" class="form-select form-select-sm">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}"
                                {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->dept_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- LEVEL --}}
                <div class="col-md-3">
                    <label class="form-label small">Level</label>
                    <select name="level" class="form-select form-select-sm">
                        <option value="">All Levels</option>
                        @for($i = 100; $i <= $student->level; $i += 100)
                            <option value="{{ $i }}" {{ request('level') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- SEMESTER --}}
                <div class="col-md-3">
                    <label class="form-label small">Semester</label>
                    <select name="semester" class="form-select form-select-sm">
                        <option value="">All Semesters</option>
                        <option value="First" {{ request('semester') == 'First' ? 'selected' : '' }}>First</option>
                        <option value="Second" {{ request('semester') == 'Second' ? 'selected' : '' }}>Second</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100">
                        Load
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>

@endif

{{-- ================= COURSES TABLE ================= --}}
<form method="POST" action="{{ route('student.courses.register') }}">
@csrf

<div class="card border-0 shadow-sm">

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-dark">
                <tr>
                    <th width="40"></th>
                    <th>Code</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Level</th>
                    <th>Semester</th>
                    <th>Credit</th>
                    <th>Lecturer</th>
                </tr>
            </thead>

            <tbody>

            @forelse($courses as $course)

                @php
                    $already = in_array($course->id, $registeredCourseIds ?? []);
                @endphp

                <tr class="{{ $already ? 'table-success' : '' }}">

                    <td>
                        <input type="checkbox"
                               name="course_ids[]"
                               value="{{ $course->id }}"
                               class="form-check-input"
                               {{ $already ? 'checked disabled' : '' }}>
                    </td>

                    <td class="fw-bold">{{ $course->course_code }}</td>
                    <td>{{ $course->course_title }}</td>

                    <td>
                        <span class="badge bg-{{ $course->course_type == 'Core' ? 'primary' : 'warning' }}">
                            {{ $course->course_type }}
                        </span>
                    </td>

                    <td>{{ $course->level }}</td>
                    <td>{{ $course->semester }}</td>

                    <td>
                        <span class="badge bg-secondary">
                            {{ $course->credit_unit }}
                        </span>
                    </td>

                    <td>
                        {{ $course->lecturer->first_name ?? '' }}
                        {{ $course->lecturer->last_name ?? '' }}
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        No courses available for this selection
                    </td>
                </tr>
            @endforelse

            </tbody>

        </table>

    </div>

</div>

{{-- SUBMIT --}}
<div class="text-end mt-3">
    <button type="submit" class="btn btn-success px-4">
        Register Selected Courses
    </button>
</div>

</form>

</div>

@endsection
