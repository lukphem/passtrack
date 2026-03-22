@extends('admin.dashboard.layouts.admin')

@section('content')

{{-- HEADER --}}
<div class="row align-items-sm-center mb-4">
    <div class="col-12 col-sm">
        <h3 class="fw-bold mb-1">Custom Academic Settings</h3>
        <p class="text-muted small">
            Manage programme-specific academic calendar
        </p>
    </div>

    <div class="col-12 col-sm-auto">
        <a href="{{ route('admin.programmes.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Programmes
        </a>
    </div>
</div>

{{-- ALERTS --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
    <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- SEARCH --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="input-group">
                <span class="input-group-text bg-white">
                    <i class="bi bi-search"></i>
                </span>

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control"
                       placeholder="Search programme, code, department...">

                <button class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>
</div>

{{-- TABLE --}}
<div class="card shadow-sm border-0">
<div class="card-body p-0">

<div class="table-responsive">
<table class="table table-hover align-middle mb-0">

<thead class="table-light">
<tr>
    <th>#</th>
    <th>Programme</th>
    <th>Code</th>
    <th>Department</th>
    <th>Session</th>
    <th>Semester</th>
    <th>Semester Period</th>
    <th>Reg Period</th>
    <th>Reg Status</th>
    <th class="text-end">Action</th>
</tr>
</thead>

<tbody>
@forelse($programmes as $programme)
<tr>
    <td>{{ $loop->iteration }}</td>

    <td class="fw-semibold">
        {{ $programme->programme->programme_name ?? 'N/A' }}
    </td>

    <td>
        <span class="badge bg-primary-subtle text-primary">
            {{ $programme->programme->programme_code ?? 'N/A' }}
        </span>
    </td>

    <td>
        {{ $programme->programme->department->dept_name ?? 'N/A' }}
    </td>

    <td>
        {{ $programme->semester->academicSession->session_name ?? 'N/A' }}
    </td>

    <td>
        {{ $programme->semester->semester_name ?? 'N/A' }}
    </td>

    <td class="small">
        {{ $programme->start_date ?? '-' }} <br>
        <span class="text-muted">to</span><br>
        {{ $programme->end_date ?? '-' }}
    </td>

    <td class="small">
        {{ $programme->registration_start_date ?? '-' }} <br>
        <span class="text-muted">to</span><br>
        {{ $programme->registration_end_date ?? '-' }}
    </td>


        @php $now = now(); @endphp
    <td>
        @if(!$programme->registration_allowed)
            <span class="badge bg-secondary">Disabled</span>
        @elseif($programme->registration_start_date && $now < $programme->registration_start_date)
            <span class="badge bg-warning text-dark">Upcoming</span>
        @elseif($programme->registration_end_date && $now > $programme->registration_end_date)
            <span class="badge bg-danger">Closed</span>
        @else
            <span class="badge bg-success">Open</span>
        @endif
    </td>

    <td class="text-end">
        <button class="btn btn-sm btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#customAcademic{{ $programme->id }}">
            <i class="bi bi-pencil"></i> Edit
        </button>
    </td>
</tr>
@empty
<tr>
    <td colspan="10" class="text-center py-4 text-muted">
        No programmes found
    </td>
</tr>
@endforelse
</tbody>

</table>
</div>

</div>
</div>

{{-- PAGINATION --}}
<div class="mt-4 d-flex justify-content-center">
    {{ $programmes->withQueryString()->links('pagination::bootstrap-5') }}
</div>

@endsection


{{-- ONLY ONE MODAL SOURCE --}}
@foreach($programmes as $programme)
    @include('admin.programmes.partials.custom-modal', ['programme' => $programme])
@endforeach


<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.session-select').forEach(select => {

        select.addEventListener('change', function () {

            let programmeId = this.dataset.programme;
            let selectedSession = this.value;

            let semesterDropdown = document.getElementById('semester' + programmeId);

            let options = semesterDropdown.querySelectorAll('option');

            options.forEach(option => {

                if (!option.value) return;

                option.hidden = option.dataset.session !== selectedSession;

            });

            // Reset semester
            semesterDropdown.value = '';
        });
    });

});

//flash message auto close after 10 seconds

    setTimeout(function () {
        let alert = document.getElementById('flash-message');
        if (alert) {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 10000); // 10 seconds

</script>
