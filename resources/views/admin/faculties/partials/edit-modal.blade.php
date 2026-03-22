{{-- Edit Faculty Modal --}}
<div class="modal fade"
     id="editFaculty{{ $faculty->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            <form method="POST"
                  action="{{ route('admin.faculties.update', $faculty) }}"
                  class="needs-validation"
                  novalidate>
                @csrf
                @method('PUT')

                {{-- Header --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square"></i> Edit Faculty
                    </h5>
                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body">

                    @if($errors->any() && session('edit_faculty_error') == $faculty->id)
                        <div class="alert alert-danger">
                            <strong>Please fix the errors below.</strong>
                        </div>
                    @endif

                    <div class="row g-3">

                        {{-- FACULTY NAME --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="text-danger">*</span> Faculty Name
                            </label>
                            <input type="text"
                                   name="faculty_name"
                                   value="{{ old('faculty_name', $faculty->faculty_name) }}"
                                   class="form-control @error('faculty_name') is-invalid @enderror"
                                   required>
                            @error('faculty_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- FACULTY CODE --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="text-danger">*</span> Faculty Code
                            </label>
                            <input type="text"
                                   name="faculty_code"
                                   value="{{ old('faculty_code', $faculty->faculty_code) }}"
                                   class="form-control @error('faculty_code') is-invalid @enderror"
                                   required>
                            @error('faculty_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DEAN --}}
                        <div class="col-md-6">
                            <label class="form-label">Dean</label>
                            <input type="text"
                                   name="dean"
                                   value="{{ old('dean', $faculty->dean) }}"
                                   class="form-control @error('dean') is-invalid @enderror">
                            @error('dean')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ESTABLISHED YEAR --}}
                        <div class="col-md-6">
                            <label class="form-label">Established Year</label>
                            <input type="number"
                                   name="established_year"
                                   value="{{ old('established_year', $faculty->established_year) }}"
                                   class="form-control @error('established_year') is-invalid @enderror">
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
                                    {{ old('status', $faculty->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive"
                                    {{ old('status', $faculty->status) == 'inactive' ? 'selected' : '' }}>
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
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $faculty->description) }}</textarea>
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
                        <i class="bi bi-check-circle"></i> Update Faculty
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- Auto-open Edit Modal on Validation Error --}}
@if ($errors->any() && session('edit_faculty_error') == $faculty->id)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('editFaculty{{ $faculty->id }}');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
});
</script>
@endif
