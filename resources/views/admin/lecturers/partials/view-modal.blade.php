{{-- View Lecturer Modal --}}
<div class="modal fade" id="viewLecturerModal{{ $lecturer->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            {{-- Header --}}
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="bi bi-person-lines-fill"></i> Lecturer Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <div class="row g-3">

                    {{-- PROFILE --}}
                    <div class="col-12 d-flex align-items-center gap-3 mb-4">
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                             style="width:80px; height:80px; font-size:26px; font-weight:600;">
                            {{ strtoupper(substr($lecturer->first_name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">
                                {{ $lecturer->title }} {{ $lecturer->first_name }} {{ $lecturer->last_name }}
                            </h4>
                            <small class="text-muted">{{ $lecturer->staff_id ?? '—' }}</small>
                        </div>
                    </div>

                    {{-- CONTACT INFO --}}
                    <div class="col-12">
                        <h6 class="fw-bold text-muted"><i class="bi bi-envelope me-1"></i> Contact Info</h6>
                        <div class="mb-2">
                            <strong>Email:</strong> {{ $lecturer->user->email ?? '—' }}
                        </div>
                        <div>
                            <strong>Phone:</strong> {{ $lecturer->phone ?? '—' }}
                        </div>
                        <hr>
                    </div>

                    {{-- ACADEMIC INFO --}}
                    <div class="col-12">
                        <h6 class="fw-bold text-muted"><i class="bi bi-mortarboard me-1"></i> Academic Info</h6>
                        <div class="mb-1"><strong>Faculty:</strong> {{ $lecturer->faculty->faculty_name ?? '—' }}</div>
                        <div class="mb-1"><strong>Department:</strong> {{ $lecturer->department->dept_name ?? '—' }}</div>
                        <div class="mb-1"><strong>Specialization:</strong> {{ $lecturer->specialization ?? '—' }}</div>
                        <div class="mb-1"><strong>Rank:</strong> {{ $lecturer->rank ?? '—' }}</div>
                        <hr>
                    </div>

                    {{-- EMPLOYMENT INFO --}}
                    <div class="col-12">
                        <h6 class="fw-bold text-muted"><i class="bi bi-briefcase me-1"></i> Employment Info</h6>
                        <div class="mb-1"><strong>Employment Type:</strong> {{ ucfirst(str_replace('_',' ',$lecturer->employment_type)) ?? '—' }}</div>
                        <div class="mb-1"><strong>Status:</strong>
                            <span class="badge {{ $lecturer->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($lecturer->status) }}
                            </span>
                        </div>
                    </div>

                    {{-- ASSIGNED COURSES
                    <div class="col-12 mt-3">
                        <h6 class="fw-bold text-muted"><i class="bi bi-book me-1"></i> Assigned Courses</h6>
                        @if($lecturer->courses->count())
                            @foreach($lecturer->courses as $course)
                                <span class="badge bg-primary-subtle text-primary me-1 mb-1">{{ $course->code }}</span>
                            @endforeach
                        @else
                            <span class="badge bg-light text-muted border">No courses assigned</span>
                        @endif
                    </div> --}}

                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Close
                </button>
            </div>

        </div>
    </div>
</div>
