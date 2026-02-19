<nav class="main-header navbar navbar-expand navbar-light bg-white shadow-sm border-bottom-0">
    <ul class="navbar-nav align-items-center">
        <li class="nav-item">
            <a class="nav-link text-success" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block ml-2">
            <span class="navbar-brand font-weight-bold text-success" style="letter-spacing:0.5px; font-size: 1.1rem;">
                @if(auth()->user()->role === 'admin')
                    <i class="fas fa-user-shield mr-1"></i> Admin Panel
                @else
                    <i class="fas fa-store mr-1"></i> Dashboard Outlet
                @endif
            </span>
        </li>
    </ul>

    <form class="form-inline mx-auto d-none d-md-flex" style="width:30%;">
        <div class="input-group input-group-sm w-100 shadow-sm" style="border-radius: 20px; overflow: hidden;">
            <input class="form-control border-0 bg-light shadow-none" type="search" placeholder="Cari data..." aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-success border-0" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <ul class="navbar-nav ml-auto align-items-center">
        @auth
            {{-- 🔔 UNIVERSAL NOTIFICATION SYSTEM --}}
            @php
                // Logika Hitung Notifikasi
                if(auth()->user()->role === 'admin') {
                    $stokKritisCount = \App\Models\StokOutlet::where('stok', '<', 5)->count();
                    $notifUrl = route('admin.notifikasi.index');
                } else {
                    $stokKritisCount = $stokAlert->count() ?? 0;
                    $notifUrl = route('user.notifikasi.index');
                }
                $pesanCount = $unreadMessages->count() ?? 0;
                $totalNotif = $stokKritisCount + $pesanCount;
            @endphp

            <li class="nav-item dropdown mr-3">
                <a class="nav-link p-0 position-relative" data-toggle="dropdown" href="#">
                    <i class="far fa-bell fa-lg text-muted"></i>
                    @if($totalNotif > 0)
                        <span class="badge badge-danger position-absolute" 
                              style="top: -5px; right: -8px; font-size: 0.65rem; border: 2px solid white; border-radius: 50%; padding: 2px 5px;">
                            {{ $totalNotif }}
                        </span>
                    @endif
                </a>
                
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right border-0 shadow-lg mt-3 animate__animated animate__fadeIn">
                    <span class="dropdown-item dropdown-header font-weight-bold text-success">{{ $totalNotif }} Pemberitahuan</span>
                    <div class="dropdown-divider"></div>
                    
                    @if($stokKritisCount > 0)
                        <a href="{{ $notifUrl }}" class="dropdown-item">
                            <i class="fas fa-exclamation-triangle mr-2 text-danger"></i> 
                            {{ $stokKritisCount }} Stok Kritis @if(auth()->user()->role === 'admin') Outlet @endif
                        </a>
                    @endif

                    @if($pesanCount > 0)
                        <a href="{{ route('chat.index') }}" class="dropdown-item">
                            <i class="fas fa-envelope mr-2 text-primary"></i> 
                            {{ $pesanCount }} Pesan belum dibaca
                        </a>
                    @endif

                    @if($totalNotif == 0)
                        <p class="dropdown-item text-center text-muted small py-3">Tidak ada kendala sistem</p>
                    @endif

                    <div class="dropdown-divider"></div>
                    <a href="{{ $notifUrl }}" class="dropdown-item dropdown-footer text-success fw-bold">Lihat Semua Notifikasi</a>
                </div>
            </li>

            {{-- 👤 USER PROFILE --}}
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center p-0" data-toggle="dropdown">
                    <img src="{{ auth()->user()->photo ? asset('uploads/profile/' . auth()->user()->photo) : asset('templates/dist/img/user2-160x160.jpg') }}"
                         class="img-circle elevation-1 border-success" style="width:32px;height:32px;object-fit:cover; border: 2px solid;" alt="User">
                    <span class="d-none d-md-inline text-dark font-weight-bold ml-2">{{ auth()->user()->name }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right border-0 shadow-lg mt-3 animate__animated animate__fadeIn">
                    <div class="dropdown-header text-center bg-light rounded-top">
                        <p class="mb-0 font-weight-bold text-dark">{{ auth()->user()->name }}</p>
                        <small class="text-muted">{{ ucfirst(auth()->user()->role) }}</small>
                    </div>
                    <div class="dropdown-divider m-0"></div>
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.profile.edit') : route('user.profile.edit') }}" class="dropdown-item py-2">
                        <i class="fas fa-user-edit mr-2 text-success"></i> Edit Profil
                    </a>
                    <div class="dropdown-divider m-0"></div>
                    <div class="p-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm btn-block shadow-sm rounded-pill">
                                <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </li>
        @endauth
    </ul>
</nav>