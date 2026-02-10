@extends('admin.dashboard.layouts.admin')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1">Semester Management</h3>
        <p class="text-muted mb-0">Manage academic periods and registration windows</p>
    </div>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSemesterModal">
        <i class="bi bi-plus-circle"></i> Add Semester
    </button>
</div>

{{-- Success/Error Alerts --}}
@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
    </div>
@endif

{{-- Semesters List --}}
@forelse($semesters as $semester)
<div class="card mb-3 shadow-sm border-0 {{ $semester->is_active ? 'border-start border-success border-4' : '' }}">
    <div class="card-body d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">

        {{-- Semester Info --}}
        <div class="d-flex align-items-center gap-3 flex-md-3 flex-1">
            <div class="{{ $semester->is_active ? 'text-primary' : 'text-secondary' }}">
                <i class="bi bi-calendar-range fs-3"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold text-uppercase"> {{ $semester->academicSession->session_name ?? 'N/A' }} {{ $semester->semester_name }}</h6>
                <small class="text-muted d-block">
                    {{ optional($semester->start_date)->format('M d, Y') }} - {{ optional($semester->end_date)->format('M d, Y') }}
                </small>
            </div>
        </div>


         {{-- Registration Status --}}
        <div class="flex-md-3 flex-1 text-center">

            @php
                $now = now();
                $regOpen = $semester->registration_allowed
                           && $semester->registration_start_date
                           && $semester->registration_end_date
                           && $now->between($semester->registration_start_date, $semester->registration_end_date);
            @endphp
            <span class="badge {{ $regOpen ? 'bg-success' : 'bg-danger' }}">
               <small class="text-muted d-block text-uppercase">Registration Status</small>
            </span>
            <div class="small text-muted mt-1">
                {{ optional($semester->registration_start_date)->format('M d, Y H:i') ?? 'N/A' }}
                {{ optional($semester->registration_end_date)->format('M d, Y H:i') ?? 'N/A' }}
            </div>
        </div>



        {{-- Semester Status --}}
        <div class="flex-md-2 flex-1 text-center">
            <small class="text-muted text-uppercase d-block">Semester Status</small>
            <span class="badge {{ $semester->is_active ? 'bg-success' : 'bg-light text-muted border' }}">
                {{ $semester->is_active ? 'Current' : 'Inactive' }}
            </span>
        </div>



        {{-- Actions --}}
        <div class="d-flex flex-md-2 flex-1 flex-column flex-md-row align-items-center gap-2 justify-content-md-end text-center text-md-end mt-2 mt-md-0">
            <div class="btn-group ms-md-2 shadow-sm" role="group">
                <button class="btn btn-sm btn-white border-end" data-bs-toggle="modal" data-bs-target="#editSemester{{ $semester->id }}">
                    <i class="bi bi-pencil text-primary"></i>
                </button>
                <button class="btn btn-sm btn-white" data-bs-toggle="modal" data-bs-target="#deleteSemester{{ $semester->id }}">
                    <i class="bi bi-trash text-danger"></i>
                </button>
            </div>
        </div>

    </div>
</div>
@empty
<div class="card text-center shadow-sm border-0 py-5">
    <i class="bi bi-calendar-x fs-1 text-muted"></i>
    <p class="mt-3 text-muted">No semesters found.</p>
</div>
@endforelse

{{-- Modals --}}
@include('admin.semesters.partials.add-modal')
@foreach($semesters as $semester)
    @include('admin.semesters.partials.edit-modal', ['semester' => $semester])
    @include('admin.semesters.partials.delete-modal', ['semester' => $semester])
@endforeach
@endsection
