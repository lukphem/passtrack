@php
$user = auth()->user();
$initial = strtoupper(substr($user->first_name ?? 'S', 0, 1));
@endphp

<div class="topbar p-3 d-flex justify-content-between align-items-center">

    <!-- LEFT SIDE (LOGO + SCHOOL NAME + TOGGLE) -->
    <div class="d-flex align-items-center gap-3">

        <!-- SIDEBAR TOGGLE -->
        <button id="sidebarToggle" class="btn btn-light">
            <i class="bi bi-list"></i>
        </button>

        <!-- SCHOOL BRANDING -->
        <div class="d-flex align-items-center gap-2">

            @if(config('school.logo'))
                <img src="{{ asset('storage/school/logo.jpg') }}"
                    alt="Logo"
                    style="height:40px; width:40px; object-fit:contain;">
            @else
                <div style="width:40px; height:40px; background:#0d6efd; color:white; display:flex; align-items:center; justify-content:center; border-radius:6px;">
                    🎓
                </div>
            @endif

            <div class="d-none d-md-block">
                <div class="fw-bold" style="font-size:14px;">
                    {{ config('school.name', 'School Name') }}
                </div>
                <small class="text-muted">Student Portal</small>
            </div>

        </div>

    </div>

    <!-- RIGHT SIDE (USER INFO) -->
    <div class="d-flex align-items-center gap-3">

        <div class="text-end d-none d-md-block">
            <div class="fw-semibold">{{ $user->first_name }}</div>
            <small class="text-muted">Student</small>
        </div>

        <div class="user-avatar">
            {{ $initial }}
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-outline-danger">Logout</button>
        </form>

    </div>

</div>
