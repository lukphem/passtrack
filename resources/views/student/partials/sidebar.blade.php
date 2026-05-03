<div id="sidebar" class="sidebar p-3">

    <h5 class="text-white mb-4 text-center">Student</h5>

    {{-- Dashboard --}}
    <a href="{{ route('student.dashboard') }}"
       class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span class="link-text">Dashboard</span>
    </a>

    {{-- Course Registration --}}
    <a href="{{ route('student.courses.available') }}"
       class="{{ request()->routeIs('student.courses.available') ? 'active' : '' }}">
        <i class="bi bi-journal-plus"></i>
        <span class="link-text">Register Courses</span>
    </a>



    {{-- Registration Status --}}
    <a href="{{ route('student.courses.index') }}"
       class="{{ request()->routeIs('student.courses.index') ? 'active' : '' }}">
        <i class="bi bi-check-circle"></i>
        <span class="link-text">Registration History</span>
    </a>

    {{-- Results --}}
    <a href="#"
       class="{{ request()->is('student/results*') ? 'active' : '' }}">
        <i class="bi bi-clipboard-data"></i>
        <span class="link-text">Results</span>
    </a>

    {{-- Attendance --}}
    <a href="#"
       class="{{ request()->is('student/attendance*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i>
        <span class="link-text">Attendance</span>
    </a>

    {{-- Timetable --}}
    <a href="#"
       class="{{ request()->is('student/timetable*') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i>
        <span class="link-text">Timetable</span>
    </a>

</div>
