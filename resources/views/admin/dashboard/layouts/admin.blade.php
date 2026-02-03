<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Custom CSS --}}
<style>
body {
    background-color: #f8f9fa;
}

.sidebar {
    width: 260px;
    min-height: 100vh;
    background: #0d1b2a;
    transition: width 0.3s ease;
}

.sidebar.collapsed {
    width: 80px;
}

.sidebar a {
    color: #adb5bd;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.sidebar a.active,
.sidebar a:hover {
    background: #0d6efd;
    color: #fff;
}

.sidebar .link-text,
.sidebar .sidebar-title {
    transition: opacity 0.2s ease;
}

.sidebar.collapsed .link-text,
.sidebar.collapsed .sidebar-title {
    display: none; /* completely hide */
    opacity: 0;    /* optional smooth fade */
}

.sidebar.collapsed a {
    justify-content: center;
}

.sidebar i {
    font-size: 1.2rem;
    min-width: 24px;
    text-align: center;
}

.user-avatar {
    width: 40px;
    height: 40px;
    background-color: #0d6efd;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

/* stats */
  .stat-card {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    padding: 1rem;
}

.stat-card.d-flex {
    display: flex !important; /* ensure flex */
    justify-content: space-between; /* push icon far right */
    align-items: left; /* vertical center */
}

.stat-card h6 {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.stat-card h3 {
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-card small {
    font-weight: 600;
}

.icon {
    width: 48px;
    height: 48px;
    border-radius: 0.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.bg-primary { background-color: #3b82f6 !important; } /* Blue */
.bg-success { background-color: #22c55e !important; } /* Green */
.bg-purple { background-color: #a855f7 !important; } /* Purple */
.bg-warning { background-color: #f97316 !important; } /* Orange */


</style>


    @stack('styles')
</head>
<body>

<div class="d-flex">
    @include('admin.dashboard.partials.admin-sidebar')

    <div class="flex-grow-1">
        @include('admin.dashboard.partials.admin-navbar')

        @php
            $user = auth()->user();
            $firstName = $user?->first_name ?? 'Administrator';
            $initial = strtoupper(substr($firstName, 0, 1));
        @endphp

        <main class="p-4">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@stack('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    if (!toggleBtn || !sidebar) return;

    toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
    });
});
</script>

</body>
</html>
