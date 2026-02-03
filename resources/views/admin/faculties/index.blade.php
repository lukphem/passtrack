@extends('admin.dashboard.layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Faculty Management</h3>
        <p class="text-muted">Manage all faculties and their leadership</p>
    </div>

    <button class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addFacultyModal">
        <i class="bi bi-plus-circle"></i> Add Faculty
    </button>
</div>

@foreach($faculties as $faculty)
<div class="card mb-3 shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-start">

        <div class="d-flex gap-3">
            <div class="bg-primary text-white rounded p-3">
                <i class="bi bi-building fs-4"></i>
            </div>

            <div>
                <h5 class="mb-1">
                    {{ $faculty->name }}
                    <span class="badge bg-light text-primary">
                        {{ $faculty->code }}
                    </span>
                </h5>

                <p class="text-muted mb-2">
                    {{ $faculty->description }}
                </p>

                <div class="d-flex gap-4 small">
                    <div>
                        <span class="text-muted">Dean</span><br>
                        <strong>{{ $faculty->dean }}</strong>
                    </div>

                    <div>
                        <span class="text-muted">Established</span><br>
                        <strong>{{ $faculty->established_year }}</strong>
                    </div>

                    <div>
                        <i class="bi bi-diagram-3 text-primary"></i>
                        <strong>{{ $faculty->departments_count }}</strong>
                        <span class="text-muted">Departments</span>
                    </div>

                    <div>
                        <i class="bi bi-people text-success"></i>
                        <strong>{{ $faculty->students_count }}</strong>
                        <span class="text-muted">Students</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex gap-2">
            <!-- EDIT triggers modal -->
            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#editFaculty{{ $faculty->id }}">
                <i class="bi bi-pencil"></i>
            </button>

            <button class="btn btn-sm btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteFaculty{{ $faculty->id }}">
                <i class="bi bi-trash"></i>
            </button>
        </div>

    </div>
</div>
@endforeach

@endsection

{{-- Include the Add Faculty Modal Partial --}}
@include('admin.faculties.partials.add-modal')


{{-- Include Edit Faculty Modals --}}
@foreach ($faculties as $faculty)
    @include('admin.faculties.partials.edit-modal', ['faculty' => $faculty])
@endforeach

{{-- Include Delete Faculty Modals --}}
@foreach ($faculties as $faculty)
    @include('admin.faculties.partials.delete-modal', ['faculty' => $faculty])
@endforeach
