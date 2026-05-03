@extends('student.layouts.app')

@section('title', 'Course Registration History')

@section('content')

<div class="container py-4">

    {{-- HEADER --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body text-center">
            <h4 class="fw-bold mb-1">Course Registration History</h4>
            <small class="text-muted">
                View all registered courses per session and semester
            </small>

            {{-- 👉 Go to Current Registration --}}
            <div class="mt-3">
                <a href="{{ route('student.courses.available') }}" class="btn btn-primary btn-sm">
                    Register Courses (Current Semester)
                </a>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Session</th>
                        <th>Semester</th>
                        <th>Total Courses</th>
                        <th>Total Units</th>
                        <th>Registered At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($registrations as $row)
                <tr>
                    {{-- INDEX --}}
                    <td>{{ $loop->iteration }}</td>

                    {{-- SESSION --}}
                    <td>{{ $row->session_name }}</td>

                    {{-- SEMESTER --}}
                    <td>
                        <span class="badge bg-info text-dark">
                            {{ $row->semester_name }}
                        </span>
                    </td>

                    {{-- TOTAL COURSES --}}
                    <td>
                        <span class="badge bg-secondary">
                            {{ $row->total_courses }}
                        </span>
                    </td>

                    {{-- TOTAL UNITS --}}
                    <td>
                        <span class="badge bg-primary">
                            {{ $row->total_units }}
                        </span>
                    </td>

                    {{-- REGISTERED DATE --}}
                    <td>
                        @if($row->registered_at)
                            {{ \Carbon\Carbon::parse($row->registered_at)->format('d M Y h:i A') }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    {{-- ACTIONS --}}
                    <td class="text-end">

                        
                        {{-- PRINT --}}
                        <a href="{{ route('student.courses.print', [
                                'session_id' => $row->session_id,
                                'semester_id' => $row->semester_id
                            ]) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-primary">
                            Print
                        </a>

                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        No course registrations found.
                    </td>
                </tr>
                @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection
