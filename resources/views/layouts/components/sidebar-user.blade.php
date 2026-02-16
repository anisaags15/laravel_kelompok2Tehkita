<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <!-- Brand Logo -->
  <a href="{{ route('user.dashboard') }}" class="brand-link text-center">
    <span class="brand-text font-weight-light">Teh Kita</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column"
          data-widget="treeview"
          role="menu"
          data-accordion="false">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ route('user.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Stok Outlet -->
        <li class="nav-item">
<a href="/user/stok-outlet" class="nav-link">
            <i class="nav-icon fas fa-box"></i>
            <p>Stok Outlet</p>
          </a>
        </li>

        <!-- Pemakaian Bahan -->
        <li class="nav-item">
<a href="/user/pemakaian/create" class="nav-link">
            <i class="nav-icon fas fa-edit"></i>
            <p>Pemakaian Bahan</p>
          </a>
        </li>

        <!-- Riwayat Distribusi -->
        <li class="nav-item">
          <a href="{{ route('user.distribusi.index') }}" class="nav-link">
            <i class="nav-icon fas fa-history"></i>
            <p>Riwayat Distribusi</p>
          </a>
        </li>

        <!-- Logout -->
        <li class="nav-item">
          <a href="{{ route('logout') }}"
             class="nav-link text-danger"
             onclick="event.preventDefault();
             document.getElementById('logout-form').submit();">

            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Logout</p>
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

  </div>
</aside>
