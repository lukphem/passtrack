@extends('student.layouts.app')

@section('title', 'Course Registration')

@section('content')

<style>
.page-box {
    background: #fff;
    border-radius: 14px;
    padding: 15px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.05);
}

.header-bar {
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    margin-bottom:15px;
}

.badge-soft {
    padding:6px 10px;
    border-radius:20px;
    font-size:12px;
}
</style>

<div class="container-fluid">

{{-- HEADER --}}
<div class="page-box mb-3">

    <div class="header-bar">

        <div>
            <h5 class="mb-1">Course Registration</h5>
            <small class="text-muted">
                {{ $currentSession->session_name ?? '-' }} |
                {{ $currentSemester->semester_name ?? '-' }}
            </small>
        </div>

        <div>
            @php
                $regStatus = $currentSemester->registration_allowed ? 'OPEN' : 'CLOSED';
                $regColor = $currentSemester->registration_allowed ? 'success' : 'danger';
            @endphp

            <span class="badge bg-{{ $regColor }}">
                {{ $regStatus }}
            </span>
        </div>

    </div>

</div>

{{-- SUMMARY --}}
<div class="row g-3 mb-3">

    <div class="col-md-3">
        <div class="stat-card bg-cyan">
            <div>Total Courses</div>
            <h5>{{ $totalCourses ?? 0 }}</h5>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card bg-cyan">
            <div>Registered</div>
            <h5>{{ $registeredCourses ?? 0 }}</h5>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card bg-cyan">
            <div>Pending</div>
            <h5>{{ $pendingCourses ?? 0 }}</h5>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card bg-cyan">
            <div>Credit Load</div>
            <h5>{{ $creditLoad ?? 0 }}</h5>
        </div>
    </div>

</div>

{{-- MAIN TABLE --}}
<div class="page-box">

    <table class="table table-hover align-middle">

        <thead>
            <tr>
                <th>Course Code</th>
                <th>Title</th>
                <th>Unit</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        @forelse($courses as $course)

            @php
                $isRegistered = in_array($course->id, $registeredCourseIds ?? []);
            @endphp

            <tr>

                <td>
                    <strong>{{ $course->course_code }}</strong>
                </td>

                <td>
                    {{ $course->course_title }}
                </td>

                <td>
                    <span class="badge bg-primary">
                        {{ $course->credit_unit }}
                    </span>
                </td>

                <td>
                    @if($isRegistered)
                        <span class="badge bg-success">Registered</span>
                    @else
                        <span class="badge bg-secondary">Not Registered</span>
                    @endif
                </td>

                <td>
                    @if($isRegistered)
                        <button class="btn btn-sm btn-outline-danger">
                            Drop
                        </button>
                    @else
                        <button class="btn btn-sm btn-primary">
                            Add
                        </button>
                    @endif
                </td>

            </tr>

        @empty
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    No courses available
                </td>
            </tr>
        @endforelse

        </tbody>

    </table>

</div>

{{-- REGISTER BUTTON (like your screenshot) --}}
<div class="text-center mt-4">
    <a href="{{ route('student.courses.submit') }}" class="btn btn-lg btn-success">
        Submit Registration
    </a>
</div>

</div>

@endsection
