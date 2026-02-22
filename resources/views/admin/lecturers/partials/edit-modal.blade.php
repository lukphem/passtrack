{{-- Edit Lecturer Modal --}}
<div class="modal fade"
     id="editLecturerModal{{ $lecturer->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            <form method="POST" action="{{ route('admin.lecturers.update', $lecturer->id) }}" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                {{-- Header --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square"></i> Edit Lecturer
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body">
                    @if($errors->any() && session('edit_lecturer_id') == $lecturer->id)
                        <div class="alert alert-danger">
                            <strong>Please fix the errors below.</strong>
                        </div>
                    @endif

                    <div class="row g-3">
                        {{-- TITLE --}}
                        <div class="col-md-4">
                            <label class="form-label"> <span class="text-danger">*</span> Title </label>
                            <select name="title" class="form-select @error('title') is-invalid @enderror" required>
                                <option value="">Select Title</option>
                                @foreach(['Dr.','Prof.','Mr.','Mrs.','Miss.'] as $title)
                                    <option value="{{ $title }}" {{ old('title', $lecturer->title)==$title?'selected':'' }}>{{ $title }}</option>
                                @endforeach
                            </select>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- STAFF ID --}}
                        <div class="col-md-4">
                            <label class="form-label"> <span class="text-danger">*</span> Staff ID </label>
                            <input name="staff_id" value="{{ old('staff_id', $lecturer->staff_id) }}" placeholder="Enter Staff ID e.g. LEC12345"
                                   class="form-control @error('staff_id') is-invalid @enderror" required>
                            @error('staff_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- GENDER --}}
                        <div class="col-md-4">
                            <label class="form-label"> <span class="text-danger">*</span> Gender </label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $lecturer->gender)=='male'?'selected':'' }}>Male</option>
                                <option value="female" {{ old('gender', $lecturer->gender)=='female'?'selected':'' }}>Female</option>
                            </select>
                            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- FIRST NAME --}}
                        <div class="col-md-4">
                            <label class="form-label"> <span class="text-danger">*</span> First Name </label>
                            <input name="first_name" value="{{ old('first_name', $lecturer->first_name) }}" placeholder="Enter First Name"
                                   class="form-control @error('first_name') is-invalid @enderror" required>
                            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- MIDDLE NAME --}}
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input name="middle_name" value="{{ old('middle_name', $lecturer->middle_name) }}" placeholder="Enter Middle Name (optional)"
                                   class="form-control @error('middle_name') is-invalid @enderror">
                            @error('middle_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- LAST NAME --}}
                        <div class="col-md-4">
                            <label class="form-label"> <span class="text-danger">*</span> Last Name </label>
                            <input name="last_name" value="{{ old('last_name', $lecturer->last_name) }}" placeholder="Enter Last Name"
                                   class="form-control @error('last_name') is-invalid @enderror" required>
                            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- EMAIL --}}
                        <div class="col-md-6">
                            <label class="form-label"> <span class="text-danger">*</span> Email </label>
                            <input type="email" name="email" value="{{ old('email', $lecturer->user->email) }}" placeholder="Enter Email"
                                   class="form-control @error('email') is-invalid @enderror" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- PHONE --}}
                        <div class="col-md-6">
                            <label class="form-label"> <span class="text-danger">*</span> Phone </label>
                            <input name="phone" value="{{ old('phone', $lecturer->phone) }}" placeholder="Enter Phone Number"
                                   class="form-control @error('phone') is-invalid @enderror" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- FACULTY --}}
                        <div class="col-md-6">
                            <label class="form-label"> <span class="text-danger">*</span> Faculty </label>
                            <select name="faculty_id" class="form-select @error('faculty_id') is-invalid @enderror" required>
                                <option value="">Select Faculty</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}" {{ old('faculty_id', $lecturer->faculty_id)==$faculty->id?'selected':'' }}>
                                        {{ $faculty->faculty_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('faculty_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- DEPARTMENT --}}
                        <div class="col-md-6">
                            <label class="form-label"> <span class="text-danger">*</span> Department </label>
                            <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $lecturer->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->dept_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- SPECIALIZATION --}}
                        <div class="col-md-6">
                            <label class="form-label"> <span class="text-danger">*</span> Specialization </label>
                            <input name="specialization" value="{{ old('specialization', $lecturer->specialization) }}" placeholder="Enter Specialization"
                                   class="form-control @error('specialization') is-invalid @enderror" required>
                            @error('specialization') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- RANK --}}
                        <div class="col-md-6">
                            <label class="form-label"> <span class="text-danger">*</span> Rank </label>
                            <input name="rank" value="{{ old('rank', $lecturer->rank) }}" placeholder="Enter Rank"
                                   class="form-control @error('rank') is-invalid @enderror" required>
                            @error('rank') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- EMPLOYMENT TYPE --}}
                        <div class="col-md-6">
                            <label class="form-label"> <span class="text-danger">*</span> Employment Type </label>
                            <select name="employment_type" class="form-select @error('employment_type') is-invalid @enderror" required>
                                <option value="">Select Type</option>
                                <option value="full_time" {{ old('employment_type', $lecturer->employment_type)=='full_time'?'selected':'' }}>Full Time</option>
                                <option value="part_time" {{ old('employment_type', $lecturer->employment_type)=='part_time'?'selected':'' }}>Part Time</option>
                                <option value="contract" {{ old('employment_type', $lecturer->employment_type)=='contract'?'selected':'' }}>Contract</option>
                                <option value="visiting" {{ old('employment_type', $lecturer->employment_type)=='visiting'?'selected':'' }}>Visiting</option>
                            </select>
                            @error('employment_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6">
                            <label class="form-label"><span class="text-danger">*</span> Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $lecturer->status)=='active'?'selected':'' }}>Active</option>
                                <option value="inactive" {{ old('status', $lecturer->status)=='inactive'?'selected':'' }}>Inactive</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <input type="hidden" name="staff_category" value="academic">
                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Lecturer</button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- Auto-open Edit Lecturer Modal on validation errors --}}
@if ($errors->any() && session('edit_lecturer_id') == $lecturer->id)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('editLecturerModal{{ $lecturer->id }}');
    if(modalEl) {
        const modal = new bootstrap.Modal(modalEl, { backdrop: true, keyboard: true });
        modal.show();
    }
});
</script>
@endif
