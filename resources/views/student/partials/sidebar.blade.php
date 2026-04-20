<div id="sidebar" class="sidebar p-3">

    <h5 class="text-white mb-4 text-center">Student</h5>

    <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span class="link-text">Dashboard</span>
    </a>

    <a href="#">
        <i class="bi bi-journal-bookmark"></i>
        <span class="link-text">My Courses</span>
    </a>

    <a href="#">
        <i class="bi bi-clipboard-data"></i>
        <span class="link-text">Results</span>
    </a>

    <a href="#">
        <i class="bi bi-calendar-check"></i>
        <span class="link-text">Attendance</span>
    </a>

    <a href="#">
        <i class="bi bi-clock-history"></i>
        <span class="link-text">Timetable</span>
    </a>

</div>
