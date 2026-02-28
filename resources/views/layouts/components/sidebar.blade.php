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
                     alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('admin.profile.edit') }}" class="d-block text-success">
                    {{ auth()->user()->name }}
                </a>
                <small class="text-muted">Admin Pusat</small>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

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
                    <a href="{{ route('admin.bahan.index') }}"
                       class="nav-link {{ request()->routeIs('admin.bahan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Data Bahan Baku</p>
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
                                $pendingCount = \App\Models\Pemakaian::where('tipe', 'waste')->where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="right badge badge-danger">{{ $pendingCount }}</span>
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
                            @if($stokKritisCount > 0)
                                <span class="right badge badge-danger">{{ $stokKritisCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>

                <li class="nav-header">LAPORAN</li>

                <li class="nav-item has-treeview {{ request()->routeIs('admin.laporan.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Laporan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.laporan.index') }}"
                            class="nav-link {{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Semua Laporan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.laporan.stok-kritis') }}"
                            class="nav-link {{ request()->routeIs('admin.laporan.stok-kritis') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Stok Kritis</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.laporan.stok-outlet') }}"
                            class="nav-link {{ request()->routeIs('admin.laporan.stok-outlet') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Stok Outlet</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.laporan.distribusi') }}"
                            class="nav-link {{ request()->routeIs('admin.laporan.distribusi') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Distribusi</p>
                            </a>
                        </li>
                    </ul>
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