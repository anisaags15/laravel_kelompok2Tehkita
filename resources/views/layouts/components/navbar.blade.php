<nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm py-2">
    {{-- LEFT SIDE --}}
    <ul class="navbar-nav align-items-center">
        <li class="nav-item">
            <a class="nav-link text-success" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block ml-2">
            <span class="navbar-brand font-weight-bold text-success mb-0" style="font-size: 1.05rem;">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <i class="fas fa-user-shield mr-1"></i> Admin Panel
                    @else
                        <i class="fas fa-store mr-1"></i> Dashboard Outlet
                    @endif
                @endauth
            </span>
        </li>
    </ul>

    {{-- SEARCH (TENGAH) --}}
    <form action="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" method="GET" class="form-inline mx-auto d-none d-md-flex" style="width:30%;">
        <div class="input-group input-group-sm w-100">
            <input class="form-control border-0 bg-light rounded-left shadow-none" 
                   name="search" 
                   type="search" 
                   placeholder="Cari data..." 
                   value="{{ request('search') }}"
                   aria-label="Search"
                   style="height: 38px; padding-left: 15px;">
            <div class="input-group-append">
                <button class="btn btn-success border-0 px-3 shadow-none" type="submit" style="border-radius: 0 10px 10px 0;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    {{-- RIGHT SIDE --}}
    <ul class="navbar-nav ml-auto align-items-center">
        @auth
        @php
            $unreadNotifications = auth()->user()->unreadNotifications;
            $notifCount = $unreadNotifications->count();
            $pesanCount = \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', 0)->count();
            
            $navStokKritis = (auth()->user()->role === 'admin') 
                ? \App\Models\StokOutlet::where('stok', '<=', 5)->count() 
                : 0;

            $navStokPusat = (auth()->user()->role === 'admin') 
                ? \App\Models\Bahan::where('stok_awal', '<=', 50)->count() 
                : 0;
            
            $totalNotif = $notifCount + $pesanCount + $navStokKritis + $navStokPusat;

            $notifUrl = (auth()->user()->role === 'admin') 
                        ? route('admin.notifikasi.index') 
                        : route('user.notifikasi.index');
        @endphp

        {{-- 🔔 NOTIFICATION DROPDOWN --}}
        <li class="nav-item dropdown px-2">
            <a class="nav-link position-relative d-flex align-items-center" data-toggle="dropdown" href="#">
                <i class="far fa-bell fa-lg text-secondary"></i>
                @if($totalNotif > 0)
                    <span class="badge badge-danger navbar-badge shadow-sm" style="font-size: 0.6rem; top: 5px; right: 2px;">{{ $totalNotif }}</span>
                @endif
            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow border-0 rounded-lg">
                <span class="dropdown-item dropdown-header font-weight-bold text-success py-3">
                    {{ $totalNotif }} Pemberitahuan Baru
                </span>
                <div class="dropdown-divider m-0"></div>

                @if($navStokPusat > 0)
                    <a href="{{ route('admin.bahan.index') }}" class="dropdown-item py-3">
                        <i class="fas fa-warehouse text-warning mr-3"></i>
                        <span class="text-sm font-weight-bold">{{ $navStokPusat }} Stok Pusat Menipis!</span>
                    </a>
                    <div class="dropdown-divider m-0"></div>
                @endif

                @if($navStokKritis > 0)
                    <a href="{{ route('admin.stok-kritis.index') }}" class="dropdown-item py-3">
                        <i class="fas fa-exclamation-triangle text-danger mr-3"></i>
                        <span class="text-sm font-weight-bold">{{ $navStokKritis }} Stok Outlet Kritis!</span>
                    </a>
                    <div class="dropdown-divider m-0"></div>
                @endif

                @forelse($unreadNotifications->take(3) as $notification)
                    <a href="{{ $notifUrl }}" class="dropdown-item py-3">
                        <p class="text-sm mb-0">
                            <i class="fas fa-info-circle mr-3 text-info"></i>
                            {{ Str::limit($notification->data['title'] ?? 'Notifikasi Baru', 28) }}
                        </p>
                        <small class="text-xs text-muted ml-4">{{ $notification->created_at->diffForHumans() }}</small>
                    </a>
                    <div class="dropdown-divider m-0"></div>
                @empty
                    @if($pesanCount == 0 && $navStokKritis == 0 && $navStokPusat == 0)
                        <div class="dropdown-item text-center text-muted py-4">
                            <i class="fas fa-bell-slash d-block mb-2 fa-2x opacity-50"></i>
                            Tidak ada notifikasi baru
                        </div>
                    @endif
                @endforelse

                @if($pesanCount > 0)
                    <a href="{{ route('chat.index') }}" class="dropdown-item text-center py-3 bg-light">
                        <i class="fas fa-envelope text-primary mr-2"></i> {{ $pesanCount }} Pesan Baru
                    </a>
                @endif

                <a href="{{ $notifUrl }}" class="dropdown-item dropdown-footer text-success font-weight-bold py-3">
                    Lihat Semua Pemberitahuan
                </a>
            </div>
        </li>

        {{-- 👤 USER PROFILE --}}
        <li class="nav-item dropdown user-menu ml-2">
            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" style="padding-top: 5px;">
                @if(auth()->user()->photo)
                    <img src="{{ asset('uploads/profile/' . auth()->user()->photo) }}"
                         class="img-circle elevation-1 border border-success" 
                         style="width:34px;height:34px;object-fit:cover;" alt="User">
                @else
                    <div class="img-circle bg-success-light d-flex align-items-center justify-content-center border" 
                         style="width:34px;height:34px; background-color: #e6f7ef; border-color: #10b981 !important;">
                        <i class="fas fa-user text-success" style="font-size: 16px;"></i>
                    </div>
                @endif
                <span class="d-none d-md-inline text-dark font-weight-bold ml-2">{{ auth()->user()->name }}</span>
                <i class="fas fa-chevron-down ml-2 text-muted" style="font-size: 0.7rem;"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow border-0 rounded-lg mt-2" style="min-width: 200px;">
                <div class="dropdown-header text-center py-4 rounded-top" style="background: linear-gradient(135deg, #f8fafc, #ffffff);">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('uploads/profile/' . auth()->user()->photo) }}" class="img-circle elevation-2 mb-2" style="width:60px;height:60px;object-fit:cover;" alt="User">
                    @else
                        <div class="img-circle mx-auto bg-success-light d-flex align-items-center justify-content-center mb-2" 
                             style="width:60px;height:60px; background-color: #e6f7ef;">
                            <i class="fas fa-user text-success fa-2x"></i>
                        </div>
                    @endif
                    <h6 class="font-weight-bold text-dark mb-0">{{ auth()->user()->name }}</h6>
                    <span class="badge badge-pill badge-success px-3" style="font-size: 0.7rem;">{{ strtoupper(auth()->user()->role) }}</span>
                </div>
                
                <div class="dropdown-divider m-0"></div>
                
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.profile.edit') : route('user.profile.edit') }}" class="dropdown-item py-3">
                    <i class="fas fa-user-edit mr-3 text-success"></i> Edit Profil
                </a>
                
                <div class="dropdown-divider m-0"></div>
                
                <div class="p-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm btn-block rounded-pill font-weight-bold">
                            <i class="fas fa-sign-out-alt mr-1"></i> Keluar Aplikasi
                        </button>
                    </form>
                </div>
            </div>
        </li>
        @endauth
    </ul>
</nav>