<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>@yield('title', 'Pengelolaan Teh Kita')</title>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet"
href="{{ asset('templates/plugins/fontawesome-free/css/all.min.css') }}">

<!-- AdminLTE -->
<link rel="stylesheet"
href="{{ asset('templates/dist/css/adminlte.min.css') }}">

<!-- Custom Theme -->
<link rel="stylesheet"
href="{{ asset('templates/dist/css/custom-admin.css') }}">

<!-- FullCalendar CSS -->
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

    <!-- CONTENT WRAPPER -->
    <div class="content-wrapper">

        <!-- PAGE HEADER -->
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

        <!-- MAIN CONTENT -->
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


<!-- ================= SCRIPTS ================= -->

<!-- jQuery -->
<script src="{{ asset('templates/plugins/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE -->
<script src="{{ asset('templates/dist/js/adminlte.min.js') }}"></script>

<!-- ChartJS (load setelah body biar ringan) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

@stack('js')

</body>
</html>
