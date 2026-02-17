<aside class="main-sidebar sidebar-light-success elevation-3">

    <!-- BRAND -->
    <a href="{{ route('user.dashboard') }}" class="brand-link d-flex align-items-center px-3">
        <div class="brand-image d-flex align-items-center justify-content-center"
             style="width:40px;height:40px;border-radius:50%;
                    background:#28a745;color:white;font-weight:bold;">
            TK
        </div>
        <span class="brand-text font-weight-bold ml-3" style="font-size:18px; letter-spacing:1px;">Teh Kita</span>
    </a>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <!-- USER PANEL -->
        <div class="user-panel mt-4 pb-3 mb-3 d-flex align-items-center px-3">
            <div class="image">
                <img src="{{ auth()->user()->photo ? asset('uploads/profile/' . auth()->user()->photo) : asset('templates/dist/img/user2-160x160.jpg') }}"
                     class="img-circle elevation-2" style="width:45px;height:45px;object-fit:cover;" alt="User Image">
            </div>
            <div class="info ml-3">
                <div class="font-weight-bold">{{ auth()->user()->name }}</div>
                <small class="text-muted d-block">{{ ucfirst(auth()->user()->role) }}</small>

                {{-- PROFILE LINK --}}
                <a href="{{ route('profile.edit') }}" class="text-success small d-block mt-1">
                    <i class="fas fa-user-circle mr-1"></i> Lihat Profil
                </a>
            </div>
        </div>

        <!-- MENU -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Stok Outlet -->
                <li class="nav-item">
                    <a href="{{ route('user.stok-outlet.index') }}" class="nav-link {{ request()->routeIs('user.stok-outlet.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Stok Outlet</p>
                    </a>
                </li>

                <!-- Pemakaian Bahan -->
                <li class="nav-item">
                    <a href="{{ route('user.pemakaian.create') }}" class="nav-link {{ request()->routeIs('user.pemakaian.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>Pemakaian Bahan</p>
                    </a>
                </li>

                <!-- Riwayat Distribusi -->
                <li class="nav-item">
                    <a href="{{ route('user.distribusi.index') }}" class="nav-link {{ request()->routeIs('user.distribusi.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Riwayat Distribusi</p>
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-left w-100 text-danger" style="border:none;">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p class="d-inline">Logout</p>
                        </button>
                    </form>
                </li>

            </ul>
        </nav>
    </div>
</aside>