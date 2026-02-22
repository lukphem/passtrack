{{-- Add Programme Modal --}}
<div class="modal fade" id="addProgrammeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            <form method="POST" action="{{ route('admin.programmes.store') }}" class="needs-validation" novalidate>
                @csrf

                {{-- Header --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle"></i> Add Programme
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body">
                    @if($errors->any() && session('add_programme_error'))
                        <div class="alert alert-danger">
                            <strong>Please fix the errors below.</strong>
                        </div>
                    @endif

                    <div class="row g-3">
                        {{-- Programme Name --}}
                        <div class="col-md-6">
                            <label class="form-label"><span class="text-danger">*</span> Programme Name</label>
                            <input type="text" name="programme_name" value="{{ old('programme_name') }}"
                                   class="form-control @error('programme_name') is-invalid @enderror"
                                   placeholder="Enter Programme Name" required>
                            @error('programme_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Programme Code --}}
                        <div class="col-md-6">
                            <label class="form-label"><span class="text-danger">*</span> Programme Code</label>
                            <input type="text" name="programme_code" value="{{ old('programme_code') }}"
                                   class="form-control @error('programme_code') is-invalid @enderror"
                                   placeholder="Enter Programme Code e.g. BSC-CS" required>
                            @error('programme_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Duration --}}
                        <div class="col-md-6">
                            <label class="form-label"><span class="text-danger">*</span> Duration (Years)</label>
                            <input type="number" name="programme_duration" value="{{ old('programme_duration') }}"
                                   class="form-control @error('programme_duration') is-invalid @enderror"
                                   placeholder="Enter Duration" min="1" max="10" required>
                            @error('programme_duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Level Type --}}
                        <div class="col-md-6">
                            <label class="form-label">Level Type</label>
                            <select name="programme_level_type" class="form-select @error('programme_level_type') is-invalid @enderror">
                                <option value="">Select Level</option>
                                <option value="Undergraduate" {{ old('programme_level_type')=='Undergraduate'?'selected':'' }}>Undergraduate</option>
                                <option value="Postgraduate" {{ old('programme_level_type')=='Postgraduate'?'selected':'' }}>Postgraduate</option>
                            </select>
                            @error('programme_level_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Start Date --}}
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="programme_start_date" value="{{ old('programme_start_date') }}"
                                   class="form-control @error('programme_start_date') is-invalid @enderror">
                            @error('programme_start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Department --}}
                        <div class="col-md-6">
                            <label class="form-label"><span class="text-danger">*</span> Department</label>
                            <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id')==$department->id?'selected':'' }}>
                                        {{ $department->dept_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="programme_description" rows="3"
                                      class="form-control @error('programme_description') is-invalid @enderror"
                                      placeholder="Enter Programme Description">{{ old('programme_description') }}</textarea>
                            @error('programme_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Accreditation Status --}}
                        <div class="col-md-6">
                            <label class="form-label">Accreditation Status</label>
                            <select name="accreditation_status" class="form-select @error('accreditation_status') is-invalid @enderror">
                                <option value="">Select Status</option>
                                <option value="Full" {{ old('accreditation_status')=='Full'?'selected':'' }}>Full</option>
                                <option value="Interim" {{ old('accreditation_status')=='Interim'?'selected':'' }}>Interim</option>
                                <option value="None" {{ old('accreditation_status')=='None'?'selected':'' }}>None</option>
                            </select>
                            @error('accreditation_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Accreditation Year --}}
                        <div class="col-md-6">
                            <label class="form-label">Accreditation Year</label>
                            <input type="number" name="accreditation_year" value="{{ old('accreditation_year') }}"
                                   class="form-control @error('accreditation_year') is-invalid @enderror"
                                   placeholder="Enter Year" min="2000" max="{{ date('Y') }}">
                            @error('accreditation_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Industrial Training --}}
                        <div class="col-md-6 form-check form-switch mt-2">
                            <input type="checkbox" class="form-check-input" id="industrial_training_required_add"
                                   name="industrial_training_required" {{ old('industrial_training_required') ? 'checked' : '' }}>
                            <label class="form-check-label" for="industrial_training_required_add">Industrial Training Required</label>
                        </div>

                        {{-- Industrial Training Level --}}
                        <div class="col-md-6">
                            <label class="form-label">Industrial Training Level</label>
                            <input type="number" name="industrial_training_level"
                                   id="industrial_training_level_add"
                                   value="{{ old('industrial_training_level') }}"
                                   class="form-control @error('industrial_training_level') is-invalid @enderror"
                                   placeholder="Enter Level" min="1" max="10"
                                   {{ old('industrial_training_required') ? '' : 'disabled' }}>
                            @error('industrial_training_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="form-label"><span class="text-danger">*</span> Status</label>
                            <select name="programme_status" class="form-select @error('programme_status') is-invalid @enderror" required>
                                <option value="1" {{ old('programme_status')=='1'?'selected':'' }}>Active</option>
                                <option value="0" {{ old('programme_status')=='0'?'selected':'' }}>Inactive</option>
                            </select>
                            @error('programme_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Programme</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JS for Add Modal --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxAdd = document.getElementById('industrial_training_required_add');
    const levelAdd = document.getElementById('industrial_training_level_add');

    if(checkboxAdd && levelAdd){
        checkboxAdd.addEventListener('change', function(){
            levelAdd.disabled = !this.checked;
            if(!this.checked) levelAdd.value = '';
        });
    }

    @if($errors->any() && session('add_programme_error'))
        const addModalEl = document.getElementById('addProgrammeModal');
        const addModal = new bootstrap.Modal(addModalEl);
        addModal.show();
    @endif
});
</script>
