<div class="modal fade" id="courseModal{{ $course->id }}" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 shadow-sm">

            {{-- HEADER --}}
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">
                    {{ $course->course_code }} — {{ $course->course_title }}
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-4">

                {{-- TOP SUMMARY (COMBINED) --}}
                <div class="mb-4 p-3 bg-light rounded">

                    <div class="row g-4">

                        <div class="col-md-3">
                            <small class="text-muted d-block">Session</small>
                            <span class="fw-bold">{{ $session->session_name ?? 'N/A' }}</span>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted d-block">Semester</small>
                            <span class="fw-bold">{{ $semester->semester_name ?? 'N/A' }}</span>
                        </div>

                        <div class="col-md-2">
                            <small class="text-muted d-block">Level</small>
                            <span class="fw-bold">{{ $course->level }}</span>
                        </div>

                        <div class="col-md-2">
                            <small class="text-muted d-block">Unit</small>
                            <span class="fw-bold">{{ $course->credit_unit }}</span>
                        </div>

                        <div class="col-md-2">
                            <small class="text-muted d-block">Type</small>
                            <span class="fw-bold text-capitalize">
                                {{ $course->type ?? 'Core' }}
                            </span>
                        </div>

                    </div>

                </div>

                {{-- MAIN GRID --}}
                <div class="row g-4">

                    {{-- LEFT: PROGRAMMES --}}
                    <div class="col-md-6">

                        <h6 class="fw-bold mb-2">Programmes offering this course</h6>

                        <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">

                            @forelse($course->programmes as $programme)
                                <div class="py-1 border-bottom small">
                                    {{ $programme->programme_name }}
                                </div>
                            @empty
                                <div class="text-muted small">No programme assigned</div>
                            @endforelse

                        </div>

                    </div>

                    {{-- RIGHT: STATS --}}
                    <div class="col-md-6">

                        <h6 class="fw-bold mb-2">Statistics</h6>

                        <div class="border rounded p-3">

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Registered Students</span>
                                <span class="fw-bold">{{ $course->students_count }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Programme Coverage</span>
                                <span class="fw-bold">{{ $course->programmes->count() }}</span>
                            </div>

                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Materials</span>
                                <span class="fw-bold">0</span>
                            </div>

                        </div>

                    </div>

                </div>

                {{-- TEACHING ACTIVITY --}}
                <div class="mt-4">

                    <h6 class="fw-bold mb-2">Teaching Activity</h6>

                    <div class="border rounded p-3">

                        <div class="row text-center">

                            <div class="col-md-4">
                                <small class="text-muted d-block">Classes Held</small>
                                <span class="fw-bold fs-5">
                                    {{ $course->classes_held ?? 0 }}
                                </span>
                            </div>

                            <div class="col-md-4">
                                <small class="text-muted d-block">Attendance Rate</small>
                                <span class="fw-bold fs-5">
                                    {{ $course->attendance_rate ?? '0%' }}
                                </span>
                            </div>
                            
                            <div class="col-md-4">
                                <small class="text-muted d-block">Last Class Held</small>
                                <span class="fw-semibold small">
                                    {{ $course->updated_at->diffForHumans() }}
                                </span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer">
                <button class="btn btn-primary px-4" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>

    </div>

</div>
