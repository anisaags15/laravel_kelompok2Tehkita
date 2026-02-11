<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <!-- Brand Logo -->
  <a href="{{ route('admin.dashboard') }}" class="brand-link text-center">
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
          <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Data Outlet -->
        <li class="nav-item">
          <a href="{{ route('admin.outlet.index') }}" class="nav-link">
            <i class="nav-icon fas fa-store"></i>
            <p>Data Outlet</p>
          </a>
        </li>

        <!-- Data Bahan -->
        <li class="nav-item">
          <a href="{{ route('admin.bahan.index') }}" class="nav-link">
            <i class="nav-icon fas fa-box"></i>
            <p>Data Bahan</p>
          </a>
        </li>

        <!-- Stok Masuk -->
        <li class="nav-item">
          <a href="{{ route('admin.stok-masuk.index') }}" class="nav-link">
            <i class="nav-icon fas fa-arrow-down"></i>
            <p>Stok Masuk</p>
          </a>
        </li>

        <!-- Distribusi -->
        <li class="nav-item">
          <a href="{{ route('admin.distribusi.index') }}" class="nav-link">
            <i class="nav-icon fas fa-truck"></i>
            <p>Distribusi</p>
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
