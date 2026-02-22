{{-- Delete Lecturer Modal --}}
<div class="modal fade" id="deleteLecturerModal{{ $lecturer->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            {{-- Header --}}
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-trash"></i> Delete Lecturer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <p class="mb-3">
                    Are you sure you want to delete <strong>{{ $lecturer->title }} {{ $lecturer->first_name }} {{ $lecturer->last_name }}</strong>?
                </p>
                <p class="text-danger small">
                    <i class="bi bi-exclamation-triangle-fill"></i> Deleting this lecturer will:
                </p>
                <ul class="small text-muted mb-0">
                    <li>Remove all lecturer personal details permanently.</li>
                    <li>Unassign all courses or modules linked to this lecturer.</li>
                    <li>Disrupt attendance, class schedules, and academic records.</li>
                    <li>Revoke system access and login credentials.</li>
                    <li>Potentially affect reports, analytics, and integrations.</li>
                </ul>
            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <form action="{{ route('admin.lecturers.destroy', $lecturer->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete Lecturer
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
