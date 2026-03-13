<aside class="main-sidebar sidebar-light-success elevation-3">
    <a href="{{ route('user.dashboard') }}" class="brand-link">
        <img src="{{ asset('images/logo teh kita.png') }}"
             alt="Logo"
             class="brand-image img-circle elevation-2"
             style="opacity: 1;">
        <span class="brand-text font-weight-bold text-success">Teh Kita</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">

                {{-- DASHBOARD --}}
                <li class="nav-item">
                    <a href="{{ route('user.dashboard') }}"
                       class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- NOTIFIKASI --}}
                <li class="nav-item">
                    <a href="{{ route('user.notifikasi.index') }}"
                       class="nav-link {{ request()->routeIs('user.notifikasi.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>
                            Notifikasi
                            @php $notifCount = auth()->user()->unreadNotifications->count(); @endphp
                            @if($notifCount > 0)
                                <span class="badge badge-warning right">{{ $notifCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>

                <li class="nav-header">OPERASIONAL OUTLET</li>

                {{-- STOK --}}
                <li class="nav-item">
                    <a href="{{ route('user.stok-outlet.index') }}"
                       class="nav-link {{ request()->routeIs('user.stok-outlet.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Stok Tersedia</p>
                    </a>
                </li>

                {{-- INPUT PEMAKAIAN --}}
                <li class="nav-item">
                    <a href="{{ route('user.pemakaian.create') }}"
                       class="nav-link {{ request()->routeIs('user.pemakaian.create') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-plus-circle"></i>
                        <p>Input Pemakaian</p>
                    </a>
                </li>

                {{-- WASTE --}}
                <li class="nav-item">
                    <a href="{{ route('user.waste.create') }}"
                       class="nav-link {{ request()->routeIs('user.waste.create') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-trash-alt"></i>
                        <p>Lapor Bahan Rusak</p>
                    </a>
                </li>

                {{-- ✅ JADWAL DISTRIBUSI --}}
                <li class="nav-item">
                    <a href="{{ route('user.jadwal-distribusi.index') }}"
                       class="nav-link {{ request()->routeIs('user.jadwal-distribusi.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Jadwal Distribusi</p>
                    </a>
                </li>

                <li class="nav-header">MONITORING & DATA</li>

                {{-- RIWAYAT LOG --}}
                <li class="nav-item has-treeview {{ request()->routeIs('user.riwayat*') || request()->routeIs('user.distribusi.index') || request()->routeIs('user.waste.index') ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link {{ request()->routeIs('user.riwayat*') || request()->routeIs('user.distribusi.index') || request()->routeIs('user.waste.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Riwayat Log <i class="right fas fa-angle-left"></i></p>
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
                            <a href="{{ route('user.waste.index') }}"
                               class="nav-link {{ request()->routeIs('user.waste.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Log Waste (Rusak)</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.distribusi.index') }}"
                               class="nav-link {{ request()->routeIs('user.distribusi.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-info"></i>
                                <p>Log Penerimaan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- LAPORAN --}}
                <li class="nav-item has-treeview {{ request()->routeIs('user.laporan.*') ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link {{ request()->routeIs('user.laporan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-contract"></i>
                        <p>Laporan Outlet <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('user.laporan.index') }}"
                               class="nav-link {{ request()->routeIs('user.laporan.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-dark"></i>
                                <p>Menu Laporan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.laporan.stok') }}"
                               class="nav-link {{ request()->routeIs('user.laporan.stok') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-success"></i>
                                <p>Lap. Stok Bahan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.laporan.distribusi') }}"
                               class="nav-link {{ request()->routeIs('user.laporan.distribusi') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-primary"></i>
                                <p>Lap. Distribusi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.laporan.ringkasan') }}"
                               class="nav-link {{ request()->routeIs('user.laporan.ringkasan') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-warning"></i>
                                <p>Ringkasan Bulanan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.laporan.waste') }}"
                               class="nav-link {{ request()->routeIs('user.laporan.waste') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Lap. Waste Bahan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">KOMUNIKASI</li>

                <li class="nav-item">
                    <a href="{{ route('chat.index') }}"
                       class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Chat Pusat</p>
                    </a>
                </li>

                <li class="nav-header">AKUN</li>

                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form-user" style="display:none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-user').submit();"
                       class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>