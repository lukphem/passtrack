<div id="sidebar" class="sidebar p-3">
    <h5 class="text-white mb-4 sidebar-title">Admin Dashboard</h5>

    <ul class="nav flex-column gap-1">

        {{-- Dashboard --}}
        <li>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i>
                <span class="link-text">Overview</span>
            </a>
        </li>



        {{-- Faculties --}}
        <li>
            <a href="{{ route('admin.faculties.index') }}"
               class="nav-link {{ request()->routeIs('admin.faculties.*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3"></i>
                <span class="link-text">Faculties</span>
            </a>
        </li>

        {{-- Departments --}}
        <li>
            <a href="{{ route('admin.departments.index') }}"
               class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i>
                <span class="link-text">Departments</span>
            </a>
        </li>


        {{-- Academic_Sessions --}}
        <li>
            <a href="{{ route('admin.academic-sessions.index') }}"
               class="nav-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-date"></i>
                <span class="link-text">Academic Sessions</span>
            </a>
        </li>

                {{-- Semester --}}
        <li>
            <a href="{{ route('admin.academic-semester.index') }}"
               class="nav-link {{ request()->routeIs('admin.academic-semester.*') ? 'active' : '' }}">
                <i class="bi bi-cloud"></i>
                <span class="link-text">Semester Mgt</span>
            </a>
        </li>


        {{-- Courses --}}
        <li>
            <a href="{{ route('admin.courses.index') }}"
               class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                <i class="bi bi-journal"></i>
                <span class="link-text">Courses</span>
            </a>
        </li>


        {{-- Lecturers --}}
        <li>
            <a href="{{ route('admin.users.index', ['role' => 'lecturer']) }}"
               class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i>
                <span class="link-text">Lecturers</span>
            </a>
        </li>

                        {{-- Students --}}
        <li>
            <a href="{{ route('admin.users.index', ['role' => 'student']) }}"
               class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span class="link-text">Students</span>
            </a>
        </li>

        {{-- Course Registration --}}
        <li>
            <a href="{{ route('admin.academic-sessions.index') }}"
               class="nav-link {{ request()->routeIs('admin.academic-sessions.*') ? 'active' : '' }}">
                <i class="bi bi-pencil-square"></i>
                <span class="link-text">Course Registration</span>
            </a>
        </li>

        {{-- Performance --}}
        <li>
            <a href="{{ route('admin.dashboard') }}#performance"
               class="nav-link">
                <i class="bi bi-bar-chart"></i>
                <span class="link-text">Performance</span>
            </a>
        </li>

        {{-- Student Comments --}}
        <li>
            <a href="#"
               class="nav-link">
                <i class="bi bi-chat-left-text"></i>
                <span class="link-text">Student Comments</span>
            </a>
        </li>

                {{-- Profile --}}
        <li>
            <a href="#"
               class="nav-link">
                <i class="bi bi-person"></i>
                <span class="link-text">My Profile</span>
            </a>
        </li>

                {{-- logout --}}
        <li>
            <a href="#"
               class="nav-link">
                <i class="bi bi-box-arrow-right"></i>
                <span class="link-text">Logout</span>
            </a>
        </li>

    </ul>
</div>
