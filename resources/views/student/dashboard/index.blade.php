@extends('student.layouts.app')

@section('title', 'Student Dashboard')

@section('content')

<style>
body {
    background: #f5f7fb;
}

/* WELCOME */
.welcome-box {
    background: #fff;
    border-radius: 14px;
    padding: 15px 18px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.05);
}

/* PROFILE */
.profile-card {
    border-radius: 16px;
    overflow: hidden;
    border: none;
    box-shadow: 0 6px 18px rgba(15,45,107,0.08);
}

.profile-header {
    background: linear-gradient(135deg,#1e4db7,#6366f1);
    color: #fff;
    padding: 20px;
}

.profile-img {
    border-radius: 50%;
    border: 4px solid rgba(255,255,255,0.4);
}

/* STATS */
.stat-card {
    border-radius: 18px;
    padding: 18px;
    color: #fff;
    text-align: center;
    box-shadow: 0 8px 18px rgba(0,0,0,0.08);
    transition: .2s;

    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 150px;
}

.stat-card:hover {
    transform: translateY(-4px);
}

.stat-card div {
    font-size: 13px;
    opacity: 0.9;
    margin-bottom: 6px;
}

.stat-card h5 {
    margin: 0;
    font-weight: 700;
    font-size: 22px;
}

.stat-card small {
    font-size: 12px;
    opacity: 0.85;
    margin-top: 6px;
    display: block;
}

/* COLORS */
.bg-blue { background: linear-gradient(135deg,#1e4db7,#0f2d6b); }
.bg-cyan { background: linear-gradient(135deg,#06b6d4,#0ea5e9); }
.bg-purple { background: linear-gradient(135deg,#6366f1,#4338ca); }
.bg-orange { background: linear-gradient(135deg,#fb923c,#f97316); }

/* CARDS */
.card-box {
    background: #fff;
    border-radius: 14px;
    padding: 15px;
    border: 1px solid #eef2f7;
    box-shadow: 0 4px 14px rgba(0,0,0,0.05);
}

/* TEXT */
.big-name {
    font-size: 20px;
    font-weight: 700;
}

.sub-text {
    font-size: 13px;
    color: #6b7280;
}

.section-title {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: #4f46e5;
    margin-bottom: 10px;
}

/* MOBILE */
@media (max-width:768px) {
    .table thead { display: none; }

    .table tbody tr {
        display: block;
        margin-bottom: 15px;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 12px;
        background: #fff;
    }

    .table tbody td {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        font-size: 13px;
    }

    .table tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #6b7280;
    }
}
</style>

<div class="container-fluid">

{{-- ================= WELCOME ================= --}}
<div class="welcome-box mb-3">
    <h5 class="fw-bold text-primary">
        Welcome back, {{ $user_names }} 👋
    </h5>
    <small class="text-muted">
        {{ now()->format('l, d F Y') }}
    </small>
</div>

{{-- ================= PROFILE ================= --}}
<div class="card profile-card mb-4">
    <div class="profile-header row align-items-center">

        <div class="col-md-2 text-center mb-3 mb-md-0">
            <img src="{{ asset('storage/'.$student->profile_photo) ?? asset('images/default-avatar.png') }}"
                 width="90" height="90" class="profile-img">
        </div>

        <div class="col-md-7 text-center text-md-start">
            <div class="big-name">
                {{ strtoupper($user_names) }} {{ strtoupper($student->last_name) }}
            </div>

            <div>{{ $student->programme->programme_name ?? '-' }}</div>
            <div class="sub-text">{{ $student->matric_no }}</div>
        </div>

        <div class="col-md-3 text-end">
            @php
                $statusColor = match(strtolower($student->status)) {
                    'active' => 'success',
                    'inactive' => 'secondary',
                    'suspended' => 'danger',
                    default => 'dark'
                };
            @endphp

            <span class="badge bg-{{ $statusColor }} px-3 py-2">
                {{ strtoupper($student->status) }}
            </span>
        </div>

    </div>
</div>

{{-- ================= STATS ================= --}}
<div class="row g-3 mb-4">

    {{-- COURSES --}}
    <div class="col-6 col-md-3">
        <div class="stat-card bg-cyan">
            <div>Courses</div>
            <h5>{{ $enrolledCourses }}</h5>
            <small>{{ $enrolledCourses > 0 ? 'Enrolled' : 'None' }}</small>
        </div>
    </div>

    {{-- ATTENDANCE --}}
    <div class="col-6 col-md-3">
        @php
            $attendanceBadge = $attendanceRate >= 75 ? 'success' : ($attendanceRate >= 50 ? 'warning' : 'danger');
            $attendanceText = $attendanceRate >= 75 ? 'Good' : ($attendanceRate >= 50 ? 'Average' : 'Low');
        @endphp

        <div class="stat-card bg-cyan">
            <div>Attendance</div>
            <h5>{{ $attendanceRate }}%</h5>
            <small>
                <span class="badge bg-{{ $attendanceBadge }}">
                    {{ $attendanceText }}
                </span>
            </small>
        </div>
    </div>

    {{-- LEVEL --}}
    <div class="col-6 col-md-3">
        <div class="stat-card bg-cyan">
            <div>Level</div>
            <h5>{{ $student->level }}</h5>
            <small>{{ $student->level >= 500 ? 'Final Year' : 'In Progress' }}</small>
        </div>
    </div>

    {{-- REGISTRATION --}}
    <div class="col-6 col-md-3">
        <div class="stat-card bg-cyan">
            <div>Registration</div>

            <small>
                @if($currentSemester->registration_start_date && $currentSemester->registration_end_date)
                    {{ \Carbon\Carbon::parse($currentSemester->registration_start_date)->format('d M Y') }}
                    →
                    {{ \Carbon\Carbon::parse($currentSemester->registration_end_date)->format('d M Y') }}
                @else
                    From - To
                @endif
            </small>

            <h5>
                <span class="badge bg-{{ $currentSemester->registration_allowed ? 'success' : 'danger' }}">
                    {{ $currentSemester->registration_allowed ? 'OPEN' : 'CLOSED' }}
                </span>
            </h5>
        </div>
    </div>

</div>

{{-- ================= DETAILS ================= --}}
<div class="row g-3">

    {{-- ACADEMIC --}}
    <div class="col-md-5">
        <div class="card-box">
            <div class="section-title">Academic Information</div>

            <p><strong>Session</strong>
                <span class="badge bg-primary">
                    {{ $currentSession->session_name ?? '-' }}
                </span>
            </p>

            <p><strong>Semester</strong>
                <span class="badge bg-info text-dark">
                    {{ $currentSemester->semester_name ?? '-' }}
                </span>
            </p>

            <p><strong>Mode</strong>
                <span class="badge bg-secondary">
                    {{ $student->mode_of_admission ?? 'N/A' }}
                </span>
            </p>

            <p><strong>Admission</strong>
                <span class="badge bg-dark">
                    {{ $student->admission_session ?? '-' }}
                </span>
            </p>
        </div>
    </div>

    {{-- PERSONAL --}}
    <div class="col-md-5">
        <div class="card-box">
            <div class="section-title">Personal Information</div>

            <p><strong>Gender</strong>
                <span class="badge bg-primary">
                    {{ ucfirst($student->gender) }}
                </span>
            </p>

            <p><strong>Phone</strong>
                <span class="badge bg-light text-dark">
                    {{ $student->phone }}
                </span>
            </p>

            <p><strong>State</strong>
                <span class="badge bg-success">
                    {{ $student->state_of_origin }}
                </span>
            </p>

            <p><strong>Nationality</strong>
                <span class="badge bg-secondary">
                    {{ $student->nationality }}
                </span>
            </p>
        </div>
    </div>

    {{-- ACTIONS --}}
    <div class="col-md-2">
        <div class="card-box">
            <div class="section-title">Quick Actions</div>

            <div class="d-grid gap-2 row g-4 align-items-stretch">
                <a href="{{ route('student.courses.available') }}" class="btn btn-primary btn-sm">Register</a>
                <a href="{{ route('student.courses.mine') }}" class="btn btn-outline-primary btn-sm">Courses</a>
                <a href="{{ route('student.registration.status') }}" class="btn btn-outline-info btn-sm">Status</a>
            </div>
        </div>
    </div>

</div>

</div>

{{-- ================= PREDICTION ================= --}}
<div class="card-box mt-4">

    <div class="section-title">Pre-Exam Performance Insight</div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">

            <thead>
                <tr>
                    <th>Course</th>
                    <th>Attendance</th>
                    <th>Progress</th>
                    <th>Mock</th>
                    <th>Prediction</th>
                    <th>Risk</th>
                    <th>Readiness</th>
                </tr>
            </thead>

            <tbody>

            @forelse($predictions as $data)

                @php
                    $score = $data->predicted_score ?? 0;
                    $scoreBadge = $score >= 70 ? 'success' : ($score >= 50 ? 'warning' : 'danger');

                    $att = $data->attendance ?? 0;
                    $attColor = $att >= 75 ? 'success' : ($att >= 50 ? 'warning' : 'danger');

                    $progress = $data->study_progress ?? 0;
                    $progColor = $progress >= 70 ? 'success' : ($progress >= 40 ? 'warning' : 'danger');

                    $mock = $data->mock_score ?? 0;
                    $mockColor = $mock >= 70 ? 'success' : ($mock >= 50 ? 'warning' : 'danger');

                    $risk = $data->risk_level ?? 'LOW';
                    $riskBadge = match(strtoupper($risk)) {
                        'HIGH' => 'danger',
                        'MEDIUM' => 'warning',
                        default => 'success'
                    };

                    $readiness = $data->readiness_score ?? 0;
                @endphp

                <tr>

                    <td>
                        <strong>{{ $data->course->course_code }}</strong><br>
                        <small class="text-muted">{{ $data->course->course_title }}</small><br>

                        @if($score < 50)
                            <span class="badge bg-danger">At Risk</span>
                        @elseif($score < 70)
                            <span class="badge bg-warning text-dark">Needs Improvement</span>
                        @else
                            <span class="badge bg-success">On Track</span>
                        @endif
                    </td>

                    <td>
                        <span class="badge bg-{{ $attColor }}">{{ $att }}%</span>
                    </td>

                    <td>
                        <span class="badge bg-{{ $progColor }}">{{ $progress }}%</span>
                    </td>

                    <td>
                        <span class="badge bg-{{ $mockColor }}">{{ $mock }}%</span>
                    </td>

                    <td>
                        <span class="badge bg-{{ $scoreBadge }}">{{ $score }}%</span>
                    </td>

                    <td>
                        <span class="badge bg-{{ $riskBadge }}">{{ $risk }}</span>
                    </td>

                    <td>
                        @if($readiness >= 70)
                            <span class="badge bg-success">READY</span>
                        @elseif($readiness >= 50)
                            <span class="badge bg-warning text-dark">AVERAGE</span>
                        @else
                            <span class="badge bg-danger">RISK</span>
                        @endif
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        No prediction data yet
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>

</div>

@endsection
