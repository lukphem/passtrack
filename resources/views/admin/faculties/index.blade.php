@extends('admin.dashboard.layouts.admin')

@section('content')

<div class="row align-items-sm-center mb-4">

    {{-- Title Section --}}
    <div class="col-12 col-sm">
        <h3 class="fw-bold mb-1">Faculty Management</h3>
        <p class="text-muted mb-2 mb-sm-0 small">
            Manage all faculties and their leadership
        </p>
    </div>

    {{-- Button Section --}}
    <div class="col-12 col-sm-auto">
        <button class="btn btn-primary w-100 w-sm-auto"
                data-bs-toggle="modal"
                data-bs-target="#addFacultyModal">
            <i class="bi bi-plus-circle"></i> Add Faculty
        </button>
    </div>

</div>


@foreach($faculties as $faculty)
<div class="card mb-3 shadow-sm">
    <div class="card-body">

        <div class="row align-items-start">

            {{-- LEFT CONTENT --}}
            <div class="col-12 col-lg">

                <div class="d-flex gap-3">

                    <div class="bg-primary text-white rounded p-3 flex-shrink-0">
                        <i class="bi bi-building fs-3"></i>
                    </div>


                    <div class="w-100">
                        <h5 class="mb-1">
                            {{ $faculty->faculty_name }}
                            <span class="badge bg-light text-primary">
                                {{ $faculty->faculty_code }}
                            </span>
                        </h5>

                        <p class="text-muted mb-2 small">
                            {{ $faculty->description }}
                        </p>

                        {{-- Stats Section --}}
                        <div class="row row-cols-2 row-cols-md-4 g-2 small">

                            <div class="col">
                                <span class="text-muted d-block">Dean</span>
                                <strong>{{ $faculty->dean }}</strong>
                            </div>

                            <div class="col">
                                <span class="text-muted d-block">Established</span>
                                <strong>{{ $faculty->established_year }}</strong>
                            </div>

                            <div class="col">
                                <i class="bi bi-diagram-3 text-primary"></i>
                                <strong>{{ $faculty->departments_count }}</strong>
                                <span class="text-muted">Departments</span>
                            </div>

                            <div class="col">
                                <i class="bi bi-people text-success"></i>
                                <strong>{{ $faculty->students_count }}</strong>
                                <span class="text-muted">Students</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">

                <div class="d-flex gap-2 justify-content-start justify-content-lg-end">

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
