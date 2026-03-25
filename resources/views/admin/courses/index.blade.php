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
    <div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Title</th>
                    <th>Department</th>
                    <th>Level</th>
                    <th>Semester</th>
                    <th>Programmes</th>
                    <th>Status</th>
                    <th width="150">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($courses as $course)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $course->course_code }}</td>
                        <td>{{ $course->course_title }}</td>
                        <td>{{ $course->department->dept_name ?? 'N/A' }}</td>
                        <td>{{ $course->level }}</td>
                        <td>{{ $course->semester }}</td>

                        {{-- PROGRAMMES --}}
                        <td>
                            @forelse($course->programmes as $programme)
                                <span class="badge bg-primary">
                                    {{ $programme->programme_name }}
                                </span><br>
                            @empty
                                <span class="text-muted small">No Programme</span>
                            @endforelse
                        </td>

                        {{-- STATUS --}}
                        <td>
                            <span class="badge bg-{{ $course->status ? 'success' : 'danger' }}">
                                {{ $course->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        {{-- ACTIONS --}}
                        <td>
                            <div class="d-flex gap-1">

                                <button class="btn btn-sm btn-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewCourse{{ $course->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCourse{{ $course->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteCourse{{ $course->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            No courses found
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
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
    @include('admin.courses.partials.view-modal', ['course' => $course])
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
