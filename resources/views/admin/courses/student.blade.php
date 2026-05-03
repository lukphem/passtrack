@extends('admin.layouts.app')

@section('content')

<div class="container py-4">

    {{-- ================= STUDENT HEADER ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5 class="fw-bold mb-1">
                {{ $student->user->first_name }} {{ $student->user->last_name }}
            </h5>

            <small class="text-muted">
                Matric No: {{ $student->matric_no }} |
                Programme: {{ $student->programme->programme_name }} |
                Level: {{ $student->level }}
            </small>

            <div class="mt-2">
                <span class="badge bg-dark">
                    {{ $session->session_name }}
                </span>
                <span class="badge bg-secondary">
                    {{ $semester->semester_name }}
                </span>
            </div>

        </div>
    </div>

    {{-- ================= ACTION BUTTONS ================= --}}
    <div class="d-flex justify-content-between mb-3">

        <form method="POST" action="{{ route('admin.student.courses.register', $student->id) }}">
            @csrf

            <div class="input-group">

                <select name="course_ids[]" class="form-select" multiple required style="min-width:300px;">
                    @foreach(\App\Models\Course::where('status',1)->get() as $course)
                        <option value="{{ $course->id }}">
                            {{ $course->course_code }} - {{ $course->course_title }}
                        </option>
                    @endforeach
                </select>

                <button class="btn btn-success">
                    Add Courses
                </button>

            </div>
        </form>

        <form method="POST" action="{{ route('admin.student.courses.reset', $student->id) }}">
            @csrf
            @method('DELETE')

            <button class="btn btn-danger"
                    onclick="return confirm('Reset all student courses?')">
                Reset All
            </button>
        </form>

    </div>

    {{-- ================= REGISTERED COURSES ================= --}}
    <div class="card shadow-sm">

        <div class="card-header bg-dark text-white">
            Registered Courses
        </div>

        <div class="card-body p-0">

            <table class="table table-bordered mb-0">

                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Units</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($courses as $i => $course)

                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $course->course_code }}</td>
                            <td>{{ $course->course_title }}</td>
                            <td>
                                <span class="badge bg-{{ $course->course_type == 'Core' ? 'primary' : 'warning' }}">
                                    {{ $course->course_type }}
                                </span>
                            </td>
                            <td>{{ $course->credit_unit }}</td>
                            <td>

                                <form method="POST"
                                      action="{{ route('admin.student.courses.drop', [$student->id, $course->id]) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Remove this course?')">
                                        Drop
                                    </button>

                                </form>

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No courses registered
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
