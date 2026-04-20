{{-- ================= ADD STUDENT MODAL ================= --}}
<div class="modal fade @if($errors->any() && session('add_student_error')) show @endif"
     id="addStudentModal"
     tabindex="-1"
     @if($errors->any() && session('add_student_error')) style="display:block;" @endif>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <form method="POST" action="{{ route('admin.students.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-primary text-white">
                    <h5>Add Student</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- ERROR ALERT -->
                @if ($errors->any() && session('add_student_error'))
                    <div class="alert alert-danger m-3">
                        <strong>Whoops!</strong> Please fix the following:
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- BODY -->
                <div class="modal-body">
                    <div class="container-fluid">

                        {{-- ================== PERSONAL INFO ================== --}}
                        <h6 class="fw-bold mb-2">Personal Information</h6>
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label>First Name *</label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name') }}" required>
                                @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Middle Name</label>
                                <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror"
                                       value="{{ old('middle_name') }}">
                                @error('middle_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Last Name *</label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name') }}" required>
                                @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Email *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required>
                                @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Phone *</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}">
                                @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Gender *</label>
                                <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                    <option value="">Select</option>
                                    <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                                    <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
                                    <option value="other" {{ old('gender')=='other'?'selected':'' }}>Other</option>
                                </select>
                                @error('gender') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Date of Birth *</label>
                                <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                                       value="{{ old('date_of_birth') }}" required>
                                @error('date_of_birth') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Nationality *</label>
                                <select name="nationality" id="nationality" class="form-select @error('nationality') is-invalid @enderror" required>
                                    @foreach($countries as $country)
                                        <option value="{{ $country }}" {{ old('nationality','Nigeria') == $country ? 'selected' : '' }}>
                                            {{ $country }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('nationality') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4" id="stateWrapper">
                                <label>State *</label>
                                <select name="state_of_origin" id="state_of_origin" class="form-select @error('state_of_origin') is-invalid @enderror" required></select>
                                @error('state_of_origin') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4" id="lgaWrapper">
                                <label>LGA *</label>
                                <select name="lga_of_origin" id="lga_of_origin" class="form-select @error('lga_of_origin') is-invalid @enderror" required></select>
                                @error('lga_of_origin') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12">
                                <label>Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" maxlength="250">{{ old('address') }}</textarea>
                                @error('address') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Profile Photo *</label>
                                <input type="file" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" required>
                                @error('profile_photo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                        </div>

                        {{-- ================== ACADEMIC INFO ================== --}}
                        <h6 class="fw-bold mt-4 mb-2">Academic Information</h6>
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label>Programme *</label>
                                <select name="programme_id" class="form-select @error('programme_id') is-invalid @enderror" required>
                                    <option value="">Select Programme</option>
                                    @foreach($programmes as $programme)
                                        <option value="{{ $programme->id }}" {{ old('programme_id')==$programme->id?'selected':'' }}>
                                            {{ $programme->programme_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('programme_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Mode of Admission *</label>
                                <select name="mode_of_admission" class="form-select @error('mode_of_admission') is-invalid @enderror" required>
                                    @foreach(['UTME','Direct Entry','Transfer','Pre-degree','Post-degree','Others'] as $mode)
                                        <option value="{{ $mode }}" {{ old('mode_of_admission')==$mode?'selected':'' }}>{{ $mode }}</option>
                                    @endforeach
                                </select>
                                @error('mode_of_admission') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-2">
                                <label>Entry Level</label>
                                <input type="number" name="entry_level" class="form-control @error('entry_level') is-invalid @enderror" value="{{ old('entry_level') }}">
                                @error('entry_level') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-2">
                                <label>Current Level</label>
                                <input type="number" name="level" class="form-control @error('level') is-invalid @enderror" value="{{ old('level') }}">
                                @error('level') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Admission Session</label>
                                <select name="admission_session" class="form-select @error('admission_session') is-invalid @enderror">
                                    <option value="">Select Session</option>
                                    @foreach($session as $sess)
                                        <option value="{{ $sess->session_name }}" {{ old('admission_session')==$sess->session_name?'selected':'' }}>
                                            {{ $sess->session_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('admission_session') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Matric No</label>
                                <input type="text" name="matric_no" class="form-control @error('matric_no') is-invalid @enderror" value="{{ old('matric_no') }}">
                                @error('matric_no') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                        </div>

                        {{-- ================== ADDITIONAL INFO ================== --}}
                        <h6 class="fw-bold mt-4 mb-2">Additional Information</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    @foreach(['active','inactive','suspended'] as $status)
                                        <option value="{{ $status }}" {{ old('status','active')==$status?'selected':'' }}>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Save Student</button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
const nigeriaStates = @json($nigeriaStates);

function initLocationSelectors() {
    let nationality = document.getElementById('nationality');
    let stateWrapper = document.getElementById('stateWrapper');
    let lgaWrapper = document.getElementById('lgaWrapper');

    function loadStatesAndLGAs() {
        let stateSelect = document.getElementById('state_of_origin');
        let lgaSelect = document.getElementById('lga_of_origin');

        // Populate states
        stateSelect.innerHTML = '<option value="">Select State</option>';
        Object.keys(nigeriaStates).forEach(state => {
            stateSelect.innerHTML += `<option value="${state}">${state}</option>`;
        });

        // Old state
        let oldState = "{{ old('state_of_origin') }}";
        if (oldState) {
            stateSelect.value = oldState;
            loadLGAs(oldState);
        }

        // On state change, load LGAs
        stateSelect.addEventListener('change', function () {
            loadLGAs(this.value);
        });

        function loadLGAs(state) {
            let lgas = nigeriaStates[state] || [];
            lgaSelect.innerHTML = '<option value="">Select LGA</option>';
            lgas.forEach(lga => {
                lgaSelect.innerHTML += `<option value="${lga}">${lga}</option>`;
            });

            let oldLga = "{{ old('lga_of_origin') }}";
            if (oldLga) {
                lgaSelect.value = oldLga;
            }
        }
    }

    nationality.addEventListener('change', function () {
        if (this.value === 'Nigeria') {
            stateWrapper.innerHTML = `<label>State *</label><select name="state_of_origin" id="state_of_origin" class="form-select" required></select>`;
            lgaWrapper.innerHTML = `<label>LGA *</label><select name="lga_of_origin" id="lga_of_origin" class="form-select" required></select>`;
            loadStatesAndLGAs();
        } else {
            stateWrapper.innerHTML = `<label>State / Province *</label><input type="text" name="state_of_origin" class="form-control" required>`;
            lgaWrapper.innerHTML = `<label>City / Region *</label><input type="text" name="lga_of_origin" class="form-control" required>`;
        }
    });

    if (nationality.value === 'Nigeria') {
        loadStatesAndLGAs();
    }
}

document.addEventListener('DOMContentLoaded', initLocationSelectors);
</script>
