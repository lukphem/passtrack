@php
    $user = auth()->user();

    $firstName = $user?->first_name
        ?? ($user?->name ? explode(' ', $user->name)[0] : 'Administrator');

    $initial = strtoupper(substr($firstName, 0, 1));
@endphp

<nav class="navbar navbar-light bg-white shadow-sm px-4">

    <!-- LEFT SIDE: TOGGLE + SCHOOL BRAND -->
    <div class="d-flex align-items-center gap-3">

        <button id="sidebarToggle" class="btn btn-light rounded-circle">
            <i id="sidebarToggleIcon" class="bi bi-list"></i>
        </button>

        <!-- SCHOOL LOGO + NAME -->
        <div class="d-flex align-items-center gap-2">

            @if(config('school.logo'))
                <img src="{{ asset('storage/school/' . config('school.logo')) }}"
                     alt="Logo"
                     style="height:35px; width:35px; object-fit:contain;">
            @else
                <div style="width:35px; height:35px; background:#0d6efd; color:white; display:flex; align-items:center; justify-content:center; border-radius:6px;">
                    🎓
                </div>
            @endif

            <div class="d-none d-md-block">
                <strong style="font-size:14px;">
                    {{ config('school.name', 'School Name') }}
                </strong>
            </div>

        </div>

    </div>

    <!-- RIGHT SIDE (UNCHANGED) -->
    <div class="ms-auto d-flex align-items-center gap-3">
        <div class="text-end">
            <small class="text-muted">Welcome back,</small><br>
            <strong>{{ $firstName }}</strong>
        </div>

        <div class="user-avatar">
            {{ $initial }}
        </div>
    </div>

</nav>
