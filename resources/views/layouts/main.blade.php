<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Pengelolaan Teh Kita')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('templates/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/dist/css/custom-admin.css') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

    @stack('css')

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }

        /* Perbaikan Sidebar agar tidak goyang */
        .main-sidebar { transition: width 0.3s ease; }
        .content-wrapper { transition: margin-left 0.3s ease; }

        /* Card Shadow lebih halus */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }

        /* --- FIX SIDEBAR COLLAPSE --- */
        .sidebar-mini.sidebar-collapse .main-sidebar:hover {
            width: 4.6rem !important; 
        }

        .sidebar-mini.sidebar-collapse .main-sidebar:hover .nav-sidebar p,
        .sidebar-mini.sidebar-collapse .main-sidebar:hover .brand-text,
        .sidebar-mini.sidebar-collapse .main-sidebar:hover .user-panel .info {
            display: none !important;
        }
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
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h3 class="font-weight-bold text-dark">@yield('page')</h3>
                    </div>
                    <div class="col-sm-6 text-right">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#" class="text-success">Home</a></li>
                            <li class="breadcrumb-item active">@yield('page')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content pb-4">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer text-center">
        <strong>&copy; {{ date('Y') }} <span class="text-success">Teh Kita</span></strong>
    </footer>
</div>

<script src="{{ asset('templates/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('templates/dist/js/adminlte.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
  $(document).ready(function() {
    $('[data-widget="pushmenu"]').PushMenu({ expandOnHover: false });
  });
</script>

@stack('js')
</body>
</html>