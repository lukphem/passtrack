<div id="sidebar" class="sidebar p-3">

    <h5 class="text-white mb-4 text-center">Lecturer</h5>

    <a href="{{ route('lecturer.dashboard') }}" class="{{ request()->routeIs('lecturer.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span class="link-text">Dashboard</span>
    </a>

    <a href="#">
        <i class="bi bi-journal-text"></i>
        <span class="link-text">My Courses</span>
    </a>

    <a href="#">
        <i class="bi bi-people"></i>
        <span class="link-text">Students</span>
    </a>

    <a href="#">
        <i class="bi bi-check2-square"></i>
        <span class="link-text">Attendance</span>
    </a>

    <a href="#">
        <i class="bi bi-bar-chart"></i>
        <span class="link-text">Results</span>
    </a>

</div>
