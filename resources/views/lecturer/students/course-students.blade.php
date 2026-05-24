@extends('lecturer.layouts.app')

@section('title', 'Course Students')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="mb-0">
            Course - {{ $course->course_code ?? 'N/A' }} {{ $course->course_title ?? 'N/A' }}
        </h4>

        <a href="{{ route('lecturer.courses.index') }}" class="btn btn-secondary btn-sm">
            Back
        </a>
    </div>

    {{-- SESSION / SEMESTER --}}
    <div class="alert alert-info py-2 mb-3 text-center">
        <strong>Session:</strong> {{ $sessionName }} |
        <strong>Semester:</strong> {{ $semesterName }}
    </div>

    {{-- FILTERS --}}
    <form method="GET" class="row g-2 mb-3">

        <div class="col-md-3">
            <input type="text" name="search" class="form-control"
                   placeholder="Search name or matric no"
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <select name="programme_id" class="form-control">
                <option value="">All Programmes</option>
                @foreach($programmes as $programme)
                    <option value="{{ $programme->id }}"
                        {{ request('programme_id') == $programme->id ? 'selected' : '' }}>
                        {{ $programme->programme_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <input type="text" name="level" class="form-control"
                   placeholder="Level"
                   value="{{ request('level') }}">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-50">
                Reset
            </a>

            <a href="{{ route('lecturer.courses.students.export', $course->id) }}"
               class="btn btn-success w-50">
                Export
            </a>
        </div>

    </form>

    {{-- TABLE --}}
    <div class="card">
        <div class="card-body p-0">

            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Matric No</th>
                        <th>Name</th>
                        <th>Programme</th>
                        <th>Level</th>
                        <th>Attendance</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($students as $index => $student)
                        <tr>
                            <td>{{ $students->firstItem() + $index }}</td>
                            <td>{{ $student->matric_no }}</td>
                            <td>{{ $student->user->first_name ?? '' }} {{ $student->user->last_name ?? '' }}</td>
                            <td>{{ $student->programme->programme_name ?? 'N/A' }}</td>
                            <td>{{ $student->level ?? 'N/A' }}</td>
                            <td>{{ $student->attendance ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-3">
                                No students found for this course
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $students->links() }}
    </div>

</div>
@endsection
