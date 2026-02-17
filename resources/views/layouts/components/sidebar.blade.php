<aside class="main-sidebar sidebar-light-success elevation-3">

    <!-- BRAND -->
    <a href="{{ route('admin.dashboard') }}" 
       class="brand-link d-flex align-items-center px-3">

        <!-- Logo Bundar -->
        <div class="brand-image d-flex align-items-center justify-content-center"
             style="width:40px;height:40px;border-radius:50%;
                    background:#28a745;color:white;font-weight:bold;">
            TK
        </div>

        <!-- Text -->
        <span class="brand-text font-weight-bold ml-3"
              style="font-size:18px; letter-spacing:1px;">
            Teh Kita
        </span>
    </a>

    <div class="sidebar">

        <!-- USER PANEL -->
        <div class="user-panel mt-4 pb-3 mb-3 d-flex align-items-center px-3">

            <div class="image">
                <img src="https://i.pravatar.cc/45"
                     class="img-circle elevation-2"
                     alt="User Image">
            </div>

            <div class="info ml-3">
                <div class="font-weight-bold">
                    {{ auth()->user()->name }}
                </div>
                <small class="text-muted d-block">
                    Admin Pusat
                </small>

                <!-- LINK PROFIL -->
                <a href="{{ route('profile.edit') }}"
                   class="text-success small d-block mt-1">
                    <i class="fas fa-user-circle mr-1"></i>
                    Lihat Profil
                </a>
            </div>

        </div>

        <!-- MENU -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu">

                <!-- DASHBOARD -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase text-muted">
                    Master Data
                </li>

                <!-- OUTLET -->
                <li class="nav-item">
                    <a href="{{ route('admin.outlet.index') }}"
                       class="nav-link {{ request()->routeIs('admin.outlet.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-store"></i>
                        <p>Data Outlet</p>
                    </a>
                </li>

                <!-- BAHAN -->
                <li class="nav-item">
                    <a href="{{ route('admin.bahan.index') }}"
                       class="nav-link {{ request()->routeIs('admin.bahan.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-box-open"></i>
                        <p>Data Bahan</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase text-muted">
                    Transaksi
                </li>

                <!-- STOK MASUK -->
                <li class="nav-item">
                    <a href="{{ route('admin.stok-masuk.index') }}"
                       class="nav-link {{ request()->routeIs('admin.stok-masuk.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-arrow-circle-down"></i>
                        <p>Stok Masuk</p>
                    </a>
                </li>

                <!-- DISTRIBUSI -->
                <li class="nav-item">
                    <a href="{{ route('admin.distribusi.index') }}"
                       class="nav-link {{ request()->routeIs('admin.distribusi.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-truck-moving"></i>
                        <p>Distribusi</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase text-muted">
                    Komunikasi
                </li>

                <!-- CHAT -->
                <li class="nav-item">
                    <a href="{{ route('chat.index') }}"
                       class="nav-link {{ request()->routeIs('chat.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Chat Outlet</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase text-muted">
                    Akun
                </li>

                <!-- LOGOUT -->
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="nav-link btn btn-link text-left w-100 text-danger"
                                style="border:none;">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p class="d-inline">Logout</p>
                        </button>
                    </form>
                </li>

            </ul>
        </nav>

    </div>
</aside>
