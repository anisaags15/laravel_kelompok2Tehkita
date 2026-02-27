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
    <link rel="stylesheet" href="{{ asset('templates/dist/css/table-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/dist/css/table-user.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-user.css') }}">
     <link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @stack('css')
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
                
                @if(session('success'))
                    <div class="alert bg-soft-success border-0 shadow-sm alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle mr-3 fa-lg text-success"></i>
                            <div>
                                <strong class="text-success d-block">Berhasil!</strong>
                                <span class="text-dark">{{ session('success') }}</span>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert bg-soft-danger border-0 shadow-sm alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle mr-3 fa-lg text-danger"></i>
                            <div>
                                <strong class="text-danger d-block">Gagal!</strong>
                                <span class="text-dark">{{ session('error') }}</span>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@include('layouts.components.sweet-alert')

<script>
  $(document).ready(function() {
    // Inisialisasi PushMenu dengan opsi agar tidak melebar saat di-hover (Fixed Sidebar)
    $('[data-widget="pushmenu"]').PushMenu({
        expandOnHover: false,
        enableRemember: true
    });
  });
</script>

@stack('js')
</body>
</html>