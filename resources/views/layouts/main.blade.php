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
    body {
        font-family: 'Poppins', sans-serif;
    }

    /* Smooth sidebar transition */
    .main-sidebar {
        transition: width 0.3s ease;
    }

    .content-wrapper {
        transition: margin-left 0.3s ease;
    }

    /* Card shadow lebih soft */
    .card {
        border-radius: 12px;
    }

    /* --- FIX SIDEBAR ANTENG (Tidak buka tutup saat hover) --- */
    /* Pastikan lebar sidebar saat tertutup tetap kecil meski kena mouse */
    .sidebar-mini.sidebar-collapse .main-sidebar:hover,
    .sidebar-mini.sidebar-collapse .main-sidebar.sidebar-focused {
        width: 4.6rem !important; 
    }

    /* Sembunyikan teks menu saat mouse lewat di kondisi tertutup */
    .sidebar-mini.sidebar-collapse .main-sidebar:hover .nav-sidebar p,
    .sidebar-mini.sidebar-collapse .main-sidebar:hover .brand-text,
    .sidebar-mini.sidebar-collapse .main-sidebar:hover .user-panel .info {
        display: none !important;
        visibility: hidden;
        width: 0;
    }
</style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">

<div class="wrapper">

    {{-- NAVBAR --}}
    @include('layouts.components.navbar')

    {{-- SIDEBAR --}}
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
                <div class="row mb-3">

                    <div class="col-sm-6">
                        <h3 class="font-weight-bold text-dark">
                            @yield('page')
                        </h3>
                    </div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}" class="text-success">
                                    Home
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                @yield('page')
                            </li>
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

    {{-- FOOTER --}}
    <footer class="main-footer text-center">
        <strong>&copy; {{ date('Y') }} Teh Kita</strong>
    </footer>

</div>


<script src="{{ asset('templates/plugins/jquery/jquery.min.js') }}"></script>

<script src="{{ asset('templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('templates/dist/js/adminlte.min.js') }}"></script>

<script>
  $(document).ready(function() {
    // Mematikan fitur Expand on Hover agar sidebar hanya buka tutup lewat tombol garis 3
    $('[data-widget="pushmenu"]').PushMenu({
      expandOnHover: false
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

@stack('js')

</body>
</html>