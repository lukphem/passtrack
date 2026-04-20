{{-- ================= EDIT STUDENT MODAL ================= --}}
<div class="modal fade"
     id="editStudentModal{{ $student->id }}"
     tabindex="-1">
     @if($errors->any() && session('edit_student_error')) style="display:block;" @endif>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <form method="POST"
              action="{{ route('admin.students.update', $student->student->id) }}"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-content">

                {{-- HEADER --}}
                <div class="modal-header bg-warning text-dark">
                    <h5>Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- BODY --}}
                <div class="modal-body">
                    <div class="container-fluid">

                        {{-- PERSONAL INFO --}}
                        <h6 class="fw-bold mb-2">Personal Information</h6>
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label>First Name *</label>
                                <input type="text" name="first_name" class="form-control"
                                       value="{{ old('first_name', $student->first_name) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label>Middle Name</label>
                                <input type="text" name="middle_name" class="form-control"
                                       value="{{ old('middle_name', $student->middle_name) }}">
                            </div>

                            <div class="col-md-4">
                                <label>Last Name *</label>
                                <input type="text" name="last_name" class="form-control"
                                       value="{{ old('last_name', $student->last_name) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label>Email *</label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ old('email', $student->email) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ old('phone', $student->student->phone) }}">
                            </div>

                            <div class="col-md-4">
                                <label>Gender *</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select</option>
                                    <option value="male" {{ old('gender', $student->student->gender)=='male'?'selected':'' }}>Male</option>
                                    <option value="female" {{ old('gender', $student->student->gender)=='female'?'selected':'' }}>Female</option>
                                    <option value="other" {{ old('gender', $student->student->gender)=='other'?'selected':'' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Date of Birth *</label>
                                <input type="date" name="date_of_birth" class="form-control"
                                       value="{{ old('date_of_birth', $student->student->date_of_birth) }}" required>
                            </div>

                            {{-- NATIONALITY --}}
                            <div class="col-md-4">
                                <label>Nationality *</label>
                                <select name="nationality"
                                        id="nationality{{ $student->id }}"
                                        class="form-select" required>
                                    @foreach($countries as $country)
                                        <option value="{{ $country }}"
                                            {{ old('nationality', trim($student->student->nationality)) == $country ? 'selected' : '' }}>
                                            {{ $country }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- STATE --}}
                            <div class="col-md-4" id="stateWrapper{{ $student->id }}"></div>

                            {{-- LGA --}}
                            <div class="col-md-4" id="lgaWrapper{{ $student->id }}"></div>

                            <div class="col-md-8">
                                <label>Address</label>
                                <textarea name="address" class="form-control">{{ old('address', $student->student->address) }}</textarea>
                            </div>

                            {{-- PHOTO --}}
                            <div class="col-md-4">
                                <label>Current Photo</label><br>

                                @if(!empty($student->student?->profile_photo))
                                <img src="{{ asset('storage/'.$student->student->profile_photo) }}"
                                    alt="Profile Photo"
                                    class="img-thumbnail"
                                    style="max-width:150px; max-height:200px;">
                                @else
                                <img src="https://via.placeholder.com/150x200?text=No+Photo"
                                    class="img-thumbnail">
                                @endif
                                <br>
                                <label>Change Photo</label>
                                <input type="file" name="profile_photo" class="form-control">
                            </div>

                        </div>

                        {{-- ACADEMIC --}}
                        <h6 class="fw-bold mt-4 mb-2">Academic Information</h6>
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label>Programme</label>
                                <select name="programme_id" class="form-select">
                                    @foreach($programmes as $programme)
                                        <option value="{{ $programme->id }}"
                                            {{ old('programme_id', $student->programme_id)==$programme->id?'selected':'' }}>
                                            {{ $programme->programme_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Mode of Admission</label>
                                <select name="mode_of_admission" class="form-select">
                                    @foreach(['UTME','Direct Entry','Transfer','Pre-degree','Post-degree','Others'] as $mode)
                                        <option value="{{ $mode }}"
                                            {{ old('mode_of_admission', $student->mode_of_admission)==$mode?'selected':'' }}>
                                            {{ $mode }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Entry Level</label>
                                <input type="number" name="entry_level" class="form-control"
                                       value="{{ old('entry_level', $student->student->entry_level) }}">
                            </div>

                            <div class="col-md-2">
                                <label>Level</label>
                                <input type="number" name="level" class="form-control"
                                       value="{{ old('level', $student->student->level) }}">
                            </div>

                           <div class="col-md-4">
                                <label class="d-flex justify-content-between align-items-center">
                                    <span>Matric No</span>

                                    <div class="form-check">
                                        <input class="form-check-input enable-matric-edit"
                                            type="checkbox"
                                            data-id="{{ $student->id }}">
                                        <label class="form-check-label small">Edit</label>
                                    </div>
                                </label>

                                <input type="text"
                                    name="matric_no"
                                    id="matric_no{{ $student->id }}"
                                    class="form-control"
                                    value="{{ old('matric_no', $student->student?->matric_no) }}"
                                    readonly>
                            </div>

                        </div>

                        {{-- STATUS --}}

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>Status</label>
                                <select name="status" class="form-select">
                                    @foreach(['active','graduated','suspended','withdrawn'] as $status)
                                        <option value="{{ $status }}"
                                            {{ old('status', $student->student->status)==$status?'selected':'' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-warning">Update Student</button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
function initEditLocationSelectors(id, oldState = null, oldLga = null) {

    let nationality = document.getElementById('nationality' + id);
    let stateWrapper = document.getElementById('stateWrapper' + id);
    let lgaWrapper = document.getElementById('lgaWrapper' + id);
    const nigeriaStates = @json($nigeriaStates);
    function loadStatesAndLGAs() {

        stateWrapper.innerHTML =
            `<label>State *</label>
             <select name="state_of_origin" id="state_of_origin${id}" class="form-select" required></select>`;

        lgaWrapper.innerHTML =
            `<label>LGA *</label>
             <select name="lga_of_origin" id="lga_of_origin${id}" class="form-select" required></select>`;

        let stateSelect = document.getElementById('state_of_origin' + id);
        let lgaSelect = document.getElementById('lga_of_origin' + id);

        stateSelect.innerHTML = '<option value="">Select State</option>';

        Object.keys(nigeriaStates).forEach(state => {
            stateSelect.innerHTML += `<option value="${state}">${state}</option>`;
        });

        // ✅ SET OLD STATE
        if (oldState) {
            stateSelect.value = oldState;
        }

        // LOAD LGAs
        function loadLGAs(state, selectedLga = null) {

            let lgas = nigeriaStates[state] || [];

            lgaSelect.innerHTML = '<option value="">Select LGA</option>';

            lgas.forEach(lga => {
                lgaSelect.innerHTML += `<option value="${lga}">${lga}</option>`;
            });

            // ✅ SET OLD LGA AFTER POPULATION
            if (selectedLga) {
                lgaSelect.value = selectedLga;
            }
        }

        // INITIAL LGA LOAD
        if (oldState) {
            loadLGAs(oldState, oldLga);
        }

        // ON CHANGE
        stateSelect.addEventListener('change', function () {
            loadLGAs(this.value);
        });
    }

    function loadForeignInputs() {
        stateWrapper.innerHTML =
            `<label>State / Province *</label>
             <input type="text" name="state_of_origin"
             class="form-control" value="${oldState ?? ''}" required>`;

        lgaWrapper.innerHTML =
            `<label>City / Region *</label>
             <input type="text" name="lga_of_origin"
             class="form-control" value="${oldLga ?? ''}" required>`;
    }

    // CHANGE EVENT
    nationality.addEventListener('change', function () {
        if (this.value === 'Nigeria') {
            loadStatesAndLGAs();
        } else {
            loadForeignInputs();
        }
    });

    // INITIAL LOAD
    if (nationality.value === 'Nigeria') {
        loadStatesAndLGAs();
    } else {
        loadForeignInputs();
    }
}

// INIT WHEN MODAL OPENS
document.getElementById('editStudentModal{{ $student->id }}')
.addEventListener('shown.bs.modal', function () {

    initEditLocationSelectors(
        {{ $student->id }},
        @json(old('state_of_origin', $student->student?->state_of_origin)),
        @json(old('lga_of_origin', $student->student?->lga_of_origin))
    );

});


//edit matric
document.addEventListener('change', function (e) {

    if (e.target.classList.contains('enable-matric-edit')) {

        let id = e.target.dataset.id;
        let input = document.getElementById('matric_no' + id);

        if (e.target.checked) {
            input.removeAttribute('readonly');
            input.focus();
        } else {
            input.setAttribute('readonly', true);
        }
    }

});
</script>

