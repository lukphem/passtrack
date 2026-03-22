@extends('admin.dashboard.layouts.admin')

@section('content')

{{-- HEADER --}}
<div class="row align-items-sm-center mb-4">

    <div class="col-12 col-sm">
        <h3 class="fw-bold mb-1">Course Management</h3>
        <p class="text-muted mb-2 mb-sm-0 small">
            Manage all courses across programmes
        </p>
    </div>

    <div class="col-12 col-sm-auto">
        <button class="btn btn-primary w-100 w-sm-auto"
                data-bs-toggle="modal"
                data-bs-target="#addCourseModal">
            <i class="bi bi-plus-circle"></i> Add Course
        </button>
    </div>

</div>

{{-- SUCCESS / ERROR --}}
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

{{-- SEARCH --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.courses.index') }}">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control border-start-0"
                       placeholder="Search by course code, title, level, semester or programme...">
                <button class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>
</div>

{{-- COURSE GRID --}}
<div class="row g-4">
@forelse($courses as $course)

    <div class="col-12 col-md-6 col-lg-4">

        <div class="card border-0 shadow-lg rounded-4 h-100 course-card position-relative">

            {{-- STATUS BADGE --}}
            <span class="position-absolute top-0 end-0 m-3 badge
                {{ $course->status ? 'bg-success-subtle text-success' : 'bg-light text-muted border' }}
                rounded-pill px-3 py-2">
                {{ $course->status ? 'Active' : 'Inactive' }}
            </span>

            <div class="card-body p-4 pt-5 d-flex flex-column">

                {{-- TITLE --}}
                <div class="mb-3">
                    <h5 class="fw-bold mb-1 text-dark">
                        {{ $course->course_title }}
                    </h5>

                    <span class="badge bg-primary-subtle text-primary fw-semibold">
                        {{ $course->course_code }}
                    </span>
                </div>

                {{-- DESCRIPTION --}}
                <p class="text-muted small flex-grow-1">
                    {{ \Illuminate\Support\Str::limit($course->course_description, 120) ?? 'No description provided' }}
                </p>

                {{-- COURSE DETAILS --}}
                <div class="row small gy-2 mb-3">

                    <div class="col-4">
                        <div class="text-muted">Level</div>
                        <div class="fw-semibold">{{ $course->level }}</div>
                    </div>

                    <div class="col-4">
                        <div class="text-muted">Semester</div>
                        <div class="fw-semibold">{{ $course->semester }}</div>
                    </div>

                    <div class="col-4">
                        <div class="text-muted">Credit Unit</div>
                        <div class="fw-semibold">{{ $course->credit_unit }}</div>
                    </div>

                    <div class="col-6">
                        <div class="text-muted">Course Type</div>
                        <div class="fw-semibold">{{ $course->course_type }}</div>
                    </div>

                    <div class="col-6">
                        <div class="text-muted">Programmes</div>
                        <div class="fw-semibold text-truncate">
                            @forelse($course->programmes as $programme)
                                <span class="badge bg-secondary-subtle text-secondary mb-1">
                                    {{ $programme->programme_code }}
                                </span>
                            @empty
                                N/A
                            @endforelse
                        </div>
                    </div>

                </div>

                <hr class="my-3">

                {{-- ACTIONS --}}
                <div class="d-flex gap-2 mt-auto">

                    <button class="btn btn-primary flex-fill"
                            data-bs-toggle="modal"
                            data-bs-target="#editCourse{{ $course->id }}">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </button>

                    <button class="btn btn-danger flex-fill"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteCourse{{ $course->id }}">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>

                </div>

            </div>
        </div>

    </div>

@empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-journal fs-1 d-block mb-3"></i>
        No courses found
    </div>
@endforelse
</div>

{{-- PAGINATION --}}
<div class="mt-4">
    {{ $courses->withQueryString()->links() }}
</div>

{{-- MODALS --}}
@include('admin.courses.partials.add-modal')

@foreach($courses as $course)
    @include('admin.courses.partials.edit-modal', ['course' => $course])
    @include('admin.courses.partials.delete-modal', ['course' => $course])
@endforeach

@endsection

@section('scripts')

<style>
.course-card {
    transition: all 0.25s ease;
    overflow: hidden;
}
.course-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 1.5rem 3rem rgba(0,0,0,0.08) !important;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {

    @if(session('add_course_error'))
        var addModal = new bootstrap.Modal(document.getElementById('addCourseModal'));
        addModal.show();
    @elseif(session('edit_course_error') && session('course_id'))
        var editModal = new bootstrap.Modal(
            document.getElementById('editCourse' + {{ session('course_id') }})
        );
        editModal.show();
    @endif

});
</script>

@endsection
