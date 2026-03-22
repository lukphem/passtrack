@extends('admin.dashboard.layouts.admin')

@section('content')

{{-- HEADER --}}
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


{{-- SEARCH BAR --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.faculties.index') }}">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control border-start-0"
                       placeholder="Search by faculty name, code or dean...">

                <button class="btn btn-primary">
                    Search
                </button>
            </div>
        </form>
    </div>
</div>


{{-- FACULTY LIST --}}
<div class="row g-4">

@forelse($faculties as $faculty)

    <div class="col-12 col-md-6">
        <div class="card shadow-sm h-100">
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

                                    {{-- Status icon --}}
                                    @if($faculty->status === 'active')
                                        <i class="bi bi-check-circle-fill text-success ms-1" title="Active"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger ms-1" title="Inactive"></i>
                                    @endif
                                </h5>



                                <p class="text-muted mb-2 small">
                                    {{ $faculty->description ?? '—' }}
                                </p>

                                {{-- Stats Section --}}
                                <div class="row row-cols-2 row-cols-md-4 g-2 small">

                                    <div class="col">
                                        <span class="text-muted d-block">Dean</span>
                                        <strong>{{ $faculty->dean ?? '—' }}</strong>
                                    </div>

                                    <div class="col">
                                        <span class="text-muted d-block">Established</span>
                                        <strong>{{ $faculty->established_year ?? '—' }}</strong>
                                    </div>

                                    <div class="col">
                                        <span class="text-muted">Departments</span><br>
                                        <i class="bi bi-diagram-3 text-primary"></i>
                                        <strong>{{ $faculty->departments_count }}</strong>
                                    </div>

                                    <div class="col">
                                        <span class="text-muted">Students</span><br>
                                        <i class="bi bi-people text-success"></i>
                                        <strong>{{ $faculty->students_count }}</strong>
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
    </div>

@empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-building fs-1 d-block mb-3"></i>
        No faculties found
    </div>
@endforelse

</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $faculties->links('pagination::bootstrap-5') }}
</div>

@endsection


{{-- ADD MODAL --}}
@include('admin.faculties.partials.add-modal')

{{-- EDIT MODALS --}}
@foreach ($faculties as $faculty)
    @include('admin.faculties.partials.edit-modal', ['faculty' => $faculty])
@endforeach

{{-- DELETE MODALS --}}
@foreach ($faculties as $faculty)
    @include('admin.faculties.partials.delete-modal', ['faculty' => $faculty])
@endforeach
