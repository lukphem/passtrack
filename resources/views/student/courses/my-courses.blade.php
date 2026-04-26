@extends('layouts.student')

@section('content')
<div class="container">

    <h3>My Courses</h3>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Code</th>
                <th>Title</th>
                <th>Credit Unit</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($courses as $course)
            <tr>
                <td>{{ $course->course_code }}</td>
                <td>{{ $course->course_title }}</td>
                <td>{{ $course->credit_unit }}</td>
                <td>
                    <form method="POST" action="{{ route('student.courses.drop', $course->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                            Drop
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
