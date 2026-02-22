<div class="modal fade" id="editDepartment{{ $department->id }}" tabindex="-1" aria-labelledby="editDepartmentLabel{{ $department->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="editDepartmentLabel{{ $department->id }}">
                    <i class="bi bi-pencil"></i> Edit Department
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>


            <form method="POST" action="{{ route('admin.departments.update', $department) }}">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label">Department Name</label>
                            <input type="text"
                                   name="dept_name"
                                   class="form-control"
                                   value="{{ old('dept_name', $department->dept_name) }}"
                                   required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Department Code</label>
                            <input type="text"
                                   name="dept_code"
                                   class="form-control"
                                   value="{{ old('dept_code', $department->dept_code) }}"
                                   required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Faculty</label>
                                <select name="faculty_id" class="form-select" required>
                                    <option value="">Select Faculty</option>
                                    @foreach($faculties as $faculty)
                                        <option value="{{ $faculty->id }}"
                                            {{ old('faculty_id', $department->faculty_id ?? '') == $faculty->id ? 'selected' : '' }}>
                                            {{ $faculty->faculty_name }}
                                        </option>
                                    @endforeach
                                </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Head of Department</label>
                            <input type="text"
                                   name="head_of_department"
                                   class="form-control"
                                   value="{{ old('head_of_department', $department->head_of_department) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description"
                                      rows="3"
                                      class="form-control">{{ old('description', $department->description) }}</textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Department</button>
                </div>
            </form>

        </div>
    </div>
</div>
