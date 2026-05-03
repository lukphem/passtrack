@extends('admin.dashboard.layouts.admin')

@section('content')

<div class="container py-4">

    <h4 class="mb-3 fw-bold">Student Course Registration Management</h4>

    {{-- ================= SEARCH & FILTER ================= --}}
    <form method="GET" action="{{ route('admin.courses-registration.index') }}" class="row g-2 mb-3">

        {{-- SEARCH --}}
        <div class="col-md-3">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Search name, matric, email..."
                   value="{{ request('search') }}">
        </div>

        {{-- PROGRAMME --}}
        <div class="col-md-2">
            <select name="programme_id" class="form-select">
                <option value="">Programme</option>
                @foreach($programmes ?? [] as $programme)
                    <option value="{{ $programme->id }}"
                        {{ request('programme_id') == $programme->id ? 'selected' : '' }}>
                        {{ $programme->programme_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- DEPARTMENT --}}
        <div class="col-md-2">
            <select name="department_id" class="form-select">
                <option value="">Department</option>
                @foreach($departments ?? [] as $dept)
                    <option value="{{ $dept->id }}"
                        {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->dept_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-dark w-100">Search</button>
        </div>

    </form>

    {{-- ================= RESULTS ================= --}}
    <div class="card shadow-sm">

        <div class="card-header bg-dark text-white">
            Students
        </div>

        <div class="card-body p-0">

            <table class="table table-hover mb-0">

                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Matric No</th>
                        <th>Programme</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($students as $student)

                    <tr>
                        <td>
                            {{ $student->user->first_name }}
                            {{ $student->user->last_name }}
                        </td>

                        <td>{{ $student->matric_no }}</td>

                        <td>{{ $student->programme->programme_name ?? 'N/A' }}</td>

                        <td>
                            <button class="btn btn-sm btn-success"
                                    data-bs-toggle="modal"
                                    data-bs-target="#courseContextModal{{ $student->id }}">
                                Manage Courses
                            </button>
                        </td>
                    </tr>

                    {{-- ================= CONTEXT MODAL ================= --}}
                    <div class="modal fade"
                         id="courseContextModal{{ $student->id }}"
                         tabindex="-1"
                         aria-hidden="true">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content">

                                <form method="GET"
                                      action="{{ route('admin.courses-registration.manage', $student->id) }}">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Course Management Context</h5>
                                        <button type="button" class="btn-close"
                                                data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="alert alert-info small mb-3">
                                            Select the <strong>academic session</strong> and <strong>semester</strong>
                                            you want to view or modify for this student.
                                        </div>

                                        {{-- SESSION --}}
                                        <div class="mb-3">
                                            <label class="form-label">Academic Session</label>
                                            <select name="session_id" class="form-select" required>
                                                <option value="">Select Session</option>
                                                @foreach($sessions ?? [] as $session)
                                                    <option value="{{ $session->id }}"
                                                        {{ $session->is_active ? 'selected' : '' }}>
                                                        {{ $session->session_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- SEMESTER --}}
                                        <div class="mb-3">
                                            <label class="form-label">Semester</label>
                                            <select name="semester_id" class="form-select" required>
                                                <option value="">Select Semester</option>
                                                @foreach($semesters ?? [] as $semester)
                                                    <option value="{{ $semester->id }}"
                                                        {{ $semester->is_active ? 'selected' : '' }}>
                                                        {{ $semester->semester_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal">
                                            Cancel
                                        </button>

                                        <button class="btn btn-primary">
                                            Proceed
                                        </button>
                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                @empty

                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            No students found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection
