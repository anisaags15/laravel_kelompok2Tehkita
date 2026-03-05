<aside class="main-sidebar sidebar-light-success elevation-3">
    <a href="{{ route('admin.dashboard') }}" class="brand-link text-center">
        <img src="{{ asset('images/logo teh kita.png') }}"
             alt="Logo"
             class="brand-image img-circle elevation-2">
        <span class="brand-text font-weight-bold">Teh Kita</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                <img src="{{ auth()->user()->photo
                    ? asset('uploads/profile/' . auth()->user()->photo)
                    : asset('templates/dist/img/user2-160x160.jpg') }}"
                     class="img-circle elevation-2"
                     alt="User Image"
                     style="width: 34px; height: 34px; object-fit: cover;">
            </div>
            <div class="info">
                <a href="{{ route('admin.profile.edit') }}" class="d-block text-success font-weight-bold">
                    {{ auth()->user()->name }}
                </a>
                <small class="text-muted"><i class="fas fa-circle text-success fa-xs"></i> Admin Pusat</small>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.notifikasi.index') }}"
                       class="nav-link {{ request()->routeIs('admin.notifikasi.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>
                            Log Notifikasi
                            @php
                                $unreadCount = auth()->user()->unreadNotifications->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="right badge badge-warning">{{ $unreadCount }}</span>
                            @endif
                        </p>
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
    <a href="{{ route('admin.bahan.index') }}" class="nav-link {{ request()->routeIs('admin.bahan.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-box"></i>
        <p>
            Data Bahan Baku
            @php
                $sidePusat = \App\Models\Bahan::where('stok_awal', '<=', 50)->count();
            @endphp
            @if($sidePusat > 0)
                {{-- Titik merah kecil di pojok kanan menu --}}
                <span class="right badge badge-danger border-circle" style="width: 10px; height: 10px; padding: 0; border-radius: 50%; margin-top: 5px;">&nbsp;</span>
            @endif
        </p>
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

                <li class="nav-item">
                    <a href="{{ route('admin.waste.index') }}"
                       class="nav-link {{ request()->routeIs('admin.waste.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-dumpster text-danger"></i>
                        <p>
                            Monitoring Waste
                            @php
                                $pendingWaste = \App\Models\Waste::where('status', 'pending')->count();
                            @endphp
                            @if($pendingWaste > 0)
                                <span class="right badge badge-danger anim-pulse">{{ $pendingWaste }}</span>
                            @endif
                        </p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.stok-kritis.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.stok-kritis.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-exclamation-triangle text-warning"></i>
                        <p>
                            Stok Kritis
                            @php
                                $sidebarStokKritis = $stokKritisCount ?? \App\Models\StokOutlet::where('stok', '<=', 5)->count();
                            @endphp
                            @if($sidebarStokKritis > 0)
                                <span class="right badge badge-danger anim-pulse">{{ $sidebarStokKritis }}</span>
                            @endif
                        </p>
                    </a>
                </li>

                <li class="nav-header">LAPORAN</li>
                <li class="nav-item has-treeview {{ request()->routeIs('admin.laporan.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Laporan <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Semua Laporan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.laporan.stok-kritis') }}" class="nav-link {{ request()->routeIs('admin.laporan.stok-kritis') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-danger"></i><p>Stok Kritis</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.laporan.stok-outlet') }}" class="nav-link {{ request()->routeIs('admin.laporan.stok-outlet') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Stok Outlet</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">AKUN</li>
                <li class="nav-item">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>