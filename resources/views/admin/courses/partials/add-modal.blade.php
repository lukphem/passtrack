{{-- ADD COURSE MODAL --}}
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <form action="{{ route('admin.courses.store') }}" method="POST">
                @csrf

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>
                        Add New Course
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body pt-2">

                    {{-- VALIDATION ERRORS --}}
                    @if($errors->any() && session('add_course_error'))
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">

                        {{-- COURSE CODE --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Course Code</label>
                            <input type="text"
                                   name="course_code"
                                   class="form-control"
                                   value="{{ old('course_code') }}"
                                   placeholder="e.g CSC101"
                                   required>
                        </div>

                        {{-- COURSE TITLE --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Course Title</label>
                            <input type="text"
                                   name="course_title"
                                   class="form-control"
                                   value="{{ old('course_title') }}"
                                   placeholder="e.g Introduction to Programming"
                                   required>
                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Course Description</label>
                            <textarea name="course_description"
                                      rows="3"
                                      class="form-control"
                                      placeholder="Brief description of the course...">{{ old('course_description') }}</textarea>
                        </div>

                        {{-- LEVEL --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Level</label>
                            <select name="level" class="form-select" required>
                                <option value="">Select Level</option>
                                <option value="100" {{ old('level') == 100 ? 'selected' : '' }}>100</option>
                                <option value="200" {{ old('level') == 200 ? 'selected' : '' }}>200</option>
                                <option value="300" {{ old('level') == 300 ? 'selected' : '' }}>300</option>
                                <option value="400" {{ old('level') == 400 ? 'selected' : '' }}>400</option>
                                <option value="500" {{ old('level') == 500 ? 'selected' : '' }}>500</option>
                            </select>
                        </div>

                        {{-- SEMESTER --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="">Select Semester</option>
                                <option value="First" {{ old('semester') == 'First' ? 'selected' : '' }}>First</option>
                                <option value="Second" {{ old('semester') == 'Second' ? 'selected' : '' }}>Second</option>
                            </select>
                        </div>

                        {{-- CREDIT UNIT --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Credit Unit</label>
                            <input type="number"
                                   name="credit_unit"
                                   class="form-control"
                                   min="1"
                                   max="10"
                                   value="{{ old('credit_unit') }}"
                                   required>
                        </div>

                        {{-- COURSE TYPE --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Course Type</label>
                            <select name="course_type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="Core" {{ old('course_type') == 'Core' ? 'selected' : '' }}>Core</option>
                                <option value="Elective" {{ old('course_type') == 'Elective' ? 'selected' : '' }}>Elective</option>
                                <option value="General" {{ old('course_type') == 'General' ? 'selected' : '' }}>General</option>
                            </select>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        {{-- PROGRAMMES --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Assign to Programmes
                            </label>

                            <select name="programmes[]" class="form-select" multiple required>
                                @foreach($programmes as $programme)
                                    <option value="{{ $programme->id }}"
                                        {{ collect(old('programmes'))->contains($programme->id) ? 'selected' : '' }}>
                                        {{ $programme->programme_name }} ({{ $programme->programme_code }})
                                    </option>
                                @endforeach
                            </select>

                            <small class="text-muted">
                                Hold Ctrl (Windows) or Command (Mac) to select multiple programmes.
                            </small>
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        Save Course
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
