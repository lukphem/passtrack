@extends('admin.dashboard.layouts.admin')

@section('content')

{{-- HEADER --}}

<div class="row align-items-sm-center mb-4">

    {{-- Title Section --}}
    <div class="col-12 col-sm">
        <h3 class="fw-bold mb-1">Lecturer Management</h3>
        <p class="text-muted mb-2 mb-sm-0 small">
            Manage all lecturer records and assignments
        </p>
    </div>

    {{-- Button Section --}}
    <div class="col-12 col-sm-auto">
        <button class="btn btn-primary w-100 w-sm-auto"
                data-bs-toggle="modal"
                data-bs-target="#addLecturerModal">
            <i class="bi bi-plus-circle"></i> Add Lecturer
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
        <form method="GET" action="{{ route('admin.lecturers.index') }}">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control border-start-0"
                       placeholder="Search by name, staff ID, phone or email...">

                <button class="btn btn-primary">
                    Search
                </button>
            </div>
        </form>
    </div>
</div>


{{-- LECTURER GRID --}}
<div class="row g-4">

@forelse($lecturers as $lecturer)
    <div class="col-12 col-md-6 col-lg-4">


        <div class="card shadow-sm border-0 rounded-4 h-100 position-relative">

            {{-- STATUS BADGE --}}
            <span class="position-absolute top-0 end-0 m-3 badge
                {{ $lecturer->status === 'active' ? 'bg-success-subtle text-success' : 'bg-light text-muted border' }}
                rounded-pill px-3 py-2">
                {{ ucfirst($lecturer->status) }}
            </span>

            <div class="card-body p-4">

                {{-- PROFILE SECTION --}}
                <div class="d-flex align-items-center gap-3 mb-3">

                    <div class="rounded-circle bg-primary-subtle text-primary
                                d-flex align-items-center justify-content-center"
                         style="width:70px; height:70px; font-size:22px; font-weight:600;">
                        {{ strtoupper(substr($lecturer->first_name, 0, 1)) }}
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            {{ $lecturer->title }}
                            {{ $lecturer->first_name }}
                            {{ $lecturer->last_name }}
                        </h5>

                        <small class="text-muted">
                            {{ $lecturer->staff_id ?? '—' }}
                        </small>
                    </div>

                </div>

                {{-- CONTACT INFO --}}
                <div class="small text-muted mb-3">
                    <div class="mb-1">
                        <i class="bi bi-envelope me-2"></i>
                        {{ $lecturer->user->email ?? '—' }}
                    </div>
                    <div>
                        <i class="bi bi-telephone me-2"></i>
                        {{ $lecturer->phone ?? '—' }}
                    </div>
                </div>

                <hr>

                {{-- ACADEMIC INFO --}}
                <div class="small mb-3">
                    <div>
                        <strong class="text-muted">Faculty:</strong>
                        {{ $lecturer->faculty->faculty_name ?? '—' }}
                    </div>

                    <div>
                        <strong class="text-muted">Department:</strong>
                        {{ $lecturer->department->dept_name ?? '—' }}
                    </div>

                    <div>
                        <strong class="text-muted">Specialization:</strong>
                        {{ $lecturer->specialization ?? '—' }}
                    </div>
                </div>

                {{-- ASSIGNED COURSES --}}
                <div class="mb-4">
                    <strong class="small d-block mb-2">
                        <i class="bi bi-book me-1"></i> Assigned Courses
                    </strong>

                    {{-- @if($lecturer->courses->count())
                        @foreach($lecturer->courses as $course)
                            <span class="badge bg-primary-subtle text-primary me-1 mb-1">
                                {{ $course->code }}
                            </span>
                        @endforeach
                    @else
                        <span class="badge bg-light text-muted border">
                            N/A
                        </span>
                    @endif --}}
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="d-flex flex-wrap gap-2">

                    {{-- VIEW --}}
                    <button class="btn btn-info border flex-fill"
                            data-bs-toggle="modal"
                            data-bs-target="#viewLecturerModal{{ $lecturer->id }}">
                        <i class="bi bi-eye"></i> View
                    </button>

                    {{-- EDIT --}}
                    <button class="btn btn-primary flex-fill"
                            data-bs-toggle="modal"
                            data-bs-target="#editLecturerModal{{ $lecturer->id }}">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </button>

                    {{-- DELETE --}}
                    <button class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteLecturerModal{{ $lecturer->id }}">
                        <i class="bi bi-trash"></i>
                    </button>

                </div>

            </div>
        </div>

    </div>

@empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-people fs-1 d-block mb-3"></i>
        No lecturers found
    </div>
@endforelse

<div class="mt-4">
    {{ $lecturers->links() }}
</div>

</div>

@endsection


{{-- ADD MODAL --}}
@include('admin.lecturers.partials.add-modal')


{{-- VIEW MODALS --}}
@foreach ($lecturers as $lecturer)
    @include('admin.lecturers.partials.view-modal', ['lecturer' => $lecturer])
@endforeach

{{-- EDIT MODALS --}}
@foreach ($lecturers as $lecturer)
    @include('admin.lecturers.partials.edit-modal', ['lecturer' => $lecturer])
@endforeach



{{-- DELETE MODALS --}}
@foreach ($lecturers as $lecturer)
    @include('admin.lecturers.partials.delete-modal', ['lecturer' => $lecturer])
@endforeach
