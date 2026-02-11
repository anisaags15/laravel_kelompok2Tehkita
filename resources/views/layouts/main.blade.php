<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>@yield('title', 'Pengelolaan Teh Kita')</title>

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome -->
  <link rel="stylesheet"
        href="{{ asset('templates/plugins/fontawesome-free/css/all.min.css') }}">

  <!-- Ionicons -->
  <link rel="stylesheet"
        href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- AdminLTE -->
  <link rel="stylesheet"
        href="{{ asset('templates/dist/css/adminlte.min.css') }}">

</head>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

  {{-- Navbar --}}
  @include('layouts.components.navbar')


  {{-- Sidebar (sesuai role) --}}
  @auth
    @if(auth()->user()->role == 'admin')
        @include('layouts.components.sidebar')
    @else
        @include('layouts.components.sidebar-user')
    @endif
  @endauth


  <!-- Content Wrapper -->
  <div class="content-wrapper">


    <!-- Header -->
    <section class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">
            <h1>@yield('page')</h1>
          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item">
                <a href="#">Home</a>
              </li>

              <li class="breadcrumb-item active">
                @yield('page')
              </li>

            </ol>

          </div>

        </div>

      </div>

    </section>


    <!-- Main Content -->
    <section class="content">

      <div class="container-fluid">

        @yield('content')

      </div>

    </section>


  </div>
  <!-- /.content-wrapper -->


  {{-- Footer --}}
  <footer class="main-footer">

    <div class="float-right d-none d-sm-inline">
      Version 1.0
    </div>

    <strong>
      &copy; {{ date('Y') }} Teh Kita
    </strong>

  </footer>

</div>
<!-- ./wrapper -->


<!-- jQuery -->
<script src="{{ asset('templates/plugins/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE -->
<script src="{{ asset('templates/dist/js/adminlte.min.js') }}"></script>

</body>
</html>
