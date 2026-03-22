{{-- Add Academic Session Modal --}}
<div class="modal fade"
     id="addSessionModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            <form method="POST"
                  action="{{ route('admin.academic-sessions.store') }}"
                  class="needs-validation"
                  novalidate>
                @csrf

                {{-- Header --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-calendar-plus-fill"></i> Add Academic Session
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
                                   value="{{ old('session_name') }}"
                                   class="form-control @error('session_name') is-invalid @enderror"
                                   placeholder="e.g. 2025/2026"
                                   required>
                            @error('session_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6">
                            <label class="form-label">Initial Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="is_active"
                                       value="1"
                                       id="activeSwitch"
                                       {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activeSwitch">
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
                                   value="{{ old('start_date') ? \Carbon\Carbon::parse(old('start_date'))->format('Y-m-d') : '' }}"
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
                                   value="{{ old('end_date') ? \Carbon\Carbon::parse(old('end_date'))->format('Y-m-d') : '' }}"
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
                        <i class="bi bi-check-circle"></i> Save Session
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- Auto-open Add Session Modal on validation errors --}}
@if ($errors->any() && session('add_session_error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('addSessionModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl, {
            backdrop: true,
            keyboard: true
        });
        modal.show();
    }
});
</script>
@endif
