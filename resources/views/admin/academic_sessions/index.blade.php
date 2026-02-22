@extends('admin.dashboard.layouts.admin')

@section('content')

<div class="row align-items-sm-center mb-4">

    {{-- Title Section --}}
    <div class="col-12 col-sm">
        <h3 class="fw-bold mb-1">Academic Sessions</h3>
        <p class="text-muted mb-2 mb-sm-0 small">
            Manage school calendar and active sessions
        </p>
    </div>

    {{-- Button Section --}}
    <div class="col-12 col-sm-auto">
        <button class="btn btn-primary w-100 w-sm-auto"
                data-bs-toggle="modal"
                data-bs-target="#addSessionModal">
            <i class="bi bi-plus-circle"></i> Add Session
        </button>
    </div>

</div>


@forelse($academic_sessions as $session)
<div class="card mb-3 shadow-sm border-0 {{ $session->is_active ? 'border-start border-success border-4' : '' }}">
    <div class="card-body">
        <div class="row align-items-center">

            <div class="col-12 col-md-3 mb-3 mb-md-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="{{ $session->is_active ? 'text-success' : 'text-secondary' }} d-none d-lg-block">
                        <i class="bi bi-calendar-event fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted text-uppercase fw-semibold d-block" style="font-size: 0.65rem; letter-spacing: 0.05em;">Session</span>
                        <h6 class="mb-0 fw-bold">{{ $session->session_name }}</h6>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-2 mb-3 mb-md-0">
                <span class="text-muted text-uppercase fw-semibold d-block" style="font-size: 0.65rem; letter-spacing: 0.05em;">Start Date</span>
                <span class="fw-bold small">{{ \Carbon\Carbon::parse($session->start_date)->format('M d, Y') }}</span>
            </div>

            <div class="col-6 col-md-2 mb-3 mb-md-0">
                <span class="text-muted text-uppercase fw-semibold d-block" style="font-size: 0.65rem; letter-spacing: 0.05em;">End Date</span>
                <span class="fw-bold small">{{ \Carbon\Carbon::parse($session->end_date)->format('M d, Y') }}</span>
            </div>

            <div class="col-6 col-md-2">
                <span class="text-muted text-uppercase fw-semibold d-block" style="font-size: 0.65rem; letter-spacing: 0.05em;">Status</span>
                <span class="badge {{ $session->is_active ? 'bg-success' : 'bg-light text-muted border' }} rounded-pill" style="font-size: 0.7rem;">
                    {{ $session->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="col-6 col-md-3 text-end">
                <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                    <button class="btn btn-sm btn-white border-end"
                            data-bs-toggle="modal"
                            data-bs-target="#editSessionModal{{ $session->id }}"
                            title="Edit">
                        <i class="bi bi-pencil text-primary"></i>
                    </button>
                    <button class="btn btn-sm btn-white"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteSession{{ $session->id }}"
                            title="Delete">
                        <i class="bi bi-trash text-danger"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@empty
    @endforelse
@endsection


{{-- ================= MODALS ================= --}}

{{-- Add Session Modal --}}
@include('admin.academic_sessions.partials.add-modal')

{{-- Edit & Delete Modals --}}
@foreach ($academic_sessions as $session)
    @include('admin.academic_sessions.partials.edit-modal', ['session' => $session])
    @include('admin.academic_sessions.partials.delete-modal', ['session' => $session])
@endforeach


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    function applyDateLimits(sessionSelect, startInput, endInput) {
        const option = sessionSelect.options[sessionSelect.selectedIndex];
        if (!option) return;

        const min = option.dataset.start;
        const max = option.dataset.end;

        startInput.min = min;
        startInput.max = max;
        endInput.min = min;
        endInput.max = max;

        if (startInput.value < min) startInput.value = min;
        if (endInput.value > max) endInput.value = max;
    }

    // ADD MODAL
    const addSession = document.getElementById('add_session');
    if (addSession) {
        addSession.addEventListener('change', () => {
            applyDateLimits(
                addSession,
                document.getElementById('add_start_date'),
                document.getElementById('add_end_date')
            );
        });
    }

    // EDIT MODALS
    document.querySelectorAll('.session-select').forEach(select => {
        const modal = select.closest('.modal');
        const start = modal.querySelector('.start-date');
        const end = modal.querySelector('.end-date');

        applyDateLimits(select, start, end);

        select.addEventListener('change', () => {
            applyDateLimits(select, start, end);
        });
    });

});
</script>
@endpush
