<div class="modal fade" id="editSemester{{ $semester->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ route('admin.academic-semester.update', $semester->id) }}">
            @csrf
            @method('PUT')

            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square"></i> Edit Semester
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- GLOBAL ERRORS --}}
                    @if ($errors->any() && session('edit_semester_id') == $semester->id)
                        <div class="alert alert-danger">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">
                        {{-- Academic Session --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Academic Session</label>
                            <select name="academic_session_id" class="form-select" required>
                                @foreach($academicSessions as $session)
                                    <option value="{{ $session->id }}"
                                        data-start="{{ $session->start_date }}"
                                        data-end="{{ $session->end_date }}"
                                        {{ old('academic_session_id', $semester->academic_session_id) == $session->id ? 'selected' : '' }}>
                                        {{ $session->session_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Semester Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Semester Name</label>
                            <input type="text" name="semester_name"
                                   value="{{ old('semester_name', $semester->semester_name) }}"
                                   class="form-control" required>
                        </div>

                        {{-- Semester Dates --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary">Semester Start Date</label>
                            <input type="date" name="start_date"
                                   value="{{ old('start_date', $semester->start_date->format('Y-m-d')) }}"
                                   class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary">Semester End Date</label>
                            <input type="date" name="end_date"
                                   value="{{ old('end_date', $semester->end_date->format('Y-m-d')) }}"
                                   class="form-control" required>
                        </div>

                        <hr class="my-3 text-muted">
                        <h6 class="mb-0"><i class="bi bi-pencil-square"></i> Course Registration Window</h6>
                        <p class="text-muted small">Define when students are allowed to register for courses.</p>

                        {{-- Registration Dates --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-info">Registration Opens</label>
                            <input type="datetime-local" name="registration_start_date"
                                   value="{{ old('registration_start_date', optional($semester->registration_start_date)->format('Y-m-d\TH:i')) }}"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-info">Registration Closes</label>
                            <input type="datetime-local" name="registration_end_date"
                                   value="{{ old('registration_end_date', optional($semester->registration_end_date)->format('Y-m-d\TH:i')) }}"
                                   class="form-control">
                        </div>

                        {{-- Registration Allowed --}}
                        <div class="col-12">
                            <input type="hidden" name="registration_allowed" value="0">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="registration_allowed"
                                       id="edit_reg_allowed_{{ $semester->id }}" value="1"
                                       {{ old('registration_allowed', $semester->registration_allowed) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold"
                                       for="edit_reg_allowed_{{ $semester->id }}">
                                    Allow Course Registration
                                </label>
                            </div>
                        </div>

                        {{-- Active --}}
                        <div class="col-12">
                            <input type="hidden" name="is_active" value="0">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active"
                                       id="edit_active_{{ $semester->id }}" value="1"
                                       {{ old('is_active', $semester->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold"
                                       for="edit_active_{{ $semester->id }}">
                                    Set as Current Active Semester
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> Update Semester
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Auto-open Edit Modal on validation errors --}}
@if ($errors->any() && session('edit_semester_id') == $semester->id)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('editSemester{{ $semester->id }}');
    if(modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
});
</script>
@endif
