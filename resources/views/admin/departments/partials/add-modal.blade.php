<!-- Add Department Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-diagram-3-fill"></i> Add Department
                </h5>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>


            <form method="POST" action="{{ route('admin.departments.store') }}">
                @csrf

                <div class="modal-body">

                    {{-- Department Name --}}
                    <div class="mb-3">
                        <label class="form-label">Department Name</label>
                        <input type="text"
                               name="dept_name"
                               class="form-control"
                               required>
                    </div>

                    {{-- Department Code --}}
                    <div class="mb-3">
                        <label class="form-label">Department Code</label>
                        <input type="text"
                               name="dept_code"
                               class="form-control"
                               required>
                    </div>

                    {{-- Faculty --}}
                    <div class="mb-3">
                        <label class="form-label">Faculty</label>
                        <select name="faculty_id" class="form-select" required>
                            <option value="">Select Faculty</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}">
                                    {{ $faculty->faculty_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Head of Department --}}
                    <div class="mb-3">
                        <label class="form-label">Head of Department</label>
                        <input type="text"
                               name="head_of_department"
                               class="form-control">
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description"
                                  rows="3"
                                  class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Save Department
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
