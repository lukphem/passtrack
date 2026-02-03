@extends('admin.dashboard.layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<h4 class="mb-4">Welcome to your admin dashboard. Here's what's happening today.</h4>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card d-flex p-3">
            <div>
                <h6>Total Students</h6>
                <h3>1,234</h3>
                <small class="text-success">+12%</small>
            </div>
            <div class="icon bg-primary">
                <i class="bi bi-mortarboard"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card d-flex p-3">
            <div>
                <h6>Total Lecturers</h6>
                <h3>87</h3>
                <small class="text-success">+5%</small>
            </div>
            <div class="icon bg-success">
                <i class="bi bi-people"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card d-flex p-3">
            <div>
                <h6>Active Courses</h6>
                <h3>156</h3>
                <small class="text-success">+8%</small>
            </div>
            <div class="icon bg-purple">
                <i class="bi bi-book"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card d-flex p-3">
            <div>
                <h6>Departments</h6>
                <h3>12</h3>
                <small class="text-muted">0%</small>
            </div>
            <div class="icon bg-warning">
                <i class="bi bi-building"></i>
            </div>
        </div>
    </div>
</div>


{{-- Charts --}}
<div class="row g-4">
    <div class="col-md-6">
        <div class="card p-3">
            <h6>Enrollment Trend</h6>
            <canvas id="enrollmentChart"></canvas>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-3">
            <h6>Student Distribution by Faculty</h6>
            <canvas id="facultyChart"></canvas>
        </div>
    </div>
</div>

<div class="card p-3 mt-4">
    <h6>Grade Distribution</h6>
    <canvas id="gradeChart"></canvas>
</div>

@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('enrollmentChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Students',
                data: [950, 1000, 1080, 1120, 1180, 1220],
                borderWidth: 2,
                tension: 0.4
            }]
        }
    });
</script>
@endpush
