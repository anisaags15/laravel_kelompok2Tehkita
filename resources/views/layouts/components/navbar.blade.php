<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">

  <!-- Left navbar links -->
  <ul class="navbar-nav">

    <!-- Toggle Sidebar -->
    <li class="nav-item">
      <a class="nav-link"
         data-widget="pushmenu"
         href="#"
         role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>

    <!-- Home -->
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="nav-link">
        Home
      </a>
    </li>

<!-- Contact / Chat -->
@php
    // Hitung jumlah pesan yang belum dibaca
    $unreadCount = \App\Models\Message::where('receiver_id', auth()->id())
        ->where('is_read', false)
        ->count();
@endphp

<li class="nav-item d-none d-sm-inline-block">
  <a href="{{ route('chat.index') }}" class="nav-link">
    Contact
    @if($unreadCount > 0)
      <span class="badge badge-danger navbar-badge">{{ $unreadCount }}</span>
    @endif
  </a>
</li>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">

    <!-- Search -->
    <li class="nav-item">
      <a class="nav-link"
         data-widget="navbar-search"
         href="#"
         role="button">
        <i class="fas fa-search"></i>
      </a>

      <div class="navbar-search-block">
        <form class="form-inline">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar"
                   type="search"
                   placeholder="Search"
                   aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
              <button class="btn btn-navbar"
                      type="button"
                      data-widget="navbar-search">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>

    <!-- Messages Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-comments"></i>
        @if($unreadCount > 0)
          <span class="badge badge-danger navbar-badge">{{ $unreadCount }}</span>
        @endif
      </a>

      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">
          {{ $unreadCount }} Messages
        </span>
        <div class="dropdown-divider"></div>
        <a href="{{ route('chat.index') }}" class="dropdown-item text-center">
          See All Messages
        </a>
      </div>
    </li>

    <!-- Notifications -->
    <li class="nav-item dropdown">
      <a class="nav-link"
         data-toggle="dropdown"
         href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">15</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">
          15 Notifications
        </span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item text-center">
          See All Notifications
        </a>
      </div>
    </li>

    <!-- Fullscreen -->
    <li class="nav-item">
      <a class="nav-link"
         data-widget="fullscreen"
         href="#"
         role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>

    <!-- Logout -->
    <li class="nav-item">
      <a class="nav-link text-danger"
         href="{{ route('logout') }}"
         onclick="event.preventDefault();
         document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i>
      </a>
      <form id="logout-form"
            action="{{ route('logout') }}"
            method="POST"
            class="d-none">
        @csrf
      </form>
    </li>

  </ul>

</nav>
<!-- /.navbar -->
