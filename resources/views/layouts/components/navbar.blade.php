<nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm">
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

{{-- SEARCH --}}
<form action="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" method="GET" class="form-inline mx-auto d-none d-md-flex" style="width:30%;">
    <div class="input-group input-group-sm w-100">
        {{-- TAMBAHKAN name="search" DAN value biar teksnya gak ilang pas di-enter --}}
        <input class="form-control border-0 bg-light" 
               name="search" 
               type="search" 
               placeholder="Cari data..." 
               value="{{ request('search') }}"
               aria-label="Search">
        <div class="input-group-append">
            <button class="btn btn-success border-0" type="submit">
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
            
            // Hitung Stok Kritis Outlet
            $navStokKritis = (auth()->user()->role === 'admin') 
                ? \App\Models\StokOutlet::where('stok', '<=', 5)->count() 
                : 0;

            // NEW: Hitung Stok Kritis Pusat
            $navStokPusat = (auth()->user()->role === 'admin') 
                ? \App\Models\Bahan::where('stok_awal', '<=', 50)->count() 
                : 0;
            
            // Total angka yang muncul di icon lonceng (Ditambah stok pusat)
            $totalNotif = $notifCount + $pesanCount + $navStokKritis + $navStokPusat;

            $notifUrl = (auth()->user()->role === 'admin') 
                        ? route('admin.notifikasi.index') 
                        : route('user.notifikasi.index');
        @endphp

        {{-- 🔔 NOTIFICATION DROPDOWN --}}
        <li class="nav-item dropdown">
            <a class="nav-link position-relative" data-toggle="dropdown" href="#">
                <i class="far fa-bell fa-lg text-dark"></i>
                @if($totalNotif > 0)
                    <span class="badge badge-danger navbar-badge anim-pulse">{{ $totalNotif }}</span>
                @endif
            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow border-0">
                <span class="dropdown-item dropdown-header font-weight-bold text-success">
                    {{ $totalNotif }} Pemberitahuan Baru
                </span>

                <div class="dropdown-divider"></div>

                {{-- ALERT STOK KRITIS PUSAT --}}
                @if($navStokPusat > 0)
                    <a href="{{ route('admin.bahan.index') }}" class="dropdown-item bg-warning-light">
                        <i class="fas fa-warehouse text-warning mr-2"></i>
                        <span class="text-sm font-weight-bold text-dark">{{ $navStokPusat }} Stok Pusat Menipis!</span>
                    </a>
                    <div class="dropdown-divider"></div>
                @endif

                {{-- ALERT STOK KRITIS OUTLET --}}
                @if($navStokKritis > 0)
                    <a href="{{ route('admin.stok-kritis.index') }}" class="dropdown-item bg-light">
                        <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                        <span class="text-sm font-weight-bold text-danger">{{ $navStokKritis }} Stok Outlet Kritis!</span>
                    </a>
                    <div class="dropdown-divider"></div>
                @endif

                {{-- LOOPING NOTIFIKASI LAINNYA --}}
                @forelse($unreadNotifications->take(3) as $notification)
                    <a href="{{ $notifUrl }}" class="dropdown-item">
                        <p class="text-sm mb-0">
                            <i class="fas fa-info-circle mr-2 text-info"></i>
                            {{ Str::limit($notification->data['title'] ?? 'Notifikasi Baru', 25) }}
                        </p>
                        <small class="text-xs text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </a>
                    <div class="dropdown-divider"></div>
                @empty
                    @if($pesanCount == 0 && $navStokKritis == 0 && $navStokPusat == 0)
                        <div class="dropdown-item text-center text-muted py-3">
                            Tidak ada notifikasi baru
                        </div>
                    @endif
                @endforelse

                {{-- NOTIFIKASI PESAN CHAT --}}
                @if($pesanCount > 0)
                    <a href="{{ route('chat.index') }}" class="dropdown-item text-center py-2">
                        <i class="fas fa-envelope text-primary mr-2"></i> {{ $pesanCount }} Pesan Baru
                    </a>
                @endif

                <a href="{{ $notifUrl }}" class="dropdown-item dropdown-footer text-success font-weight-bold py-2">
                    Lihat Semua Pemberitahuan
                </a>
            </div>
        </li>

        {{-- 👤 USER PROFILE --}}
        <li class="nav-item dropdown user-menu ml-2">
            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown">
                <img src="{{ auth()->user()->photo ? asset('uploads/profile/' . auth()->user()->photo) : asset('templates/dist/img/user2-160x160.jpg') }}"
                     class="img-circle elevation-1" style="width:32px;height:32px;object-fit:cover;" alt="User">
                <span class="d-none d-md-inline text-dark font-weight-bold ml-2">{{ auth()->user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow border-0">
                <div class="dropdown-header text-center bg-light">
                    <strong>{{ auth()->user()->name }}</strong><br>
                    <small class="text-muted">{{ ucfirst(auth()->user()->role) }}</small>
                </div>
                <div class="dropdown-divider"></div>
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.profile.edit') : route('user.profile.edit') }}" class="dropdown-item">
                    <i class="fas fa-user-edit mr-2 text-success"></i> Edit Profil
                </a>
                <div class="dropdown-divider"></div>
                <div class="px-3 py-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm btn-block rounded-pill">Keluar</button>
                    </form>
                </div>
            </div>
        </li>
        @endauth
    </ul>
</nav>