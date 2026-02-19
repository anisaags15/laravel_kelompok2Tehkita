<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Pengelolaan Teh Kita')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('templates/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/dist/css/custom-admin.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

    @stack('css')

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }
        .main-sidebar, .content-wrapper { transition: all 0.3s ease; }
        .card { border-radius: 12px; border: none; }
        .breadcrumb-item + .breadcrumb-item::before { content: "›"; }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

    @include('layouts.components.navbar')

    @auth
        @if(auth()->user()->role == 'admin')
            @include('layouts.components.sidebar')
        @else
            @include('layouts.components.sidebar-user')
        @endif
    @endauth

    <div class="content-wrapper">
        <section class="content-header pb-0">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12">
                        <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                            <li class="breadcrumb-item"><a href="#" class="text-success small">Home</a></li>
                            <li class="breadcrumb-item active small">@yield('page')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer text-center small text-muted">
        <strong>&copy; {{ date('Y') }} Teh Kita</strong>
    </footer>
</div>

<script src="{{ asset('templates/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('templates/dist/js/adminlte.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

@stack('js')
</body>
</html>