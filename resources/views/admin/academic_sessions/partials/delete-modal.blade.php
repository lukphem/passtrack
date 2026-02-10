<div class="modal fade" id="deleteSession{{ $session->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            {{-- Red Header for High Warning --}}
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Confirm Deletion
                </h5>
                <button class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="fw-bold mb-2">
                    Are you sure you want to delete this academic session?
                </p>

                <p class="text-muted small mb-0">
                    Deleting <strong>{{ $session->session_name }}</strong> will:
                </p>

                <ul class="text-muted small mt-2">
                    <li>Permanently remove the session record from the system</li>
                    <li>Remove the associated calendar dates ({{ \Carbon\Carbon::parse($session->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($session->end_date)->format('M d, Y') }})</li>
                    <li>Potentially affect historical records or reports tied to this session</li>
                </ul>

                @if($session->is_active)
                <div class="alert alert-danger py-2 px-3 mt-3 mb-0" style="font-size: 0.8rem;">
                    <i class="bi bi-x-circle-fill me-1"></i>
                    <strong>Warning:</strong> This is the <strong>Active</strong> session. Deleting it may cause system errors.
                </div>
                @else
                <p class="text-danger small mt-2 mb-0">
                    <i class="bi bi-info-circle me-1"></i> This action cannot be undone.
                </p>
                @endif
            </div>

            <div class="modal-footer bg-light">
                <button class="btn btn-light border fw-semibold"
                        data-bs-dismiss="modal"
                        style="border-radius: 8px;">
                    Cancel
                </button>

                <form method="POST" action="{{ route('admin.academic-sessions.destroy', $session->id) }}">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger fw-semibold"
                            style="border-radius: 8px;"
                            {{ $session->is_active ? 'disabled' : '' }}>
                        Yes, Delete Session
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
