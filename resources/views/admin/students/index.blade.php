@extends('admin.dashboard.layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">🎓 Student Management</h4>
        <div>
                <a href="{{ route('admin.students.export', request()->query()) }}" class="btn btn-success me-2">
                    Export Excel
                </a>
            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                Bulk Upload
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                Add Student
            </button>
        </div>
    </div>

    <!-- SEARCH & FILTER -->
    <form method="GET" class="row g-2 mb-3">

    <!-- SEARCH -->
    <div class="col-md-3">
        <input type="text" name="search" class="form-control"
               placeholder="Search name, matric, email..."
               value="{{ request('search') }}">
    </div>

    <!-- PROGRAMME -->
    <div class="col-md-2">
        <select name="programme_id" class="form-select">
            <option value="">Programme</option>
            @foreach($programmes as $programme)
                <option value="{{ $programme->id }}"
                    {{ request('programme_id') == $programme->id ? 'selected' : '' }}>
                    {{ $programme->programme_name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- DEPARTMENT -->
    <div class="col-md-2">
        <select name="department_id" class="form-select">
            <option value="">Department</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}"
                    {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                    {{ $dept->dept_name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- LEVEL -->
    <div class="col-md-1">
        <select name="level" class="form-select">
            <option value="">Level</option>
            @foreach([100,200,300,400,500,600,700] as $lvl)
                <option value="{{ $lvl }}" {{ request('level') == $lvl ? 'selected' : '' }}>
                    {{ $lvl }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- ENTRY MODE -->
    <div class="col-md-2">
        <select name="mode_of_admission" class="form-select">
            <option value="">Entry Mode</option>
            @foreach(['UTME','Direct Entry','Transfer','Pre-degree','Post-degree','Others'] as $mode)
                <option value="{{ $mode }}" {{ request('mode_of_admission') == $mode ? 'selected' : '' }}>
                    {{ $mode }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- STATUS -->
    <div class="col-md-1">
        <select name="status" class="form-select">
            <option value="">Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Grad</option>
            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Susp</option>
            <option value="withdrawn" {{ request('status') == 'withdrawn' ? 'selected' : '' }}>Wd</option>
        </select>
    </div>

    <!-- BUTTON -->
    <div class="col-md-1">
        <button class="btn btn-dark w-100">Filter</button>
    </div>

</form>

    {{-- ================= ALERTS ================= --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    <!-- TABLE -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Matric No</th>
                        <th>Name</th>
                        <th>Programme</th>
                        <th>Entry Mode</th>
                        <th>Level</th>
                        <th>Contacts</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($students as $student)
                    @php
                        $profile = $student->student;
                    @endphp

                    <tr>

                        <!-- Matric -->
                        <td>{{ $profile?->matric_no ?? '---' }}</td>

                        <!-- Name -->
                        <td>
                            {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                        </td>

                        <!-- Programme + Department -->
                        <td>
                            {{ $profile?->programme?->programme_name ?? '---' }}<br>
                            <small class="text-muted">
                                {{ $profile?->programme?->department?->dept_name ?? '---' }}
                            </small>
                        </td>

                        <!-- Admission -->
                        <td>
                            <span class="badge bg-info">
                                {{ strtoupper($profile?->mode_of_admission ?? '-') }}
                            </span>
                        </td>

                        <!-- Level -->
                        <td>
                            {{ $profile?->level ?? '-' }}
                        </td>

                        <!-- Contact -->
                        <td>
                            {{ $profile?->phone ?? '---' }}<br>
                            <small class="text-muted">{{ $student->email }}</small>
                        </td>

                 <!-- Status -->
                        <td>
                            @php
                                $status = $profile?->status;
                            @endphp

                            <span class="badge
                                @if($status == 'active') bg-success
                                @elseif($status == 'graduated') bg-primary
                                @elseif($status == 'suspended') bg-warning text-dark
                                @elseif($status == 'withdrawn') bg-danger
                                @else bg-secondary
                                @endif
                            ">
                                {{ ucfirst($status ?? '-') }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">

                                <!-- VIEW -->
                                <button class="btn btn-sm btn-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewStudentModal{{ $student->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>

                                                <!-- EDIT BUTTON -->
                        <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#editStudentModal{{ $student->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>

                                <!-- DELETE -->
                                <button class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteStudentModal{{ $student->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            No students found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- PAGINATION -->
            <div class="mt-3">
               {{ $students->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>

</div>
@endsection


{{-- ADD MODAL --}}
@include('admin.students.partials.add-modal')

{{-- EDIT MODALS --}}
@foreach ($students as $student)
    @include('admin.students.partials.edit-modal', ['student' => $student])
@endforeach

{{-- DELETE MODALS --}}
@foreach ($students as $student)
    @include('admin.students.partials.delete-modal', ['student' => $student])
@endforeach

{{-- VIEW MODALS (IMPORTANT - you used it) --}}
@foreach ($students as $student)
    @include('admin.students.partials.view-modal', ['student' => $student])
@endforeach

{{-- BULK UPLOAD (only if exists) --}}
@include('admin.students.partials.bulk-upload-modal')
