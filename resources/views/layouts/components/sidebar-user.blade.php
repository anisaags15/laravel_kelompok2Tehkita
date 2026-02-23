<aside class="main-sidebar sidebar-light-success elevation-3">
    <a href="{{ route('user.dashboard') }}" class="brand-link">
        <img src="{{ asset('images/logo teh kita.png') }}" 
             alt="Logo" 
             class="brand-image">
        <span class="brand-text font-weight-light">Teh Kita</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-4 pb-3 mb-3 d-flex align-items-center px-3 border-bottom-0">
            <div class="image">
                <div style="width:45px; height:45px; border-radius:50%; overflow:hidden; border: 2px solid #28a745;">
                    <img src="{{ auth()->user()->photo ? asset('uploads/profile/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=28a745&color=fff' }}"
                         style="width:100%; height:100%; object-fit:cover;" 
                         alt="User Image">
                </div>
            </div>
            <div class="info ml-3">
                <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                    {{ auth()->user()->name }}
                </div>
                <a href="{{ route('user.profile.edit') }}" class="text-success small">
                    <i class="fas fa-circle text-success mr-1" style="font-size: 8px;"></i> Online
                </a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">
                
                {{-- DASHBOARD --}}
                <li class="nav-item">
                    <a href="{{ route('user.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('user.dashboard') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase small font-weight-bold text-muted">
                    Operasional Outlet
                </li>

                {{-- STOK --}}
                <li class="nav-item">
                    <a href="{{ route('user.stok-outlet.index') }}" 
                       class="nav-link {{ request()->routeIs('user.stok-outlet.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Stok Tersedia</p>
                    </a>
                </li>

                {{-- INPUT PEMAKAIAN --}}
                <li class="nav-item">
                    <a href="{{ route('user.pemakaian.create') }}" 
                       class="nav-link {{ request()->routeIs('user.pemakaian.create') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-plus-circle"></i>
                        <p>Input Pemakaian</p>
                    </a>
                </li>

                {{-- WASTE --}}
                <li class="nav-item">
                    <a href="{{ route('user.waste.create') }}" 
                       class="nav-link {{ request()->routeIs('user.waste.create') ? 'active bg-danger text-white' : '' }}">
                        <i class="nav-icon fas fa-trash-alt"></i>
                        <p>Lapor Bahan Rusak</p>
                    </a>
                </li>

                {{-- RIWAYAT --}}
                <li class="nav-item {{ request()->routeIs('user.riwayat*') || request()->routeIs('user.distribusi.*') ? 'menu-open' : '' }}">
                    <a href="#" 
                       class="nav-link {{ request()->routeIs('user.riwayat*') || request()->routeIs('user.distribusi.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>
                            Riwayat Data
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('user.riwayat_pemakaian') }}" 
                               class="nav-link {{ request()->routeIs('user.riwayat_pemakaian') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-warning"></i>
                                <p>Log Pemakaian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.distribusi.index') }}" 
                               class="nav-link {{ request()->routeIs('user.distribusi.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-info"></i>
                                <p>Log Distribusi</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ================= LAPORAN OUTLET ================= --}}
                <li class="nav-header text-uppercase small font-weight-bold text-muted">
                    Laporan Outlet
                </li>

                <li class="nav-item {{ request()->routeIs('user.laporan.*') ? 'menu-open' : '' }}">
                    <a href="#" 
                    class="nav-link {{ request()->routeIs('user.laporan.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Laporan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        {{-- DASHBOARD LAPORAN (INDEX) --}}
                        <li class="nav-item">
                            <a href="{{ route('user.laporan.index') }}" 
                            class="nav-link {{ request()->routeIs('user.laporan.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-dark"></i>
                                <p>Dash Laporan</p>
                            </a>
                        </li>

                        {{-- LAPORAN STOK --}}
                        <li class="nav-item">
                            <a href="{{ route('user.laporan.stok') }}" 
                            class="nav-link {{ request()->routeIs('user.laporan.stok') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-success"></i>
                                <p>Laporan Stok</p>
                            </a>
                        </li>

                        {{-- LAPORAN DISTRIBUSI --}}
                        <li class="nav-item">
                            <a href="{{ route('user.laporan.distribusi') }}" 
                            class="nav-link {{ request()->routeIs('user.laporan.distribusi') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-primary"></i>
                                <p>Laporan Distribusi</p>
                            </a>
                        </li>

                 

                    </ul>
                </li>

                {{-- KOMUNIKASI --}}
                <li class="nav-header text-uppercase small font-weight-bold text-muted">
                    Komunikasi & Info
                </li>

                <li class="nav-item">
                    <a href="{{ route('chat.index') }}" 
                       class="nav-link {{ request()->routeIs('chat.*') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Chat Pusat</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('user.notifikasi.index') }}" 
                       class="nav-link {{ request()->routeIs('user.notifikasi.index') ? 'active bg-success' : '' }}">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>Notifikasi</p>
                    </a>
                </li>

                {{-- LOGOUT --}}
                <li class="nav-item mt-3 border-top pt-2">
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