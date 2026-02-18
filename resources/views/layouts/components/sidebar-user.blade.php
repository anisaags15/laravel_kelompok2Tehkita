<aside class="main-sidebar sidebar-light-success elevation-3">

    <a href="{{ route('user.dashboard') }}" class="brand-link d-flex align-items-center px-3">
        <div class="brand-image d-flex align-items-center justify-content-center"
             style="width:40px;height:40px;border-radius:50%;background:#28a745;color:white;font-weight:bold;box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            TK
        </div>
        <span class="brand-text font-weight-bold ml-3" style="font-size:18px;letter-spacing:1px;color: #28a745;">Teh Kita</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-4 pb-3 mb-3 d-flex align-items-center px-3 border-bottom-0">
            <div class="image">
                {{-- Container Foto Sidebar --}}
                <div style="width:45px; height:45px; border-radius:50%; overflow:hidden; border: 2px solid #28a745;">
                    <img src="{{ auth()->user()->photo ? asset('uploads/profile/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=28a745&color=fff' }}"
                         style="width:100%; height:100%; object-fit:cover;" 
                         alt="User Image">
                </div>
            </div>
            <div class="info ml-3">
                <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">{{ auth()->user()->name }}</div>
                <a href="{{ route('user.profile.edit') }}" class="text-success small">
                    <i class="fas fa-circle text-success mr-1" style="font-size: 8px;"></i> Online
                </a>
                    <!-- LINK PROFILE HARUS SESUAI ROLE ADMIN -->
                <a href="{{ route('user.profile.edit') }}" class="text-success small d-block mt-1">
                    <i class="fas fa-user-circle mr-1"></i> Lihat Profil
                </a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">
                
                <li class="nav-item">
                    <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase small font-weight-bold text-muted">Manajemen Stok</li>

                <li class="nav-item">
                    <a href="{{ route('user.stok-outlet.index') }}" class="nav-link {{ request()->routeIs('user.stok-outlet.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Stok Outlet</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('user.pemakaian.create') }}" class="nav-link {{ request()->routeIs('user.pemakaian.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>Pemakaian Bahan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('user.distribusi.index') }}" class="nav-link {{ request()->routeIs('user.distribusi.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Riwayat Distribusi</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase small font-weight-bold text-muted">Sistem</li>

                <li class="nav-item">
                    <a href="{{ route('user.notifikasi') }}" class="nav-link {{ request()->routeIs('user.notifikasi') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>Notifikasi</p>
                    </a>
                </li>

                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-left w-100 text-danger border-0">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>