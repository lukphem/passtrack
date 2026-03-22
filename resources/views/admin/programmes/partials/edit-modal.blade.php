@foreach($programmes as $programme)

{{-- Edit Programme Modal --}}
<div class="modal fade"
     id="editProgramme{{ $programme->id }}"
     tabindex="-1"
     aria-hidden="true">

<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content border-0 shadow">

<form method="POST"
      action="{{ route('admin.programmes.update', $programme) }}"
      class="needs-validation"
      novalidate>
@csrf
@method('PUT')

{{-- Header --}}
<div class="modal-header bg-primary text-white">
<h5 class="modal-title fw-bold">
<i class="bi bi-pencil-square"></i> Edit Programme
</h5>

<button type="button"
        class="btn-close btn-close-white"
        data-bs-dismiss="modal"></button>
</div>

{{-- Body --}}
<div class="modal-body">

@if($errors->any() && session('edit_programme_error') == $programme->id)
<div class="alert alert-danger">
<strong>Please fix the errors below.</strong>
</div>
@endif

<div class="row g-3">

{{-- PROGRAMME NAME --}}
<div class="col-md-6">
<label class="form-label"><span class="text-danger">*</span> Programme Name</label>
<input type="text"
       name="programme_name"
       value="{{ old('programme_name', $programme->programme_name) }}"
       class="form-control @error('programme_name') is-invalid @enderror"
       placeholder="e.g. Computer Science"
       required>
@error('programme_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- PROGRAMME CODE --}}
<div class="col-md-6">
<label class="form-label"><span class="text-danger">*</span> Programme Code</label>
<input type="text"
       name="programme_code"
       value="{{ old('programme_code', $programme->programme_code) }}"
       class="form-control @error('programme_code') is-invalid @enderror"
       placeholder="e.g. BSC-CS"
       required>
@error('programme_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- DEPARTMENT --}}
<div class="col-md-6">
<label class="form-label"><span class="text-danger">*</span> Department</label>
<select name="department_id"
        class="form-select @error('department_id') is-invalid @enderror"
        required>
<option value="">Select Department</option>
@foreach($departments as $department)
<option value="{{ $department->id }}"
{{ old('department_id', $programme->department_id) == $department->id ? 'selected' : '' }}>
{{ $department->dept_name }}
</option>
@endforeach
</select>
@error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- PROGRAMME DURATION --}}
<div class="col-md-3">
<label class="form-label"><span class="text-danger">*</span> Duration (Years)</label>
<input type="number"
       name="programme_duration"
       value="{{ old('programme_duration', $programme->programme_duration) }}"
       class="form-control @error('programme_duration') is-invalid @enderror"
       min="1" max="10" required>
