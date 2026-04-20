@extends('lecturer.layouts.app')

@section('title', 'Lecturer Dashboard')

@section('content')

<h4 class="mb-4">Dashboard Overview</h4>

<div class="row g-3">

    <div class="col-md-3">
        <div class="stat-card">
            <h6>Courses</h6>
            <h3 class="text-primary">0</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <h6>Students</h6>
            <h3 class="text-success">0</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <h6>Attendance</h6>
            <h3 class="text-warning">0%</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <h6>Reports</h6>
            <h3 class="text-danger">0</h3>
        </div>
    </div>

</div>

@endsection
