@php
    $user = auth()->user();

    $firstName = $user?->first_name
        ?? ($user?->name ? explode(' ', $user->name)[0] : 'Administrator');

    $initial = strtoupper(substr($firstName, 0, 1));
@endphp

<nav class="navbar navbar-light bg-white shadow-sm px-4">
    <button id="sidebarToggle" class="btn btn-light rounded-circle">
        <i id="sidebarToggleIcon" class="bi bi-list"></i>
    </button>

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
