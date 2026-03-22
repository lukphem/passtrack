{{-- Add Semester Modal --}}
<div class="modal fade"
     id="addSemesterModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            <form method="POST"
                  action="{{ route('admin.academic-semester.store') }}"
                  class="needs-validation"
                  novalidate>
                @csrf

                {{-- Header --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-calendar-plus-fill"></i> Add Semester
                    </h5>
                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                {{-- Body --}}
                <div class="modal-body">

                    {{-- GLOBAL VALIDATION ERRORS --}}
                    @if(session('add_semester_error'))
                        <div class="alert alert-danger">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">

                        {{-- Academic Session --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="text-danger">*</span> Academic Session
                            </label>
                            <select name="academic_session_id"
                                    class="form-select @error('academic_session_id') is-invalid @enderror"
                                    required>
                                <option value="">Select Session</option>
                                @foreach($academicSessions as $session)
                                    <option value="{{ $session->id }}"
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
                            <label class="form-label">
                                <span class="text-danger">*</span> Semester Name
                            </label>
                            <input type="text"
                                   name="semester_name"
                                   value="{{ old('semester_name') }}"
                                   class="form-control @error('semester_name') is-invalid @enderror"
                                   placeholder="e.g. First Semester"
                                   required>
                            @error('semester_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Start Date --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="text-danger">*</span> Start Date
                            </label>
                            <input type="date"
                                   name="start_date"
                                   value="{{ old('start_date') ? \Carbon\Carbon::parse(old('start_date'))->format('d-m-Y') : '' }}"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- End Date --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="text-danger">*</span> End Date
                            </label>
                            <input type="date"
                                   name="end_date"
                                   value="{{ old('end_date') ? \Carbon\Carbon::parse(old('end_date'))->format('d-m-Y') : '' }}"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Registration Section --}}
                        <div class="col-12">
                            <hr>
                            <h6 class="fw-bold mb-1">
                                <i class="bi bi-pencil-square text-primary"></i>
                                Course Registration Window
                            </h6>
                            <p class="text-muted small mb-0">
                                Define when students are allowed to register for courses.
                            </p>
                        </div>

                        {{-- Registration Opens --}}
                        <div class="col-md-6">
                            <label class="form-label">Registration Opens</label>
                            <input type="datetime-local"
                                   name="registration_start_date"
                                   value="{{ old('registration_start_date') ? \Carbon\Carbon::parse(old('registration_start_date'))->format('d-m-Y\TH:i') : '' }}"
                                   class="form-control @error('registration_start_date') is-invalid @enderror">
                            @error('registration_start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Registration Closes --}}
                        <div class="col-md-6">
                            <label class="form-label">Registration Closes</label>
                            <input type="datetime-local"
                                   name="registration_end_date"
                                   value="{{ old('registration_end_date') ? \Carbon\Carbon::parse(old('registration_end_date'))->format('d-m-Y\TH:i') : '' }}"
                                   class="form-control @error('registration_end_date') is-invalid @enderror">
                            @error('registration_end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Registration Toggle --}}
                        <div class="col-12">
                            <input type="hidden" name="registration_allowed" value="0">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="registration_allowed"
                                       value="1"
                                       id="registration_allowed"
                                       {{ old('registration_allowed') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold"
                                       for="registration_allowed">
                                    Allow Course Registration
                                </label>
                            </div>
                        </div>

                        {{-- Active Toggle --}}
                        <div class="col-12">
                            <input type="hidden" name="is_active" value="0">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="is_active"
                                       value="1"
                                       id="is_active"
                                       {{ old('is_active') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold"
                                       for="is_active">
                                    Set as Current Active Semester
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Save Semester
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- Reopen modal if validation fails --}}
@if(session('add_semester_error'))
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modalEl = document.getElementById('addSemesterModal');
    if(modalEl){
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
});
</script>
@endif
