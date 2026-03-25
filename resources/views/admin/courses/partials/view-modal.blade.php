{{-- VIEW COURSE MODAL --}}
<div class="modal fade"
     id="viewCourse{{ $course->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            {{-- HEADER --}}
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-eye"></i> Course Details
                </h5>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body">

                <div class="row g-3">

                    {{-- BASIC INFO --}}
                    <div class="col-12">
                        <h6 class="fw-bold text-info">Basic Information</h6>
                        <hr class="mt-1 mb-2">
                    </div>

                    <div class="col-md-4">
                        <strong>Course Code:</strong>
                        <div class="text-uppercase">{{ $course->course_code }}</div>
                    </div>

                    <div class="col-md-4">
                        <strong>Course Title:</strong>
                        <div>{{ $course->course_title }}</div>
                    </div>

                    <div class="col-md-4">
                        <strong>Level:</strong>
                        <div>{{ $course->level }}</div>
                    </div>

                    <div class="col-md-4">
                        <strong>Semester:</strong>
                        <div>{{ $course->semester }}</div>
                    </div>

                    <div class="col-md-4">
                        <strong>Credit Unit:</strong>
                        <div>{{ $course->credit_unit }}</div>
                    </div>

                    <div class="col-md-4">
                        <strong>Course Type:</strong>
                        <div>{{ $course->course_type }}</div>
                    </div>

                    <div class="col-md-4">
                        <strong>Status:</strong>
                            @if($course->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                    </div>

                    {{-- RELATIONSHIPS --}}
                    <div class="col-12 mt-3">
                        <h6 class="fw-bold text-info">Assignments</h6>
                        <hr class="mt-1 mb-2">
                    </div>

                    <div class="col-md-6">
                        <strong>Department:</strong>
                        <div>
                            {{ $course->department->dept_name ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <strong>Lecturer:</strong>
                        <div>
                            {{ $course->lecturer ? $course->lecturer->title . ' ' . $course->lecturer->first_name . ' ' . $course->lecturer->last_name : '—' }}
                        </div>
                    </div>

                    <div class="col-12">
                        <strong>Programmes:</strong>
                        <div class="mt-1">
                            @forelse($course->programmes as $programme)
                                <span class="badge bg-primary me-1">
                                    {{ $programme->programme_name }}
                                </span>
                            @empty
                                <span class="text-muted">No programmes assigned</span>
                            @endforelse
                        </div>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="col-12 mt-3">
                        <h6 class="fw-bold text-info">Description</h6>
                        <hr class="mt-1 mb-2">
                        <div class="text-muted">
                            {{ $course->course_description ?? 'No description provided' }}
                        </div>
                    </div>

                    {{-- TIMESTAMP --}}
                    <div class="col-12 mt-3">
                        <h6 class="fw-bold text-info">Record Info</h6>
                        <hr class="mt-1 mb-2">
                    </div>

                    <div class="col-md-6">
                        <strong>Created At:</strong>
                        <div>{{ $course->created_at?->format('d M Y, h:i A') }}</div>
                    </div>

                    <div class="col-md-6">
                        <strong>Last Updated:</strong>
                        <div>{{ $course->updated_at?->format('d M Y, h:i A') }}</div>
                    </div>

                </div>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer bg-light">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>
