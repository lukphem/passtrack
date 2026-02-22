<!-- Delete Programme Modal -->
<div class="modal fade" id="deleteProgramme{{ $programme->id }}" tabindex="-1">
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
                    Are you sure you want to delete this programme?
                </p>

                <p class="text-muted small mb-0">
                    Deleting <strong>{{ $programme->programme_name }}</strong> will:
                </p>

                <ul class="text-muted small mt-2">
                    <li>Remove the programme permanently</li>
                    <li>Disconnect it from its levels and courses</li>
                    <li>Affect students enrolled under this programme</li>
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
                      action="{{ route('admin.programmes.destroy', $programme) }}">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger">
                        Yes, Delete Programme
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
