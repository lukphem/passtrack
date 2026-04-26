@extends('layouts.student')

@section('content')
<div class="container">

    <h2>Course Registration</h2>

    <div class="card p-3 mb-3">
        <h5>Registration Status</h5>
        <a href="{{ route('student.registration.status') }}" class="btn btn-info">
            Check Status
        </a>
    </div>

    <div class="card p-3 mb-3">
        <h5>Available Courses</h5>
        <a href="{{ route('student.courses.available') }}" class="btn btn-primary">
            View Courses
        </a>
    </div>

    <div class="card p-3 mb-3">
        <h5>My Courses</h5>
        <a href="{{ route('student.courses.mine') }}" class="btn btn-success">
            View Registered Courses
        </a>
    </div>

</div>
@endsection
