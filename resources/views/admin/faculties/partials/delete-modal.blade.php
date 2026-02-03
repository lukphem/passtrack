<div class="modal fade" id="deleteFaculty{{ $faculty->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Confirm Deletion
                </h5>
                <button class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="fw-bold mb-2">
                    Are you sure you want to delete this faculty?
                </p>

                <p class="text-muted small mb-0">
                    Deleting <strong>{{ $faculty->name }}</strong> will:
                </p>

                <ul class="text-muted small mt-2">
                    <li>Remove the faculty permanently</li>
                    <li>Disconnect all related departments</li>
                    <li>Affect students associated with this faculty</li>
                </ul>

                <p class="text-danger small mt-2 mb-0">
                    This action cannot be undone.
                </p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                <form method="POST"
                      action="{{ route('admin.faculties.destroy', $faculty) }}">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger">
                        Yes, Delete Faculty
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
