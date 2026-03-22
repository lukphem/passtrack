@extends('admin.dashboard.layouts.admin')

@section('content')

{{-- HEADER --}}
<div class="row align-items-sm-center mb-4">

    <div class="col-12 col-sm">
        <h3 class="fw-bold mb-1">Department Management</h3>
        <p class="text-muted mb-2 mb-sm-0 small">
            Manage all departments across faculties
        </p>
    </div>

    <div class="col-12 col-sm-auto">
        <button class="btn btn-primary w-100 w-sm-auto"
                data-bs-toggle="modal"
                data-bs-target="#addDepartmentModal">
            <i class="bi bi-plus-circle"></i> Add Department
        </button>
    </div>

</div>

{{-- SUCCESS MESSAGE --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
        </button>
    </div>
@endif

{{-- SEARCH --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.departments.index') }}">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control border-start-0"
                       placeholder="Search by name, code or head...">

                <button class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>
</div>

{{-- DEPARTMENTS --}}
<div class="row g-4">
@forelse($departments as $department)
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column justify-content-between">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-semibold mb-0">
                            {{ $department->dept_name }}
                            <span class="badge bg-light text-primary ms-1">{{ $department->dept_code }}</span>

                            @if($department->status === 'active')
                                <i class="bi bi-check-circle-fill text-success ms-1" title="Active"></i>
                            @else
                                <i class="bi bi-x-circle-fill text-danger ms-1" title="Inactive"></i>
                            @endif
                        </h5>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#editDepartment{{ $department->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteDepartment{{ $department->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>

                <p class="text-muted small mb-3">{{ $department->description ?? '—' }}</p>

                <div class="small mb-3">
                    <div class="mb-1">
                        <strong>Faculty:</strong>
                        <span class="text-muted">{{ $department->faculty->faculty_name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <strong>Head of Department:</strong>
                        <span class="text-muted">{{ $department->head_of_department ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <strong>Established Year:</strong>
                        <span class="text-muted">{{ $department->established_year ?? 'N/A' }}</span>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary bg-opacity-10 text-primary rounded p-2">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $department->students_count }}</div>
                            <small class="text-muted">Students</small>
                        </div>
                    </div>


                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-success bg-opacity-10 text-success rounded p-2">
                            <i class="bi bi-book"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $department->courses_count }}</div>
                            <small class="text-muted">Courses</small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-building fs-1 d-block mb-3"></i>
        No departments found
    </div>
@endforelse
</div>

{{-- Pagination --}}
<div class="mt-4 d-flex justify-content-center">
    {{ $departments->links('pagination::bootstrap-5') }}
</div>

@endsection

{{-- ADD MODAL --}}
@include('admin.departments.partials.add-modal')

{{-- EDIT MODALS --}}
@foreach ($departments as $department)
    @include('admin.departments.partials.edit-modal', [
        'department' => $department,
        'faculties' => $faculties
    ])
@endforeach

{{-- DELETE MODALS --}}
@foreach ($departments as $department)
    @include('admin.departments.partials.delete-modal', [
        'department' => $department,
        'faculties' => $faculties
    ])
@endforeach
