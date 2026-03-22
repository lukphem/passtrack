{{-- Edit Department Modal --}}
<div class="modal fade"
     id="editDepartment{{ $department->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            <form method="POST"
                  action="{{ route('admin.departments.update', $department) }}"
                  class="needs-validation"
                  novalidate>
                @csrf
                @method('PUT')

                {{-- Header --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square"></i> Edit Department
                    </h5>
                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body">

                    @if($errors->any() && session('edit_department_error') == $department->id)
                        <div class="alert alert-danger">
                            <strong>Please fix the errors below.</strong>
                        </div>
                    @endif

                    <div class="row g-3">

                        {{-- DEPARTMENT NAME --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="text-danger">*</span> Department Name
                            </label>
                            <input type="text"
                                   name="dept_name"
                                   value="{{ old('dept_name', $department->dept_name) }}"
                                   class="form-control @error('dept_name') is-invalid @enderror"
                                   placeholder="Enter Department Name"
                                   required>
                            @error('dept_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DEPARTMENT CODE --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="text-danger">*</span> Department Code
                            </label>
                            <input type="text"
                                   name="dept_code"
                                   value="{{ old('dept_code', $department->dept_code) }}"
                                   class="form-control @error('dept_code') is-invalid @enderror"
                                   placeholder="Enter Department Code"
                                   required>
                            @error('dept_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- FACULTY --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="text-danger">*</span> Faculty
                            </label>
                            <select name="faculty_id"
                                    class="form-select @error('faculty_id') is-invalid @enderror"
                                    required>
                                <option value="">Select Faculty</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}"
                                        {{ old('faculty_id', $department->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                        {{ $faculty->faculty_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('faculty_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- HEAD OF DEPARTMENT --}}
                        <div class="col-md-6">
                            <label class="form-label">Head of Department</label>
                            <input type="text"
                                   name="head_of_department"
                                   value="{{ old('head_of_department', $department->head_of_department) }}"
                                   class="form-control @error('head_of_department') is-invalid @enderror"
                                   placeholder="Enter HOD Name (optional)">
                            @error('head_of_department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                                                {{-- ESTABLISHED YEAR --}}
                        <div class="col-md-6">
                            <label class="form-label">Established Year</label>
                            <input type="number"
                                   name="established_year"
                                   value="{{ old('established_year') }}"
                                   class="form-control @error('established_year') is-invalid @enderror"
                                   placeholder="e.g. 1998">
                            @error('established_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="text-danger">*</span> Status
                            </label>
                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required>
                                <option value="active"
                                    {{ old('status', $department->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive"
                                    {{ old('status', $department->status) == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description"
                                      rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Enter Department Description (optional)">{{ old('description', $department->description) }}</textarea>
                            @error('description')
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
                        <i class="bi bi-check-circle"></i> Update Department
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- Auto-open Edit Modal on Validation Error --}}
@if ($errors->any() && session('edit_department_error') == $department->id)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('editDepartment{{ $department->id }}');
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
