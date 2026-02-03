<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Lecturer Dashboard')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background:#f5f7fb; }
        .sidebar {
            width:260px;
            min-height:100vh;
            background:#0d6efd;
        }
        .sidebar a {
            color:#fff;
            text-decoration:none;
            display:block;
            padding:12px 20px;
            border-radius:6px;
        }
        .sidebar a.active,
        .sidebar a:hover {
            background:rgba(255,255,255,.15);
        }
        @media (max-width: 991px) {
            .sidebar { position:fixed; z-index:1050; left:-260px; transition:.3s; }
            .sidebar.show { left:0; }
        }
    </style>
</head>
<body>

<div class="d-flex">

    @include('lecturer.components.sidebar')

    <div class="flex-grow-1">
        @include('lecturer.components.topbar')

        <main class="p-4">
            @yield('content')
        </main>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('show');
    }
</script>
</body>
</html>
