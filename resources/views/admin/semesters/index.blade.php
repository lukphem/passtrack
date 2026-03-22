@extends('admin.dashboard.layouts.admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="row align-items-sm-center mb-4">

    <div class="col-12 col-sm">
        <h3 class="fw-bold mb-1">Semester Management</h3>
        <p class="text-muted mb-2 mb-sm-0 small">
            Manage academic periods and registration windows
        </p>
    </div>

    <div class="col-12 col-sm-auto">
        <button class="btn btn-primary w-100 w-sm-auto"
                data-bs-toggle="modal"
                data-bs-target="#addSemesterModal">
            <i class="bi bi-plus-circle"></i> Add Semester
        </button>
    </div>

</div>

{{-- ================= ALERTS ================= --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ================= SEARCH ================= --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.academic-semester.index') }}">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control border-start-0"
                       placeholder="Search by semester or session name...">
                <button class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>
</div>

{{-- ================= TABLE ================= --}}
<div class="card shadow-sm border-0">
    <div class="card-body table-responsive">

        <table class="table table-hover align-middle">

            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Session</th>
                    <th>Session Duration</th>
                    <th>Semester</th>
                    <th>Semester Duration</th>
                    <th>Registration Window</th>
                    <th>Status</th>
                    <th>Registration</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>

            @forelse($semesters as $semester)

                @php
                    $now = now();

                    $regOpen = $semester->registration_allowed
                        && $semester->registration_start_date
                        && $semester->registration_end_date
                        && $now->between($semester->registration_start_date, $semester->registration_end_date);
                @endphp

                <tr>

                    {{-- INDEX --}}
                    <td>{{ $loop->iteration }}</td>

                    {{-- SESSION --}}
                    <td>
                        {{ $semester->academicSession->session_name ?? '—' }}
                    </td>

                    {{-- SESSION DURATION --}}
                    <td class="small text-muted">
                        {{ optional($semester->academicSession->start_date)->format('d M, Y') ?? '—' }} <br>
                        <span class="text-muted">to</span><br>
                        {{ optional($semester->academicSession->end_date)->format('d M, Y') ?? '—' }}
                    </td>

                    {{-- SEMESTER --}}
                    <td>
                        <span class="badge bg-primary">
                            {{ $semester->semester_name }}
                        </span>
                    </td>

                    {{-- DURATION --}}
                    <td class="small text-muted">
                        {{ optional($semester->start_date)->format('d M, Y') ?? '—' }} <br>
                        <span class="text-muted">to</span><br>
                        {{ optional($semester->end_date)->format('d M, Y') ?? '—' }}
                    </td>

                    {{-- REGISTRATION WINDOW --}}
                    <td class="small">
                        {{ optional($semester->registration_start_date)->format('d M, Y') ?? '—' }} <br>
                        <span class="text-muted">to</span><br>
                        {{ optional($semester->registration_end_date)->format('d M, Y') ?? '—' }}
                    </td>

                    {{-- ACTIVE STATUS --}}
                    <td>
                        @if($semester->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>

                    {{-- REGISTRATION STATUS --}}
                    <td>
                        @if($regOpen)
                            <span class="badge bg-success">Open</span>
                        @else
                            <span class="badge bg-danger">Closed</span>
                        @endif
                    </td>

                    {{-- ACTIONS --}}
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">

                            <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editSemester{{ $semester->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <button class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteSemester{{ $semester->id }}">
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x fs-4 d-block mb-2"></i>
                        No semesters found
                    </td>
                </tr>
            @endforelse

            </tbody>

        </table>

    </div>
</div>

{{-- ================= PAGINATION ================= --}}
<div class="mt-4 d-flex justify-content-center">
    {{ $semesters->links('pagination::bootstrap-5') }}
</div>

@endsection

{{-- ================= MODALS ================= --}}
@include('admin.semesters.partials.add-modal')

@foreach ($semesters as $semester)
    @include('admin.semesters.partials.edit-modal', ['semester' => $semester])
    @include('admin.semesters.partials.delete-modal', ['semester' => $semester])
@endforeach
