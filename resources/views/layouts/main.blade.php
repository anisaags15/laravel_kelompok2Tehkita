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

    <link rel="stylesheet" href="{{ asset('templates/dist/css/custom-user.css') }}"> 

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @stack('css')

    <style id="preload-transitions">
        body, .content-wrapper, .main-sidebar, .card, .table, .nav-link, .main-footer, .main-header, .navbar, .dropdown-menu {
            transition: none !important;
        }
    </style>

    {{-- REVISI CSS: MENGUNCI POSISI NAVBAR AGAR RATA --}}
    <style>
        /* Memaksa kontainer navbar kanan menjadi flex dan rata tengah */
        .navbar-nav.ml-auto {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            height: 100%;
        }

        /* Memastikan li item di dalam navbar memiliki tinggi yang sama */
        .navbar-nav.ml-auto .nav-item {
            display: flex;
            align-items: center;
            height: 100%;
        }

        /* Menstandarkan ukuran ikon agar tidak menggeser layout saat berubah */
        #dark-mode-icon {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
            display: inline-block;
        }

        /* Memastikan link navbar tidak punya margin aneh yang bikin miring */
        .navbar-nav.ml-auto .nav-link {
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">

<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }
</script>

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
    
    /* =========================================================
       [REVISI] BUKA GEMBOK TRANSISI
    ========================================================= */
    setTimeout(function() {
        $('#preload-transitions').remove();
    }, 100);

    /* =========================================================
       1. INJEKSI TOMBOL DARK MODE (VERSI FIX POSISI)
       Menggunakan tag <a> agar identik dengan lonceng AdminLTE
    ========================================================= */
    if ($('.navbar-nav.ml-auto').length) {
        if ($('#dark-mode-toggle').length === 0) {
            $('.navbar-nav.ml-auto').prepend(`
                <li class="nav-item">
                    <a class="nav-link" href="#" id="dark-mode-toggle" role="button">
                        <i class="fas fa-moon" id="dark-mode-icon" style="transition: 0.3s;"></i>
                    </a>
                </li>
            `);
        }
    }

    /* =========================================================
       2. LOGIKA DARK MODE
    ========================================================= */
    const body = $('body');
    const icon = $('#dark-mode-icon');

    function updateDarkModeUI(isDark) {
        if (isDark) {
            icon.removeClass('fa-moon').addClass('fa-sun').css('color', '#ffc107');
        } else {
            icon.removeClass('fa-sun').addClass('fa-moon').css('color', '#ffc107'); // Default warna icon mataharimu tadi
            // Jika ingin tetap abu-abu di mode terang, gunakan: .css('color', '#2f3e46')
        }
    }

    updateDarkModeUI(body.hasClass('dark-mode'));

    $(document).on('click', '#dark-mode-toggle', function(e) {
        e.preventDefault(); // Mencegah scroll ke atas karena tag <a>
        body.toggleClass('dark-mode');
        const isDarkNow = body.hasClass('dark-mode');
        
        localStorage.setItem('theme', isDarkNow ? 'dark' : 'light');
        updateDarkModeUI(isDarkNow);
    });

    /* =========================================================
       3. FUNGSI SIDEBAR BAWAAN (JANGAN DIHAPUS)
    ========================================================= */
    $('[data-widget="pushmenu"]').PushMenu({
        expandOnHover: false,
        enableRemember: true
    });

  });
</script>

@stack('js')
</body>
</html>