@error('programme_duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- LEVEL TYPE --}}
<div class="col-md-3">
<label class="form-label">Level Type</label>
<select name="programme_level_type"
        class="form-select @error('programme_level_type') is-invalid @enderror">
<option value="">Select Level</option>
<option value="Undergraduate"
{{ old('programme_level_type', $programme->programme_level_type) == 'Undergraduate' ? 'selected' : '' }}>
Undergraduate
</option>
<option value="Postgraduate"
{{ old('programme_level_type', $programme->programme_level_type) == 'Postgraduate' ? 'selected' : '' }}>
Postgraduate
</option>
</select>
@error('programme_level_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- START DATE --}}
<div class="col-md-6">
<label class="form-label">Programme Start Date</label>
<input type="date"
       name="programme_start_date"
       value="{{ old('programme_start_date', optional($programme->programme_start_date)->format('Y-m-d')) }}"
       class="form-control @error('programme_start_date') is-invalid @enderror">
@error('programme_start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- ACCREDITATION --}}
<div class="col-md-6">
<label class="form-label">Accreditation Status</label>
<select name="accreditation_status"
        class="form-select @error('accreditation_status') is-invalid @enderror">
<option value="">Select Status</option>
<option value="Full" {{ old('accreditation_status', $programme->accreditation_status) == 'Full' ? 'selected' : '' }}>Full</option>
<option value="Interim" {{ old('accreditation_status', $programme->accreditation_status) == 'Interim' ? 'selected' : '' }}>Interim</option>
<option value="None" {{ old('accreditation_status', $programme->accreditation_status) == 'None' ? 'selected' : '' }}>None</option>
</select>
@error('accreditation_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- YEAR --}}
<div class="col-md-6">
<label class="form-label">Accreditation Year</label>
<input type="number"
       name="accreditation_year"
       value="{{ old('accreditation_year', $programme->accreditation_year) }}"
       class="form-control @error('accreditation_year') is-invalid @enderror"
       min="2000" max="{{ date('Y') }}">
@error('accreditation_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- INDUSTRIAL TRAINING --}}
<div class="col-md-6">
<input type="hidden" name="industrial_training_required" value="0">
<div class="form-check form-switch mt-2">
<input class="form-check-input"
       type="checkbox"
       id="industrial_training_required_{{ $programme->id }}"
       name="industrial_training_required"
       value="1"
       {{ old('industrial_training_required', $programme->industrial_training_required) ? 'checked' : '' }}>
<label class="form-check-label fw-bold">Industrial Training Required</label>
</div>
</div>

{{-- INDUSTRIAL TRAINING LEVEL --}}
<div class="col-md-6">
<label class="form-label">Industrial Training Level</label>
<input type="number"
       name="industrial_training_level"
       id="industrial_training_level_{{ $programme->id }}"
       value="{{ old('industrial_training_level', $programme->industrial_training_level) }}"
       class="form-control @error('industrial_training_level') is-invalid @enderror"
       min="1" max="500"
       {{ old('industrial_training_required', $programme->industrial_training_required) ? '' : 'disabled' }}>
@error('industrial_training_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- STATUS --}}
<div class="col-md-6">
<input type="hidden" name="programme_status" value="0">
<div class="form-check form-switch">
<input class="form-check-input"
       type="checkbox"
       name="programme_status"
       value="1"
       id="programme_status_{{ $programme->id }}"
       {{ old('programme_status', $programme->programme_status) ? 'checked' : '' }}>
<label class="form-check-label fw-bold">Active Programme</label>
</div>
</div>

{{-- CUSTOM ACADEMIC SETTINGS --}}
<div class="col-md-6">
    <input type="hidden" name="use_custom_academic_settings" value="0">

    <div class="form-check form-switch">
        <input class="form-check-input"
               type="checkbox"
               name="use_custom_academic_settings"
               value="1"
               id="use_custom_academic_settings_{{ $programme->id }}"
               {{ old('use_custom_academic_settings', $programme->use_custom_academic_settings) ? 'checked' : '' }}>

        <label class="form-check-label fw-bold">
            Use Custom Academic Settings
        </label>
    </div>
</div>

{{-- DESCRIPTION --}}
<div class="col-12">
<label class="form-label">Description</label>
<textarea name="programme_description"
          rows="3"
          class="form-control @error('programme_description') is-invalid @enderror"
          placeholder="Programme description">{{ old('programme_description', $programme->programme_description) }}</textarea>
@error('programme_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

</div>
</div>

{{-- Footer --}}
<div class="modal-footer bg-light">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-primary">
<i class="bi bi-check-circle"></i> Update Programme
</button>
</div>

</form>
</div>
</div>
</div>
@endforeach

@foreach($programmes as $programme)
    @if(session('edit_programme_error') == $programme->id)
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('editProgramme{{ $programme->id }}');
        if (modalEl) new bootstrap.Modal(modalEl).show();
    });
    </script>
    @endif
@endforeach


<script>
document.querySelectorAll('[id^="industrial_training_required_"]').forEach(function (checkbox) {
    const id = checkbox.id.split('_').pop();
    const level = document.getElementById('industrial_training_level_' + id);

    if (level) {
        level.disabled = !checkbox.checked;
        checkbox.addEventListener('change', function () {
            level.disabled = !this.checked;
            if (!this.checked) level.value = '';
        });
    }
});
</script>



{{-- Conditional Custom Settings --}}
<div id="custom_academic_settings_add" class="row g-3" style="{{ old('use_custom_academic_settings') ? '' : 'display:none;' }}">
    {{-- CURRENT SESSION --}}
    <div class="col-md-6">
        <label class="form-label">Current Academic Session</label>
        <select name="current_session_id" class="form-select">
            <option value="">-- Select Session --</option>
            @foreach($sessions as $session)
                <option value="{{ $session->id }}" {{ old('current_session_id') == $session->id ? 'selected' : '' }}>
                    {{ $session->session_name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- CURRENT SEMESTER --}}
    <div class="col-md-6">
        <label class="form-label">Current Semester</label>
        <select name="current_semester_id" class="form-select">
            <option value="">-- Select Semester --</option>
            @foreach($semesters as $semester)
                <option value="{{ $semester->id }}" {{ old('current_semester_id') == $semester->id ? 'selected' : '' }}>
                    {{ $semester->semester_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
