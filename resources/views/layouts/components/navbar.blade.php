<nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm">

    {{-- LEFT SIDE --}}
    <ul class="navbar-nav align-items-center">
        <li class="nav-item">
            <a class="nav-link text-success" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block ml-2">
            <span class="navbar-brand font-weight-bold text-success mb-0" style="font-size: 1.1rem;">
                @if(auth()->user()->role === 'admin')
                    <i class="fas fa-user-shield mr-1"></i> Admin Panel
                @else
                    <i class="fas fa-store mr-1"></i> Dashboard Outlet
                @endif
            </span>
        </li>
    </ul>


    {{-- SEARCH --}}
    <form class="form-inline mx-auto d-none d-md-flex" style="width:30%;">
        <div class="input-group input-group-sm w-100">
            <input class="form-control border-0 bg-light shadow-none"
                   type="search"
                   placeholder="Cari data..."
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

        {{-- 🔔 NOTIFICATION --}}
        <li class="nav-item dropdown mr-3">
            <a class="nav-link position-relative px-2" data-toggle="dropdown" href="#">
                <i class="far fa-bell fa-lg text-dark"></i>

                @if($totalNotif > 0)
                    <span class="badge badge-danger navbar-badge">
                        {{ $totalNotif }}
                    </span>
                @endif
            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow border-0 mt-2">
                <span class="dropdown-item dropdown-header font-weight-bold text-success">
                    {{ $totalNotif }} Pemberitahuan
                </span>

                <div class="dropdown-divider"></div>

                @if($stokKritisCount > 0)
                    <a href="{{ $notifUrl }}" class="dropdown-item d-flex align-items-center">
                        <i class="fas fa-exclamation-circle text-danger mr-2"></i>
                        <span>
                            {{ $stokKritisCount }} Stok Kritis
                            @if(auth()->user()->role === 'admin') Outlet @endif
                        </span>
                    </a>
                @endif

                @if($pesanCount > 0)
                    <a href="{{ route('chat.index') }}" class="dropdown-item d-flex align-items-center">
                        <i class="fas fa-envelope text-primary mr-2"></i>
                        <span>{{ $pesanCount }} Pesan belum dibaca</span>
                    </a>
                @endif

                @if($totalNotif == 0)
                    <div class="dropdown-item text-center text-muted py-3">
                        Tidak ada notifikasi
                    </div>
                @endif

                <div class="dropdown-divider"></div>

                <a href="{{ $notifUrl }}" class="dropdown-item dropdown-footer text-success font-weight-bold">
                    Lihat Semua Notifikasi
                </a>
            </div>
        </li>


        {{-- 👤 USER PROFILE --}}
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center p-0"
               data-toggle="dropdown">

                <img src="{{ auth()->user()->photo 
                        ? asset('uploads/profile/' . auth()->user()->photo) 
                        : asset('templates/dist/img/user2-160x160.jpg') }}"
                     class="img-circle elevation-1"
                     style="width:32px;height:32px;object-fit:cover;"
                     alt="User">

                <span class="d-none d-md-inline text-dark font-weight-bold ml-2">
                    {{ auth()->user()->name }}
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right shadow border-0 mt-2">

                <div class="dropdown-header text-center bg-light">
                    <p class="mb-0 font-weight-bold text-dark">
                        {{ auth()->user()->name }}
                    </p>
                    <small class="text-muted">
                        {{ ucfirst(auth()->user()->role) }}
                    </small>
                </div>

                <div class="dropdown-divider"></div>

                <a href="{{ auth()->user()->role === 'admin'
                            ? route('admin.profile.edit')
                            : route('user.profile.edit') }}"
                   class="dropdown-item">
                    <i class="fas fa-user-edit mr-2 text-success"></i>
                    Edit Profil
                </a>

                <div class="dropdown-divider"></div>

                <div class="px-3 py-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="btn btn-danger btn-sm btn-block rounded-pill">
                            <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                        </button>
                    </form>
                </div>

            </div>
        </li>

        @endauth

    </ul>
</nav>
