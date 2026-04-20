<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Student Portal')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background-color: #f4f6f9;
}

/* SIDEBAR */
.sidebar {
    width: 250px;
    min-height: 100vh;
    background: #1e293b;
    transition: width 0.3s ease;
}

.sidebar.collapsed {
    width: 80px;
}

.sidebar a {
    color: #cbd5e1;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px;
    border-radius: 6px;
}

.sidebar a:hover,
.sidebar a.active {
    background: #0d6efd;
    color: #fff;
}

.sidebar .link-text {
    transition: 0.2s;
}

.sidebar.collapsed .link-text {
    display: none;
}

.sidebar i {
    font-size: 1.2rem;
    min-width: 24px;
}

/* NAVBAR */
.topbar {
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
}

/* USER AVATAR */
.user-avatar {
    width: 40px;
    height: 40px;
    background-color: #0d6efd;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

/* CARDS */
.stat-card {
    border-radius: 10px;
    padding: 15px;
    background: white;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.05);
}

</style>

@stack('styles')
</head>

<body>

<div class="d-flex">

    {{-- SIDEBAR --}}
    @include('student.partials.sidebar')

    <div class="flex-grow-1">

        {{-- NAVBAR --}}
        @include('student.partials.navbar')

        <main class="p-4">
            @yield('content')
        </main>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('sidebarToggle')?.addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('collapsed');
});
</script>

@stack('scripts')

</body>
</html>
