@extends('layouts.student')

@section('content')
<div class="container">

    <h3>Registration Status</h3>

    @if($open)
        <div class="alert alert-success">
            Course registration is OPEN
        </div>
    @else
        <div class="alert alert-danger">
            Course registration is CLOSED
        </div>
    @endif

</div>
@endsection
