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
                <span class="badge bg-{{ $isOpen ? 'success' : 'danger' }}">
                    {{ $isOpen ? 'OPEN' : 'CLOSED' }}
                </span>
            </div>

        </div>

    </div>
</div>

{{-- ================= FLASH MESSAGES ================= --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
        <strong>Success!</strong> {{ session('success') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
        <strong>Error!</strong> {{ session('error') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body d-flex justify-content-between align-items-center">

        <div>
            <strong>Total Courses:</strong> {{ $totalRegisteredCourses }}
            <span class="mx-2">|</span>
            <strong>Total Units:</strong>
            <span class="badge bg-primary">
                {{ $totalRegisteredUnits }}
            </span>
        </div>

        <div>
            <a href="{{ route('student.courses.print') }}"
               class="btn btn-outline-dark btn-sm"
               target="_blank">
                <i class="bi bi-printer"></i> Print Slip
            </a>
        </div>

    </div>
</div>

{{-- ================= MODE SWITCH ================= --}}
<div class="d-flex justify-content-between align-items-center mb-3">

    <div class="btn-group">

        <a href="{{ route('student.courses.available') }}"
           class="btn btn-sm {{ !request()->has('expand') ? 'btn-primary' : 'btn-outline-primary' }}">
            My Programme Courses
        </a>

        <a href="{{ route('student.courses.available', ['expand' => 1]) }}"
           class="btn btn-sm {{ request()->has('expand') ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
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
                        <option value="{{ $currentSemester->semester_name }}"
                            {{ request('semester') == $currentSemester->semester_name ? 'selected' : '' }}>
                            {{ $currentSemester->semester_name ?? 'N/A' }}
                        </option>
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
                    <th>Action</th>

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

                    <td>
                            @if($already)
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#dropModal"
                                        data-course-id="{{ $course->id }}"
                                        data-course-code="{{ $course->course_code }}">
                                    <i class="bi bi-trash"></i>
                                </button>

                        @else
                            <span class="text-muted">--</span>
                        @endif
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="{{ $isOpen ? 9 : 8 }}" class="text-center text-muted py-5">
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
                    Are you sure you want to drop
                    <strong id="courseCode"></strong>?
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Drop</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const dropModal = document.getElementById('dropModal');

    dropModal.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;

        const courseId = button.getAttribute('data-course-id');
        const courseCode = button.getAttribute('data-course-code');

        document.getElementById('courseCode').textContent = courseCode;

        document.getElementById('dropForm').action =
    "{{ route('student.courses.drop', ':id') }}".replace(':id', courseId);
    });

});
</script>

@endsection
