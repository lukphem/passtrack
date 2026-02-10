<div class="modal fade" id="deleteSemester{{ $semester->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.academic-semester.destroy', $semester) }}">
            @csrf
            @method('DELETE')

            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-trash"></i> Delete Semester
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-2">
                        You are about to delete the semester
                        <strong>{{ $semester->semester_name }}</strong>
                        under the
                        <strong>{{ $semester->academicSession->label ?? $semester->academicSession->session_name }}</strong>
                        academic session.
                    </p>

                    <div class="alert alert-danger mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <strong>This action is permanent and will affect academic operations.</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            <li>Course registration for this semester will no longer be available</li>
                            <li>Students will be unable to register or view courses tied to this semester</li>
                            <li>Any ongoing or future academic activities linked to this semester will be disrupted</li>
                            <li>Semester-specific settings, timelines, and configurations will be permanently removed</li>
                            <li>This action cannot be undone</li>
                        </ul>
                    </div>

                    @if($semester->is_active)
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="bi bi-shield-exclamation me-1"></i>
                            This semester is currently active and cannot be deleted.
                            Please deactivate it before attempting deletion.
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    @unless($semester->is_active)
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Yes, Delete Semester
                        </button>
                    @endunless
                </div>
            </div>
        </form>
    </div>
</div>
