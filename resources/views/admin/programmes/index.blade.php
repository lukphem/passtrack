@extends('admin.dashboard.layouts.admin')

@section('content')

{{-- HEADER --}}
<div class="row align-items-sm-center mb-4">

    {{-- Title Section --}}
    <div class="col-12 col-sm">
        <h3 class="fw-bold mb-1">Programme Management</h3>
        <p class="text-muted mb-2 mb-sm-0 small">Manage all programmes across faculties</p>
    </div>

    {{-- Button Section --}}
    <div class="col-12 col-sm-auto">
        <button class="btn btn-primary w-100 w-sm-auto"
                data-bs-toggle="modal"
                data-bs-target="#addProgrammeModal">
            <i class="bi bi-plus-circle"></i> Add Programme
        </button>
    </div>

</div>

{{-- SUCCESS / ERROR MESSAGE --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- SEARCH BAR --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.programmes.index') }}">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control border-start-0"
                       placeholder="Search by programme name, code, level, or department...">
                <button class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>
</div>

{{-- PROGRAMME GRID --}}
<div class="row g-4">
@forelse($programmes as $programme)
    <div class="col-12 col-md-6 col-lg-4">

        <div class="card border-0 shadow-lg rounded-4 h-100 programme-card position-relative">


            <div class="card-body p-4 pt-5 d-flex flex-column">
{{-- STATUS BADGE --}}
<span class="position-absolute top-0 end-0 m-3 badge
    {{ $programme->programme_status ? 'bg-success-subtle text-success' : 'bg-light text-muted border' }}
    rounded-pill px-3 py-2">
    {{ $programme->programme_status ? 'Active' : 'Inactive' }}
</span>
                {{-- TITLE --}}
                <div class="mb-3">
                    <h5 class="fw-bold mb-1 text-dark">{{ $programme->programme_name }}</h5>
                    <span class="badge bg-primary-subtle text-primary fw-semibold">
                        {{ $programme->programme_code }}
                    </span>
                </div>

                {{-- DESCRIPTION --}}
                <p class="text-muted small flex-grow-1">
                    {{ \Illuminate\Support\Str::limit($programme->programme_description, 120) ?? 'No description provided' }}
                </p>

                {{-- INFO GRID --}}
                <div class="row small gy-2 mb-3">
                    <div class="col-4">
                        <div class="text-muted">Department</div>
                        <div class="fw-semibold text-truncate">{{ $programme->department->dept_name ?? 'N/A' }}</div>
                    </div>

                    <div class="col-4">
                        <div class="text-muted">Level</div>
                        <div class="fw-semibold">{{ $programme->programme_level_type ?? 'N/A' }}</div>
                    </div>

                    <div class="col-4">
                        <div class="text-muted">Duration</div>
                        <div class="fw-semibold">{{ $programme->programme_duration }} yr(s)</div>
                    </div>

                    <div class="col-4">
                        <div class="text-muted">Start Date</div>
                        <div class="fw-semibold">{{ $programme->programme_start_date?->format('d M, Y') ?? 'N/A' }}</div>
                    </div>

                    <div class="col-4">
                        <div class="text-muted">Industrial Training</div>
                        <div class="fw-semibold">
                            {{ $programme->industrial_training_required ? 'Yes' : 'No' }}
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="text-muted">IT Level</div>
                        <div class="fw-semibold">
                            {{ $programme->industrial_training_level ?? 'N/A' }}
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                {{-- STATS --}}
                <div class="d-flex justify-content-between mb-3">
                    <div class="text-center flex-fill">
                        <div class="fw-bold fs-5 text-primary">{{ $programme->students_count ?? 0 }}</div>
                        <small class="text-muted">Students</small>
                    </div>
                    <div class="border-start"></div>
                    <div class="text-center flex-fill">
                        <div class="fw-bold fs-5 text-success">{{ $programme->courses_count ?? 0 }}</div>
                        <small class="text-muted">Courses</small>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="d-flex gap-2 mt-auto">

                    {{-- EDIT --}}
                    <button class="btn btn-primary flex-fill"
                            data-bs-toggle="modal"
                            data-bs-target="#editProgramme{{ $programme->id }}">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </button>

                    {{-- DELETE --}}
                    <button class="btn btn-danger flex-fill"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteProgramme{{ $programme->id }}">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>

                </div>

            </div>
        </div>

    </div>
@empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-journal fs-1 d-block mb-3"></i>
        No programmes found
    </div>
@endforelse
</div>

{{-- PAGINATION --}}
<div class="mt-4">
    {{ $programmes->withQueryString()->links() }}
</div>

{{-- INCLUDE MODALS --}}
@include('admin.programmes.partials.add-modal')

@foreach($programmes as $programme)
    @include('admin.programmes.partials.edit-modal', ['programme' => $programme])
    @include('admin.programmes.partials.delete-modal', ['programme' => $programme])
@endforeach

@endsection

{{-- AUTO OPEN MODAL ON ERROR --}}
@section('scripts')
<style>
.programme-card {
    transition: all 0.25s ease;
    overflow: hidden;
}
.programme-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 1.5rem 3rem rgba(0, 0, 0, 0.08) !important;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    @if(session('add_programme_error'))
        var addModal = new bootstrap.Modal(document.getElementById('addProgrammeModal'));
        addModal.show();
    @elseif(session('edit_programme_error') && session('programme_id'))
        var editModal = new bootstrap.Modal(document.getElementById('editProgramme' + {{ session('programme_id') }}));
        editModal.show();
    @endif
});
</script>
@endsection
