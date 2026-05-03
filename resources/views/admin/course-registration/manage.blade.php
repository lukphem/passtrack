@extends('admin.dashboard.layouts.admin')

@section('content')

<div class="container py-4">

{{-- ================= HEADER ================= --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <div class="text-center mb-3">
            <h4 class="fw-bold mb-0">Admin Course Registration</h4>
            <small class="text-muted">Override / Amend Student Courses</small>
        </div>

        <div class="row g-2 small text-muted">
            <div class="col-md-3"><strong>Student:</strong> {{ $student->user->first_name }} {{ $student->user->last_name }}</div>
            <div class="col-md-3"><strong>Matric:</strong> {{ $student->matric_no }}</div>
            <div class="col-md-3"><strong>Level:</strong> {{ $student->level }}</div>
            <div class="col-md-3"><strong>Programme:</strong> {{ $programme->programme_name ?? 'N/A' }}</div>

            <div class="col-md-3 mt-2"><strong>Session:</strong> {{ $session->session_name ?? 'N/A' }}</div>
            <div class="col-md-3 mt-2"><strong>Semester:</strong> {{ $semester->semester_name ?? 'N/A' }}</div>
        </div>

    </div>
</div>

{{-- ================= SUMMARY + PRINT ================= --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body d-flex justify-content-between align-items-center">

        <div>
            <strong>Total Courses:</strong> {{ count($registeredCourseIds ?? []) }}
            <span class="mx-2">|</span>
            <strong>Total Units:</strong>
            <span class="badge bg-primary">{{ $totalUnits ?? 0 }}</span>
        </div>

        <a href="{{ route('admin.course-registration.print', [
            'student' => $student->id,
            'session_id' => $session->id,
            'semester_id' => $semester->id
        ]) }}"
           target="_blank"
           class="btn btn-outline-dark btn-sm">
            <i class="bi bi-printer"></i> Print Slip
        </a>

    </div>
</div>

{{-- ================= MODE SWITCH ================= --}}
<div class="d-flex justify-content-between align-items-center mb-3">

    <div class="btn-group">

        <a href="{{ route('admin.course-registration.manage', [
            'student' => $student->id,
            'session_id' => $session->id,
            'semester_id' => $semester->id
        ]) }}"
           class="btn btn-sm {{ !request()->has('expand') ? 'btn-primary' : 'btn-outline-primary' }}">
            Programme Courses
        </a>

        <a href="{{ route('admin.course-registration.manage', [
            'student' => $student->id,
            'expand' => 1,
            'session_id' => $session->id,
            'semester_id' => $semester->id
        ]) }}"
           class="btn btn-sm {{ request()->has('expand') ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
            Expand Mode(Carry Over/Extra Courses)
        </a>

    </div>

</div>

{{-- ================= FILTER (EXPAND ONLY) ================= --}}
@if(request()->has('expand'))
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">

        <form method="GET" action="{{ route('admin.course-registration.manage', $student->id) }}">
            <input type="hidden" name="expand" value="1">
            <input type="hidden" name="session_id" value="{{ $session->id }}">
            <input type="hidden" name="semester_id" value="{{ $semester->id }}">

            <div class="row g-2">

                <div class="col-md-4">
                    <select name="department_id" class="form-select form-select-sm">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->dept_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="level" class="form-select form-select-sm">
                        <option value="">All Levels</option>
                        @for($i=100; $i<=500; $i+=100)
                            <option value="{{ $i }}" {{ request('level') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100">Load</button>
                </div>

            </div>
        </form>

    </div>
</div>
@endif

{{-- ================= ALERTS ================= --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
        {{ session('success') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
        {{ session('error') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ================= COURSES TABLE ================= --}}
<form method="POST" action="{{ route('admin.course-registration.addcourse', $student->id) }}">
@csrf

<input type="hidden" name="session_id" value="{{ $session->id }}">
<input type="hidden" name="semester_id" value="{{ $semester->id }}">

<div class="card shadow-sm border-0">
    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th></th>
                    <th>Code</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Level</th>
                    <th>Credit</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
            @forelse($courses as $course)

                @php
                    $registered = in_array($course->id, $registeredCourseIds ?? []);
                    $isProgrammeCourse = $course->programme_id == $student->programme_id;
                    $isValidLevel = $course->level <= $student->level;

                    $showCourse = request()->has('expand')
                        ? true
                        : ($registered || ($isProgrammeCourse && $isValidLevel));
                @endphp

                @if($showCourse)
                <tr class="{{ $registered ? 'table-success' : '' }}">

                    <td>
                        <input type="checkbox"
                               name="course_ids[]"
                               value="{{ $course->id }}"
                               {{ $registered ? 'checked disabled' : '' }}>
                    </td>

                    <td class="fw-bold">{{ $course->course_code }}</td>
                    <td>{{ $course->course_title }}</td>

                    <td>
                        <span class="badge bg-{{ $course->course_type == 'Core' ? 'primary' : 'warning' }}">
                            {{ $course->course_type }}
                        </span>
                    </td>

                    <td>{{ $course->level }}</td>

                    <td><span class="badge bg-secondary">{{ $course->credit_unit }}</span></td>

                    <td>
                        @if($registered)
                            <span class="badge bg-success">Registered</span>
                        @else
                            <span class="badge bg-light text-dark">Available</span>
                        @endif
                    </td>

                    <td>
                        @if($registered)
                        <button type="button"
                                class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#dropModal"
                                data-course-id="{{ $course->id }}"
                                data-course-code="{{ $course->course_code }}">
                            <i class="bi bi-trash"></i>
                        </button>
                        @endif
                    </td>

                </tr>
                @endif

            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        No courses available
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>

    </div>
</div>

<div class="mt-3 d-flex justify-content-between">
    <a href="{{ route('admin.course-registration.index') }}" class="btn btn-secondary">
        Back
    </a>

    <button type="submit" class="btn btn-primary px-4">
        Update Registration
    </button>
</div>

</form>

</div>

{{-- ================= DROP MODAL ================= --}}
<div class="modal fade" id="dropModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" id="dropForm">
                @csrf
                @method('DELETE')

                <div class="modal-header">
                    <h5 class="modal-title">Drop Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Drop <strong id="courseCode"></strong>?<br><br>
                    <small class="text-muted">
                        {{ $student->user->first_name }} {{ $student->user->last_name }} ({{ $student->matric_no }})<br>
                        {{ $session->session_name }} | {{ $semester->semester_name }}
                    </small>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger">Drop</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('dropModal').addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;

        document.getElementById('courseCode').textContent =
            button.getAttribute('data-course-code');

        document.getElementById('dropForm').action =
            "{{ route('admin.course-registration.dropcourse', ['student' => $student->id, 'course' => ':id']) }}"
            .replace(':id', button.getAttribute('data-course-id'));
    });

});
</script>

@endsection
