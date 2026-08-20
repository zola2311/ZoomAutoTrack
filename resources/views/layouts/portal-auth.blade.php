<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AutoTrack Ethiopia')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0/dist/css/tabler.min.css">
</head>
<body class="d-flex flex-column">
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="{{ route('portal.login') }}" class="navbar-brand navbar-brand-autodark">
                AutoTrack Ethiopia
            </a>
        </div>
        <div class="card card-md">
            <div class="card-body">
                @yield('content')
            </div>
        </div>
    </div>
</div>
</body>
</html>
