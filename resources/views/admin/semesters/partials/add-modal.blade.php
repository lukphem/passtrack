<div class="modal fade" id="addSemesterModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ route('admin.academic-semester.store') }}">
            @csrf

            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-calendar-plus"></i> Add New Semester
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- GLOBAL ERRORS --}}
                    @if ($errors->any() && !session('edit_semester_id'))
                        <div class="alert alert-danger">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">
                        {{-- Session Selection --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Academic Session</label>
                            <select name="academic_session_id" id="add_session"
                                    class="form-select @error('academic_session_id') is-invalid @enderror"
                                    required>
                                <option value="">Select session</option>
                                @foreach($academicSessions as $session)
                                    <option value="{{ $session->id }}"
                                            data-start="{{ $session->start_date }}"
                                            data-end="{{ $session->end_date }}"
                                            {{ old('academic_session_id') == $session->id ? 'selected' : '' }}>
                                        {{ $session->session_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_session_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Semester Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Semester Name</label>
                            <input type="text" name="semester_name" value="{{ old('semester_name') }}"
                                   class="form-control @error('semester_name') is-invalid @enderror"
                                   placeholder="e.g. First Semester" required>
                            @error('semester_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Semester Dates --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary">Semester Start Date</label>
                            <input type="date" name="start_date" id="add_start_date"
                                   value="{{ old('start_date') }}"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary">Semester End Date</label>
                            <input type="date" name="end_date" id="add_end_date"
                                   value="{{ old('end_date') }}"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   required>
                        </div>

                        <hr class="my-3 text-muted">
                        <h6 class="mb-0"><i class="bi bi-pencil-square"></i> Course Registration Window</h6>
                        <p class="text-muted small">Define when students are allowed to register for courses.</p>

                        {{-- Registration Window Dates --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-info">Registration Opens</label>
                            <input type="datetime-local" name="registration_start_date"
                                   value="{{ old('registration_start_date') }}"
                                   class="form-control @error('registration_start_date') is-invalid @enderror">
                            <div class="form-text">Optional: Leave blank if not yet decided.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-info">Registration Closes</label>
                            <input type="datetime-local" name="registration_end_date"
                                   value="{{ old('registration_end_date') }}"
                                   class="form-control @error('registration_end_date') is-invalid @enderror">
                        </div>

                        {{-- Registration Allowed Checkbox --}}
                        <div class="col-12">
                            <input type="hidden" name="registration_allowed" value="0">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="registration_allowed"
                                       id="registration_allowed" value="1"
                                       {{ old('registration_allowed') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="registration_allowed">
                                    Allow Course Registration
                                </label>
                            </div>
                        </div>

                        {{-- Active Checkbox --}}
                        <div class="col-12">
                            <input type="hidden" name="is_active" value="0">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active"
                                       id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">
                                    Set as Current Active Semester
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> Save Semester
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Auto-open Add Modal on validation errors --}}
@if ($errors->any() && session('add_semester_error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('addSemesterModal');
    if(modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
});
</script>
@endif
