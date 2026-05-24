@extends('lecturer.layouts.app')

@section('content')

<div class="container py-4">

    {{-- HEADER --}}
    <div class="mb-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">

            <div>
                <h3 class="fw-bold mb-1">My Courses</h3>
                <p class="text-muted mb-0">
                    Manage courses, students, materials, assignments and quizzes
                </p>
            </div>

            <div class="mt-3 mt-md-0 text-md-end">

    <div class="mb-1">
        <span class="alert alert-info py-2 mb-3">
            <strong>Session:</strong> {{ $session->session_name ?? 'N/A' }} |
           <strong>Semester:</strong> {{ $semester->semester_name ?? 'N/A' }}
        </span>
    </div>

</div>

        </div>

        <hr>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-3">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr class="small text-uppercase text-muted">
                            <th>#</th>
                            <th>Code</th>
                            <th>Course Title</th>
                            <th>Programmes</th>
                            <th>Level</th>
                            <th>Unit</th>
                            <th>Students</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($courses as $index => $course)

                            <tr>

                                <td class="text-muted">
                                    {{ $index + 1 }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $course->course_code }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $course->course_title }}
                                </td>

                                {{-- PROGRAMMES --}}
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @forelse($course->programmes as $programme)
                                            <span class="badge bg-info text-dark">
                                                {{ $programme->programme_name ?? 'N/A' }}
                                            </span>
                                        @empty
                                            <span class="text-muted small">N/A</span>
                                        @endforelse
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $course->level }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ $course->credit_unit }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-success">
                                        {{ $course->students_count }}
                                    </span>
                                </td>

                                <td class="text-end">

                                    <button class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#courseModal{{ $course->id }}">
                                        View
                                    </button>

                                    <a href="{{ route('lecturer.courses.students', $course->id) }}"
                                       class="btn btn-sm btn-outline-success">
                                        Students
                                    </a>

                                </td>

                            </tr>

                            {{--  MODAL INCLUDED INSIDE LOOP --}}
                            @include('lecturer.courses.partials.view-modal', ['course' => $course])

                        @empty

                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    No courses assigned yet.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
