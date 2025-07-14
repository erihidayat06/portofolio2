<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin portofolio eri</title>

    <!-- Favicons -->
    <link href="/assets/img/logo.png" rel="icon">
    <link href="/assets/img/logo.png" rel="apple-touch-icon">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

    <!-- Optional: Custom CSS -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
</head>

<body class="bg-light">

    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div class="w-100" style="max-width: 500px;">
            <div class="text-center mb-4">
                <a href="/">
                    {{-- Ganti logo sesuai kebutuhan --}}
                    <img src="{{ asset('/assets/img/logo.png') }}" alt="Logo" width="80">
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

</body>

</html>
