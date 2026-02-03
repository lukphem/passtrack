@extends('lecturer.layouts.app')

@section('title','Dashboard')

@section('content')

<h4 class="fw-semibold mb-4">Dashboard</h4>

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <small class="text-muted">Courses</small>
                <h3 class="fw-bold">6</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <small class="text-muted">Students</small>
                <h3 class="fw-bold">420</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <small class="text-muted">Attendance</small>
                <h3 class="fw-bold">95%</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <small class="text-muted">Pending Results</small>
                <h3 class="fw-bold">3</h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h6 class="fw-semibold mb-3">Recent Courses</h6>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                <tr>
                    <th>Course</th>
                    <th>Code</th>
                    <th>Students</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Software Engineering</td>
                    <td>SEN401</td>
                    <td>120</td>
                    <td><span class="badge bg-success">Active</span></td>
                </tr>
                <tr>
                    <td>Data Structures</td>
                    <td>COS202</td>
                    <td>180</td>
                    <td><span class="badge bg-warning">Ongoing</span></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
