<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- WAJIB: Biar AJAX/Chat lancar jaya --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Teh Kita')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('templates/dist/css/adminlte.min.css') }}">
    {{-- CSS Custom kamu tetap di sini --}}
    <script>
    $(document).ready(function () {
        // Matiin hover AdminLTE setelah semua plugin load
        setTimeout(function () {
            $('.main-sidebar').off('mouseenter mouseleave');
            $(document).off('mouseenter', '.main-sidebar');
            $(document).off('mouseleave', '.main-sidebar');

            // Override PushMenu hover bawaan AdminLTE
            if ($.fn.PushMenu) {
                $.fn.PushMenu.Constructor.prototype.expandOnHover = function () {};
                $.fn.PushMenu.Constructor.prototype.collapseOnHover = function () {};
            }
        }, 500);
    });
</script>
    <link rel="stylesheet" href="{{ asset('templates/dist/css/custom-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/dist/css/custom-user.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/laporan-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/laporan-user.css') }}">


    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @stack('css')

    <style id="preload-transitions">
        body, .content-wrapper, .main-sidebar, .card, .table, .nav-link, .main-footer, .main-header, .navbar, .dropdown-menu {
            transition: none !important;
        }
    </style>

    <style>
        /* FIX NAVBAR ALIGNMENT */
        .navbar-nav.ml-auto { display: flex !important; align-items: center !important; }
        .navbar-nav.ml-auto .nav-item { display: flex; align-items: center; }
        
        #dark-mode-icon {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        /* Hover effect biar makin pro */
        #dark-mode-toggle:hover #dark-mode-icon {
            transform: rotate(15deg) scale(1.1);
        }

        /* Animasi halu saat ganti tema */
        body.dark-mode-transition {
            transition: background-color 0.3s ease !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini sidebar-no-expand layout-fixed layout-navbar-fixed">

<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }
</script>

<div class="wrapper">
    @include('layouts.components.navbar')

    @auth
        @include(auth()->user()->role == 'admin' ? 'layouts.components.sidebar' : 'layouts.components.sidebar-user')
    @endauth

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h3 class="font-weight-bold">@yield('page')</h3>
                    </div>
                    <div class="col-sm-6">
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
                {{-- Alert Success/Error kamu sudah mantap di sini --}}
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
    // 1. Lepas gembok transisi setelah page load
    setTimeout(() => $('#preload-transitions').remove(), 100);
     $('.main-sidebar').off('mouseenter mouseleave');
    $(document).off('mouseenter mouseleave', '.main-sidebar');


    // 2. Injeksi Tombol Dark Mode (Identik dengan AdminLTE style)
    if ($('.navbar-nav.ml-auto').length && !$('#dark-mode-toggle').length) {
        $('.navbar-nav.ml-auto').prepend(`
            <li class="nav-item">
                <a class="nav-link" href="#" id="dark-mode-toggle">
                    <i class="fas fa-moon" id="dark-mode-icon"></i>
                </a>
            </li>
        `);
    }

    const body = $('body');
    const icon = $('#dark-mode-icon');

    // 3. Update UI berdasarkan tema
    function updateDarkModeUI(isDark) {
        if (isDark) {
            icon.removeClass('fa-moon').addClass('fa-sun').css('color', '#ffc107'); // Kuning Matahari
        } else {
            icon.removeClass('fa-sun').addClass('fa-moon').css('color', '#6c757d'); // Abu-abu standar nav
        }
    }

    // Jalankan saat load
    updateDarkModeUI(body.hasClass('dark-mode'));

    // 4. Event Click Toggle
    $(document).on('click', '#dark-mode-toggle', function(e) {
        e.preventDefault();
        body.addClass('dark-mode-transition'); // Tambah transisi smooth sebentar
        body.toggleClass('dark-mode');
        
        const isDarkNow = body.hasClass('dark-mode');
        localStorage.setItem('theme', isDarkNow ? 'dark' : 'light');
        updateDarkModeUI(isDarkNow);

        setTimeout(() => body.removeClass('dark-mode-transition'), 350);
    });
});
</script>

@stack('js')
</body>
</html>