<aside class="main-sidebar sidebar-light-success elevation-3">

    <!-- BRAND -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset('images/logo teh kita.png') }}" 
             alt="Logo" 
             class="brand-image">
        <span class="brand-text">Teh Kita</span>
    </a>

    <div class="sidebar">

        <!-- USER PANEL -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->photo 
                    ? asset('uploads/profile/' . auth()->user()->photo) 
                    : asset('templates/dist/img/user2-160x160.jpg') }}"
                     class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('admin.profile.edit') }}" class="d-block text-success">
                    {{ auth()->user()->name }}
                </a>
                <small class="text-muted">{{ ucfirst(auth()->user()->role) }}</small>
            </div>
        </div>

        <!-- MENU -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                <!-- DASHBOARD -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">MASTER DATA</li>

                <li class="nav-item">
                    <a href="{{ route('admin.outlet.index') }}"
                       class="nav-link {{ request()->routeIs('admin.outlet.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-store"></i>
                        <p>Data Outlet</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.bahan.index') }}"
                       class="nav-link {{ request()->routeIs('admin.bahan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box-open"></i>
                        <p>Data Bahan</p>
                    </a>
                </li>

                <li class="nav-header">TRANSAKSI</li>

                <li class="nav-item">
                    <a href="{{ route('admin.stok-masuk.index') }}"
                       class="nav-link {{ request()->routeIs('admin.stok-masuk.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-arrow-circle-down"></i>
                        <p>Stok Masuk</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.distribusi.index') }}"
                       class="nav-link {{ request()->routeIs('admin.distribusi.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-truck-moving"></i>
                        <p>Distribusi</p>
                    </a>
                </li>

                <li class="nav-header">LAPORAN</li>

                <!-- **PERBAIKAN ROUTE** -->
                <li class="nav-item">
                    <a href="{{ route('admin.laporan.lengkap') }}"
                       class="nav-link {{ request()->routeIs('admin.laporan.lengkap') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-pdf"></i>
                        <p>Cetak Laporan Lengkap</p>
                    </a>
                </li>

                <li class="nav-header">KOMUNIKASI</li>

                <li class="nav-item">
                    <a href="{{ route('chat.index') }}"
                       class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Chat Outlet</p>
                    </a>
                </li>

                <li class="nav-header">AKUN</li>

                <li class="nav-item">
                    <a href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="nav-link text-danger">
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