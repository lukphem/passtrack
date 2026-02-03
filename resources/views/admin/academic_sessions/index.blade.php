@extends('admin.dashboard.layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold">Academic Session Management</h3>
        <p class="text-muted mb-0">
            Manage academic years and active sessions
        </p>
    </div>

    <button class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addSessionModal">
        <i class="bi bi-plus"></i> Add Session
    </button>
</div>

<div class="row g-4">
@foreach($sessions as $session)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">

            <div class="card-body d-flex flex-column justify-content-between">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-semibold mb-0">
                            {{ $session->name }}
                        </h5>
                        <small class="text-muted">
                            {{ $session->start_year }} – {{ $session->end_year }}
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#editSession{{ $session->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>

                        @if(!$session->is_active)
                        <button class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteSession{{ $session->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    @if($session->is_active)
                        <span class="badge bg-success">
                            Active Session
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            Inactive Session
                        </span>
                    @endif
                </div>

                <hr>

                {{-- Stats --}}
                <div class="d-flex justify-content-between">

                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary bg-opacity-10 text-primary rounded p-2">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">
                                {{ $session->semesters_count ?? 0 }}
                            </div>
                            <small class="text-muted">Semesters</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-success bg-opacity-10 text-success rounded p-2">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">
                                {{ $session->is_active ? 'Yes' : 'No' }}
                            </div>
                            <small class="text-muted">Active</small>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-3 d-flex gap-2">
                    @if(!$session->is_active)
                        <form method="POST"
                              action="{{ route('admin.academic-sessions.activate', $session) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-success w-100">
                                Activate
                            </button>
                        </form>
                    @else
                        <button class="btn btn-sm btn-success w-100" disabled>
                            Current Session
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endforeach
</div>

@endsection


{{-- ================= MODALS ================= --}}

{{-- Add Session Modal --}}
@include('admin.academic-sessions.partials.add-modal')


{{-- Edit Session Modals --}}
@foreach ($sessions as $session)
    @include('admin.academic-sessions.partials.edit-modal', [
        'session' => $session
    ])
@endforeach


{{-- Delete Session Modals --}}
@foreach ($sessions as $session)
    @include('admin.academic-sessions.partials.delete-modal', [
        'session' => $session
    ])
@endforeach
