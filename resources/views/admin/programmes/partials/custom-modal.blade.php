{{-- ================= MODALS ================= --}}
@foreach ($programmes as $programme)

<div class="modal fade" id="customAcademic{{ $programme->id }}" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered">

<form method="POST"
      action="{{ route('admin.programmes.updateCustomSettings', $programme->id) }}">
@csrf
@method('PUT')

<div class="modal-content">

{{-- HEADER --}}
<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        {{ $programme->programme->programme_name ?? 'Programme' }} - Academic Settings
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

{{-- BODY --}}
<div class="modal-body">

{{-- ✅ ERROR DISPLAY --}}
@if ($errors->any() && session('error_programme_id') == $programme->id)
    <div class="alert alert-danger">
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">

{{-- SESSION --}}
<div class="col-md-6">
    <label class="form-label">Academic Session</label>
    <select name="academic_session_id"
            class="form-select session-select"
            data-programme="{{ $programme->id }}">
        <option value="">-- Select Session --</option>

        @foreach($sessions as $session)
            <option value="{{ $session->id }}"
                {{ old('academic_session_id', $programme->academic_session_id) == $session->id ? 'selected' : '' }}>
                {{ $session->session_name }}
            </option>
        @endforeach
    </select>

    @error('academic_session_id')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

{{-- SEMESTER --}}
<div class="col-md-6">
    <label class="form-label">Semester</label>
    <select name="semester_id"
            class="form-select semester-select"
            id="semester{{ $programme->id }}">
        <option value="">-- Select Semester --</option>

        @foreach($semesters as $semester)
            <option value="{{ $semester->id }}"
                data-session="{{ $semester->academic_session_id }}"
                {{ old('semester_id', $programme->semester_id) == $semester->id ? 'selected' : '' }}>
                {{ $semester->semester_name }}
            </option>
        @endforeach
    </select>

    @error('semester_id')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>


{{-- START DATE --}}
<div class="col-md-6">
    <label class="form-label">Semester Start Date</label>
    <input type="date"
           name="start_date"
           value="{{ old('start_date', $programme->start_date ? \Carbon\Carbon::parse($programme->start_date)->format('Y-m-d') : '') }}"
           class="form-control">

    @error('start_date')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

{{-- END DATE --}}
<div class="col-md-6">
    <label class="form-label">Semester End Date</label>
    <input type="date"
           name="end_date"
           value="{{ old('end_date', $programme->end_date ? \Carbon\Carbon::parse($programme->end_date)->format('Y-m-d') : '') }}"
           class="form-control">

    @error('end_date')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

{{-- REG START --}}
<div class="col-md-6">
    <label class="form-label">Registration Start</label>
    <input type="datetime-local"
           name="registration_start_date"
           value="{{ old('registration_start_date', $programme->registration_start_date ? \Carbon\Carbon::parse($programme->registration_start_date)->format('Y-m-d\TH:i') : '') }}"
           class="form-control">

    @error('registration_start_date')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

{{-- REG END --}}
<div class="col-md-6">
    <label class="form-label">Registration End</label>
    <input type="datetime-local"
           name="registration_end_date"
           value="{{ old('registration_end_date', $programme->registration_end_date ? \Carbon\Carbon::parse($programme->registration_end_date)->format('Y-m-d\TH:i') : '') }}"
           class="form-control">

    @error('registration_end_date')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
{{-- REGISTRATION --}}
<div class="col-md-6">
    <input type="hidden" name="registration_allowed" value="0">

    <div class="form-check form-switch mt-3">
        <input class="form-check-input"
               type="checkbox"
               name="registration_allowed"
               value="1"
               id="registration_allowed_{{ $programme->id }}"
               {{ old('registration_allowed', $programme->registration_allowed) ? 'checked' : '' }}>

        <label class="form-check-label fw-bold"
               for="registration_allowed_{{ $programme->id }}">
            Allow Course Registration
        </label>
    </div>
</div>

</div>
</div>

{{-- FOOTER --}}
<div class="modal-footer">
    <button type="submit" class="btn btn-primary">Save Settings</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
</div>

</div>
</form>

</div>
</div>

@endforeach
{{-- ================= END OF MODALS ================= --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    //  Filter semesters
    document.querySelectorAll('.session-select').forEach(select => {

        filterSemesters(select, true);

        select.addEventListener('change', function () {
            filterSemesters(this, false);
        });

    });

    function filterSemesters(select, isInitialLoad = false) {

        let programmeId = select.dataset.programme;
        let selectedSession = select.value;

        let semesterDropdown = document.getElementById('semester' + programmeId);

        if (!semesterDropdown) return;

        let options = semesterDropdown.querySelectorAll('option');

        options.forEach(option => {
            if (!option.value) return;
            option.hidden = option.dataset.session !== selectedSession;
        });

        if (!isInitialLoad) {
            semesterDropdown.value = '';
        }
    }

    //  Auto open modal on validation error
    @if(session('error_programme_id'))
        let modalId = "customAcademic{{ session('error_programme_id') }}";
        let modalElement = document.getElementById(modalId);

        if (modalElement) {
            let modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    @endif

});
</script>
