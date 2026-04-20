@extends('student.layouts.app')

@section('title', 'Student Dashboard')

@section('content')

<h4 class="mb-4">Dashboard Overview</h4>

<div class="row g-3">

    <div class="col-md-3">
        <div class="stat-card">
            <h6>Courses</h6>
            <h3 class="text-primary">{{ $enrolledCourses }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <h6>Attendance</h6>
            <h3 class="text-success">{{ $attendanceRate }}%</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <h6>CGPA</h6>
            <h3 class="text-purple">{{ $cgpa }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <h6>Department</h6>
            <h3>{{ $departments }}</h3>
        </div>
    </div>

</div>

@endsection
