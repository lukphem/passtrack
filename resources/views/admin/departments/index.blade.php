@extends('admin.dashboard.layouts.admin')

@section('content')

<div class="row align-items-sm-center mb-4">

    {{-- Title Section --}}
    <div class="col-12 col-sm">
        <h3 class="fw-bold mb-1">Department Management</h3>
        <p class="text-muted mb-2 mb-sm-0 small">
            Manage all departments across faculties
        </p>
    </div>

    {{-- Button Section --}}
    <div class="col-12 col-sm-auto">
        <button class="btn btn-primary w-100 w-sm-auto"
                data-bs-toggle="modal"
                data-bs-target="#addDepartmentModal">
            <i class="bi bi-plus"></i> Add Department
        </button>
    </div>

</div>


<div class="row g-4">
@foreach($departments as $department)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column justify-content-between">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-semibold mb-0">
                            {{ $department->dept_name }}
                        </h5>
                        <small class="text-muted text-uppercase">
                            {{ $department->dept_code }}
                        </small>
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

                {{-- Description --}}
                <p class="text-muted small mb-3">
                    {{ $department->description }}
                </p>

                {{-- Meta --}}
                <div class="small mb-3">
                    <div class="mb-1">
                        <strong>Faculty:</strong>
                        <span class="text-muted">
                            {{ $department->faculty->faculty_name ?? 'N/A' }}
                        </span>
                    </div>

                    <div>
                        <strong>Head of Department:</strong>
                        <span class="text-muted">
                            {{ $department->head_of_department ?? 'N/A' }}
                        </span>
                    </div>
                </div>

                <hr>

                {{-- Stats --}}
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
@endforeach
</div>

@endsection


{{-- Include the Add Department Modal Partial --}}
@include('admin.departments.partials.add-modal')


{{-- Include Edit Department Modals --}}
@foreach ($departments as $department)
    @include('admin.departments.partials.edit-modal', [
        'department' => $department,
        'faculties' => $faculties
    ])
@endforeach


{{-- Include Delete Department Modals --}}
@foreach ($departments as $department)
    @include('admin.departments.partials.delete-modal', [
        'department' => $department,
        'faculties' => $faculties
        ])
@endforeach
