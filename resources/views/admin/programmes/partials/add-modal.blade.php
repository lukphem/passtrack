{{-- Add Programme Modal --}}
<div class="modal fade" id="addProgrammeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            <form method="POST" action="{{ route('admin.programmes.store') }}" class="needs-validation" novalidate>
                @csrf

                {{-- Header --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-journal-plus"></i> Add Programme
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body" style="max-height:70vh; overflow-y:auto;">

                    @if($errors->any() && session('add_programme_error'))
                        <div class="alert alert-danger">
                            <strong>Please fix the errors below.</strong>
                        </div>
                    @endif

                    <div class="row g-3">

                        {{-- BASIC INFO --}}
                        <div class="col-12">
                            <h6 class="fw-bold text-primary">Basic Information</h6>
                            <hr class="mt-1 mb-2">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Programme Name *</label>
                            <input type="text" name="programme_name"
                                   value="{{ old('programme_name') }}"
                                   class="form-control @error('programme_name') is-invalid @enderror"
                                   required>
                            @error('programme_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Programme Code *</label>
                            <input type="text" name="programme_code"
                                   value="{{ old('programme_code') }}"
                                   class="form-control @error('programme_code') is-invalid @enderror"
                                   required>
                            @error('programme_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Department *</label>
                            <select name="department_id"
                                    class="form-select @error('department_id') is-invalid @enderror"
                                    required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->dept_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Duration (Years) *</label>
                            <input type="number" name="programme_duration"
                                   value="{{ old('programme_duration') }}"
                                   class="form-control @error('programme_duration') is-invalid @enderror"
                                   min="1" max="10" required>
                            @error('programme_duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Level Type</label>
                            <select name="programme_level_type" class="form-select">
                                <option value="">Select</option>
                                <option value="Undergraduate" {{ old('programme_level_type') == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                <option value="Postgraduate" {{ old('programme_level_type') == 'Postgraduate' ? 'selected' : '' }}>Postgraduate</option>
                            </select>
                        </div>

                        {{-- ACADEMIC DETAILS --}}
                        <div class="col-12 mt-3">
                            <h6 class="fw-bold text-primary">Academic Details</h6>
                            <hr class="mt-1 mb-2">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="programme_start_date"
                                   value="{{ old('programme_start_date') }}"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Accreditation Status</label>
                            <select name="accreditation_status" class="form-select">
                                <option value="">Select</option>
                                <option value="Full" {{ old('accreditation_status') == 'Full' ? 'selected' : '' }}>Full</option>
                                <option value="Interim" {{ old('accreditation_status') == 'Interim' ? 'selected' : '' }}>Interim</option>
                                <option value="None" {{ old('accreditation_status') == 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>

                        {{-- INDUSTRIAL TRAINING --}}
                        <div class="col-12 mt-3">
                            <h6 class="fw-bold text-primary">Industrial Training</h6>
                            <hr class="mt-1 mb-2">
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <input type="hidden" name="industrial_training_required" value="0">

                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="industrial_training_required"
                                       name="industrial_training_required"
                                       value="1"
                                       {{ old('industrial_training_required') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold">Required</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Training Level</label>
                            <input type="number"
                                   id="industrial_training_level"
                                   name="industrial_training_level"
                                   value="{{ old('industrial_training_level') }}"
                                   class="form-control @error('industrial_training_level') is-invalid @enderror">
                            @error('industrial_training_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SETTINGS --}}
                        <div class="col-12 mt-3">
                            <h6 class="fw-bold text-primary">Settings</h6>
                            <hr class="mt-1 mb-2">
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <input type="hidden" name="use_custom_academic_settings" value="0">

                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="use_custom_academic_settings"
                                       value="1"
                                       {{ old('use_custom_academic_settings') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold">Custom Academic Settings</label>
                            </div>
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <input type="hidden" name="programme_status" value="0">

                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="programme_status"
                                       value="1"
                                       {{ old('programme_status') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold">Active Programme</label>
                            </div>
                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="col-12 mt-3">
                            <label class="form-label">Description</label>
                            <textarea name="programme_description"
                                      rows="3"
                                      class="form-control">{{ old('programme_description') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Programme</button>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('addProgrammeModal');
    const checkbox = document.getElementById('industrial_training_required');
    const level = document.getElementById('industrial_training_level');

    if (!modal || !checkbox || !level) return;

    function toggleLevel() {
        level.disabled = !checkbox.checked;

        if (!checkbox.checked) {
            level.value = '';
        }
    }

    // run on load (VERY IMPORTANT for old() values)
    toggleLevel();

    checkbox.addEventListener('change', toggleLevel);

    // reset modal properly
    modal.addEventListener('hidden.bs.modal', function () {
        modal.querySelector('form').reset();
        level.disabled = true;
    });

    @if(session('add_programme_error'))
        new bootstrap.Modal(modal).show();
    @endif

});
</script>
