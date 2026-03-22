@extends('admin.dashboard.layouts.admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="row align-items-sm-center mb-4">

    <div class="col-12 col-sm">
        <h3 class="fw-bold mb-1">Academic Sessions</h3>
        <p class="text-muted mb-2 mb-sm-0 small">
            Manage school calendar and active sessions
        </p>
    </div>

    <div class="col-12 col-sm-auto">
        <button class="btn btn-primary w-100 w-sm-auto"
                data-bs-toggle="modal"
                data-bs-target="#addSessionModal">
            <i class="bi bi-plus-circle"></i> Add Session
        </button>
    </div>

</div>

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

{{-- ================= SEARCH ================= --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.academic-sessions.index') }}">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control border-start-0"
                       placeholder="Search by session name...">

                <button class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>
</div>

{{-- ================= TABLE ================= --}}
<div class="card shadow-sm border-0">
    <div class="card-body table-responsive">

        <table class="table table-hover align-middle">

            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Session Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>

            @forelse($academic_sessions as $session)

                <tr>

                    {{-- INDEX --}}
                    <td>{{ $loop->iteration }}</td>

                    {{-- NAME --}}
                    <td>
                        <strong>{{ $session->session_name }}</strong>
                    </td>

                    {{-- START DATE --}}
                    <td class="small text-muted">
                        {{ \Carbon\Carbon::parse($session->start_date)->format('M d, Y') }}
                    </td>

                    {{-- END DATE --}}
                    <td class="small text-muted">
                        {{ \Carbon\Carbon::parse($session->end_date)->format('M d, Y') }}
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if($session->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>

                    {{-- ACTIONS --}}
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">

                            <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editSessionModal{{ $session->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <button class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteSession{{ $session->id }}">
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-event fs-4 d-block mb-2"></i>
                        No academic sessions found
                    </td>
                </tr>
            @endforelse

            </tbody>

        </table>

    </div>
</div>

{{-- ================= PAGINATION ================= --}}
@if(method_exists($academic_sessions, 'links'))
<div class="mt-4 d-flex justify-content-center">
    {{ $academic_sessions->links('pagination::bootstrap-5') }}
</div>
@endif

@endsection

{{-- ================= MODALS ================= --}}
@include('admin.academic_sessions.partials.add-modal')

@foreach ($academic_sessions as $session)
    @include('admin.academic_sessions.partials.edit-modal', ['session' => $session])
    @include('admin.academic_sessions.partials.delete-modal', ['session' => $session])
@endforeach
