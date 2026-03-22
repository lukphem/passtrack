{{-- Edit Academic Session Modal --}}
<div class="modal fade"
     id="editSessionModal{{ $session->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            <form method="POST"
                  action="{{ route('admin.academic-sessions.update', $session->id) }}"
                  class="needs-validation"
                  novalidate>
                @csrf
                @method('PUT')

                {{-- Header --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square"></i> Edit Academic Session
                    </h5>
                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Please fix the errors below.</strong>
                        </div>
                    @endif

                    <div class="row g-3">

                        {{-- SESSION NAME --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="text-danger">*</span> Session Name
                            </label>
                            <input type="text"
                                   name="session_name"
                                   value="{{ old('session_name', $session->session_name) }}"
                                   class="form-control @error('session_name') is-invalid @enderror"
                                   placeholder="e.g. 2025/2026"
                                   required>
                            @error('session_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6">
                            <label class="form-label">Session Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="is_active"
                                       value="1"
                                       id="activeSwitch{{ $session->id }}"
                                       {{ old('is_active', $session->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label"
                                       for="activeSwitch{{ $session->id }}">
                                    Set as Active
                                </label>
                            </div>
                            @error('is_active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- START DATE --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="text-danger">*</span> Start Date
                            </label>
                            <input type="date"
                                   name="start_date"
                                   value="{{ old('start_date', optional($session->start_date)->format('Y-m-d')) }}"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- END DATE --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="text-danger">*</span> End Date
                            </label>
                            <input type="date"
                                   name="end_date"
                                   value="{{ old('end_date', optional($session->end_date)->format('Y-m-d')) }}"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit"
                            class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Session
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- Auto-open Edit Session Modal on validation errors --}}
@if ($errors->any()
    && session('edit_session_id')
    && session('edit_session_id') == $session->id)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('editSessionModal{{ $session->id }}');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
});
</script>
@endif
