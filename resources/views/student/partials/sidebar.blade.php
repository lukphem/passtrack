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

        {{-- Learning Materials --}}
    <a href="{{ route('student.materials.index') }}"
    class="{{ request()->is('student/materials*') ? 'active' : '' }}">
        <i class="bi bi-book"></i>
        <span class="link-text">Learning Materials</span>
    </a>

    {{-- Assignments --}}
    <a href="#"
    class="{{ request()->is('student/assignments*') ? 'active' : '' }}">
        <i class="bi bi-journal-text"></i>
        <span class="link-text">Assignments</span>
    </a>

    {{-- Quizzes --}}
    <a href="#"
    class="{{ request()->is('student/quizzes*') ? 'active' : '' }}">
        <i class="bi bi-patch-question"></i>
        <span class="link-text">Quizzes</span>
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

    <li>
            <a href="#"
               class="nav-link {{ request()->routeIs('lecturer.password.change') ? 'active' : '' }}">
                <i class="bi bi-key"></i>
                <span class="link-text">Change Password</span>
            </a>
        </li>


       <li>
            <a href="#"
            class="nav-link"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span class="link-text">Logout</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>

</div>
