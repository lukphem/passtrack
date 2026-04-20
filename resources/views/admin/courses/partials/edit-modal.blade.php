{{-- EDIT COURSE MODAL --}}
<div class="modal fade" id="editCourse{{ $course->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <form method="POST" action="{{ route('admin.courses.update', $course->id) }}">
                @csrf
                @method('PUT')

                {{-- HEADER --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square"></i>
                        Edit Course
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- BODY --}}

                <div class="modal-body pt-2">

                    @if($errors->any() && session('edit_course_error') == $course->id)
                        <div class="alert alert-danger">
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
                            <input type="text" name="course_code"
                                   class="form-control"
                                   value="{{ old('course_code', $course->course_code) }}" required>
                        </div>

                        {{-- COURSE TITLE --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Course Title</label>
                            <input type="text" name="course_title"
                                   class="form-control"
                                   value="{{ old('course_title', $course->course_title) }}" required>
                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="course_description"
                                      class="form-control"
                                      rows="3">{{ old('course_description', $course->course_description) }}</textarea>
                        </div>

                        {{-- LEVEL --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Level</label>
                            <select name="level" class="form-select" required>
                                @for($i=100; $i<=700; $i+=100)
                                    <option value="{{ $i }}"
                                        {{ old('level', $course->level) == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        {{-- SEMESTER --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="First"
                                    {{ old('semester', $course->semester) == 'First' ? 'selected' : '' }}>First</option>
                                <option value="Second"
                                    {{ old('semester', $course->semester) == 'Second' ? 'selected' : '' }}>Second</option>
                                <option value="Third"
                                    {{ old('semester', $course->semester) == 'Third' ? 'selected' : '' }}>Third</option>

                            </select>
                        </div>

                        {{-- CREDIT --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Credit Unit</label>
                            <input type="number" name="credit_unit"
                                   class="form-control"
                                   value="{{ old('credit_unit', $course->credit_unit) }}" required>
                        </div>

                        {{-- TYPE --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Course Type</label>
                            <select name="course_type" class="form-select" required>
                                @foreach(['Core','Elective','General'] as $type)
                                    <option value="{{ $type }}"
                                        {{ old('course_type', $course->course_type) == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ old('status', $course->status) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $course->status) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        {{-- DEPARTMENT --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Department</label>
                            <select name="department_id" class="form-select" required>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id', $course->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->dept_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- LECTURER --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Lecturer</label>
                            <select name="lecturer_id" class="form-select" required>
                                @foreach($lecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}"
                                        {{ old('lecturer_id', $course->lecturer_id) == $lecturer->id ? 'selected' : '' }}>
                                        {{ $lecturer->first_name }} {{ $lecturer->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PROGRAMMES (PIVOT) --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Programmes</label>
                            <select name="programmes[]" class="form-select" multiple required>

                                @foreach($programmes as $programme)
                                    <option value="{{ $programme->id }}"
                                        {{ in_array($programme->id,
                                            old('programmes',
                                                $course->programmes->pluck('id')->toArray()
                                            )
                                        ) ? 'selected' : '' }}>
                                        {{ $programme->programme_name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Update Course
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    @if(session('add_course_error'))
        new bootstrap.Modal(document.getElementById('addCourseModal')).show();
    @endif

    @if(session('edit_course_error') && session('edit_course_id'))
        new bootstrap.Modal(
            document.getElementById('editCourse{{ session('edit_course_id') }}')
        ).show();
    @endif

});
</script>
