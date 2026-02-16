<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">

  <!-- Left navbar links -->
  <ul class="navbar-nav">

    <!-- Toggle Sidebar -->
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
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

  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">

    <!-- Messages Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-comments"></i>
        @if($unreadCount > 0)
          <span class="badge badge-danger navbar-badge">{{ $unreadCount }}</span>
        @endif
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">{{ $unreadCount }} Messages</span>
        <div class="dropdown-divider"></div>
        <a href="{{ route('chat.index') }}" class="dropdown-item text-center">See All Messages</a>
      </div>
    </li>

    <!-- Notifications / Stok & Pemakaian (User Only) -->
    @if(auth()->user()->role === 'user')
      @php
        // Gunakan View Composer untuk stokAlert & pemakaianHariIni
        $stokAlert = $stokAlert ?? collect();
        $pemakaianHariIni = $pemakaianHariIni ?? collect();
        $totalNotif = $stokAlert->count() + $pemakaianHariIni->count();
      @endphp

      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          @if($totalNotif > 0)
            <span class="badge badge-warning navbar-badge">{{ $totalNotif }}</span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">{{ $totalNotif }} Notifikasi</span>
          <div class="dropdown-divider"></div>

          @foreach($stokAlert as $item)
            <a href="{{ route('user.stok-outlet.index') }}" class="dropdown-item">
              Stok <strong>{{ $item->bahan->nama ?? 'Bahan' }}</strong> tersisa {{ $item->stok }}
            </a>
            <div class="dropdown-divider"></div>
          @endforeach

          @foreach($pemakaianHariIni as $item)
            <a href="{{ route('user.pemakaian.index') }}" class="dropdown-item">
              Pemakaian <strong>{{ $item->bahan->nama ?? 'Bahan' }}</strong>: {{ $item->jumlah }}
            </a>
            <div class="dropdown-divider"></div>
          @endforeach

{{-- 
<a href="{{ route('notifikasi') }}" class="dropdown-item text-center">
    Lihat Semua Notifikasi
</a>
--}}
        </div>
      </li>
    @endif

    <!-- Fullscreen -->
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>

    <!-- Logout -->
    <li class="nav-item">
      <a class="nav-link text-danger" href="{{ route('logout') }}"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </li>

  </ul>

</nav>