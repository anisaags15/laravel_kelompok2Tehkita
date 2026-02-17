<nav class="main-header navbar navbar-expand navbar-light bg-white shadow-sm">

    <!-- LEFT -->
    <ul class="navbar-nav align-items-center">
        <li class="nav-item">
            <a class="nav-link text-success" data-widget="pushmenu" href="#">
                <i class="fas fa-bars"></i>
            </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block ml-2">
            <span class="navbar-brand font-weight-bold text-success" style="letter-spacing:1px;">
                @if(auth()->user()->role === 'admin')
                    Dashboard Admin
                @else
                    Dashboard Outlet
                @endif
            </span>
        </li>
    </ul>

    <!-- SEARCH -->
    <form class="form-inline mx-auto d-none d-md-flex" style="width: 40%;">
        <div class="input-group input-group-sm w-100">
            <input class="form-control border-0 shadow-sm" type="search" name="search" placeholder="Cari data..." style="border-radius:20px 0 0 20px;">
            <div class="input-group-append">
                <button class="btn btn-success shadow-sm" type="submit" style="border-radius:0 20px 20px 0;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- RIGHT -->
    <ul class="navbar-nav align-items-center">

        @auth
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown">
                <img src="{{ auth()->user()->photo ? asset('uploads/profile/' . auth()->user()->photo) : asset('templates/dist/img/user2-160x160.jpg') }}"
                     class="img-circle elevation-2" style="width:35px;height:35px;object-fit:cover;" alt="User Image">
                <span class="d-none d-md-inline ml-2">{{ auth()->user()->name }}</span>
            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow">
                <div class="dropdown-header text-center">
                    <img src="{{ auth()->user()->photo ? asset('uploads/profile/' . auth()->user()->photo) : asset('templates/dist/img/user2-160x160.jpg') }}"
                         class="img-circle mb-2" style="width:60px;height:60px;object-fit:cover;" alt="User Image">
                    <p class="mb-0 font-weight-bold">{{ auth()->user()->name }}</p>
                    <small class="text-muted">{{ auth()->user()->email }}</small>
                </div>

                <div class="dropdown-divider"></div>

                {{-- PROFILE LINK SESUAI ROLE --}}
                <a href="{{ route('profile.edit') }}" class="dropdown-item text-success">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>

                <div class="dropdown-divider"></div>

                <form action="{{ route('logout') }}" method="POST" class="px-3">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm btn-block">Logout</button>
                </form>
            </div>
        </li>
        @endauth

    </ul>
</nav>