<div id="sidebar" class="sidebar p-3">
    <h5 class="text-white mb-4 sidebar-title">Lecturer</h5>

    <ul class="nav flex-column gap-1">

        {{-- Dashboard --}}
        <li>
            <a href="{{ route('lecturer.dashboard') }}"
               class="nav-link {{ request()->routeIs('lecturer.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span class="link-text">Dashboard</span>
            </a>
        </li>

        {{-- My Courses --}}
        <li>
            <a href="{{ route('lecturer.courses.index') }}"
               class="nav-link {{ request()->routeIs('lecturer.courses.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i>
                <span class="link-text">My Courses</span>
            </a>
        </li>

        {{-- Students --}}
        <li>
            <a href="{{ route('lecturer.students.index') }}"
               class="nav-link {{ request()->routeIs('lecturer.students.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span class="link-text">Students</span>
            </a>
        </li>

           {{-- Materials --}}
        <li>
            <a href="{{ route('lecturer.materials.index') }}"
               class="nav-link {{ request()->routeIs('lecturer.materials.*') ? 'active' : '' }}">
                <i class="bi bi-folder2-open"></i>
                <span class="link-text">Materials</span>
            </a>
        </li>

        {{-- Attendance --}}
        <li>
            <a href="#"
               class="nav-link {{ request()->routeIs('lecturer.attendance.*') ? 'active' : '' }}">
                <i class="bi bi-check2-square"></i>
                <span class="link-text">Attendance</span>
            </a>
        </li>

        {{-- Results --}}
        <li>
            <a href="#"
               class="nav-link {{ request()->routeIs('lecturer.results.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i>
                <span class="link-text">Results</span>
            </a>
        </li>

        {{-- Quiz --}}
        <li>
            <a href="#"
               class="nav-link {{ request()->routeIs('lecturer.quiz.*') ? 'active' : '' }}">
                <i class="bi bi-patch-question"></i>
                <span class="link-text">Quiz</span>
            </a>
        </li>

        {{-- Assignment --}}
        <li>
            <a href="#"
               class="nav-link {{ request()->routeIs('lecturer.assignments.*') ? 'active' : '' }}">
                <i class="bi bi-pencil-square"></i>
                <span class="link-text">Assignments</span>
            </a>
        </li>


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

    </ul>
</div>
