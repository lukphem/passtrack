@extends('lecturer.layouts.app')

@section('title', 'Course Materials')

@section('content')

<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <small class="text-muted">Manage course materials</small>
        </div>

        <button class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#materialModal">
            + Add Material
        </button>
    </div>

    {{-- CURRENT SESSION BADGE --}}


        <div class="mb-3">
        <span class="alert alert-info py-2 mb-3">
            <strong>Session:</strong>  {{ $sessions->firstWhere('id', $selectedSessionId)?->session_name ?? 'All Sessions' }} |

           <strong>Semester:</strong> {{ $semester->semester_name ?? 'N/A' }}
        </span>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="row g-2 mb-3">

        {{-- SESSION --}}
        <div class="col-md-2">
            <select name="academic_session_id" class="form-select">
                <option value="">All Sessions</option>

                @foreach($sessions as $session)
                    <option value="{{ $session->id }}"
                        {{ request('academic_session_id', $selectedSessionId) == $session->id ? 'selected' : '' }}>

                        {{ $session->session_name }}

                        @if($session->is_active)
                            (Current)
                        @endif

                    </option>
                @endforeach
            </select>
        </div>

        {{-- COURSE --}}
        <div class="col-md-3">
            <select name="course_id" class="form-select">
                <option value="">All Courses</option>

                @foreach($courses as $c)
                    <option value="{{ $c->id }}"
                        {{ request('course_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->course_code }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- SEARCH --}}
        <div class="col-md-3">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Search title..."
                   value="{{ request('search') }}">
        </div>

        {{-- TYPE --}}
        <div class="col-md-2">
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="lecture_note" {{ request('type') == 'lecture_note' ? 'selected' : '' }}>Lecture Note</option>
                <option value="assignment" {{ request('type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                <option value="slide" {{ request('type') == 'slide' ? 'selected' : '' }}>Slide</option>
                <option value="reference" {{ request('type') == 'reference' ? 'selected' : '' }}>Reference</option>
                <option value="lab_manual" {{ request('type') == 'lab_manual' ? 'selected' : '' }}>Lab Manual</option>
            </select>
        </div>

        {{-- WEEK --}}
        <div class="col-md-1">
            <input type="number"
                   name="week"
                   class="form-control"
                   placeholder="Week"
                   value="{{ request('week') }}">
        </div>

        <div class="col-md-1 mt-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>

    </form>


    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="card">
        <div class="card-body p-0">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Course</th>
                        <th>Material</th>
                        <th>Type</th>
                        <th>Week</th>
                        <th>Source</th>
                        <th>Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($materials as $index => $material)

                    <tr>

                        {{-- INDEX --}}
                        <td class="text-muted fw-semibold">
                            {{ ($materials->currentPage() - 1) * $materials->perPage() + $index + 1 }}
                        </td>

                        {{-- COURSE --}}
                        <td>
                            <div class="fw-semibold">
                                {{ $material->course->course_code ?? 'N/A' }}
                            </div>
                            <small class="text-muted">
                                {{ $material->course->course_title ?? '' }}
                            </small>
                        </td>

                        {{-- MATERIAL --}}
                        <td>
                            <div class="fw-semibold">
                                {{ $material->title }}
                            </div>
                            <small class="text-muted">
                                {{ Str::limit($material->description, 60) }}
                            </small>
                        </td>

                        {{-- TYPE --}}
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ ucwords(str_replace('_', ' ', $material->type)) }}
                            </span>
                        </td>

                        {{-- WEEK --}}
                        <td class="text-muted">
                            {{ $material->week ? 'Wk ' . $material->week : '-' }}
                        </td>

                        {{-- SOURCE --}}
                        <td>
                            @if($material->file_path)
                                <span class="badge bg-success-subtle text-success">File</span>
                            @elseif($material->external_link)
                                <span class="badge bg-primary-subtle text-primary">Link</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- DATE --}}
                        <td>
                            <div>{{ $material->created_at->format('d M Y') }}</div>
                            <small class="text-muted">
                                {{ $material->created_at->format('H:i') }}
                            </small>
                        </td>

                        {{-- ACTION --}}
                        <td class="text-center">

                            <div class="btn-group btn-group-sm">

                                <button class="btn btn-light border"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewMaterialModal{{ $material->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="btn btn-light border"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editMaterialModal{{ $material->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form action="{{ route('lecturer.materials.destroy', $material->id) }}"
                                     data-bs-toggle="modal"
                                        data-bs-target="#deleteMaterialModal{{ $material->id }}">

                                    @csrf
                                    @method('DELETE')

                                   <button type="button"
                                            class="btn btn-light border text-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteMaterialModal{{ $material->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="text-center p-4 text-muted">
                            No materials found.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $materials->withQueryString()->links() }}
    </div>

</div>


{{-- CREATE MODAL --}}
@include('lecturer.materials.partials.create-modal', [
    'course' => $course ?? null,
    'sessions' => $sessions
])


{{-- VIEW MODALS --}}
@foreach($materials as $material)

    @include('lecturer.materials.partials.view-material-modal', [
        'material' => $material
    ])

@endforeach

{{-- EDIT MODALS --}}
@foreach($materials as $material)

    @include('lecturer.materials.partials.edit-material-modal', [
        'material' => $material
    ])

@endforeach

{{-- DELETE MODAL --}}
@foreach($materials as $material)
    @include('lecturer.materials.partials.delete-modal', [
        'material' => $material
    ])
@endforeach



@endsection
