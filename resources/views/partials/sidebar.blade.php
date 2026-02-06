<div class="sidebar">
    <div class="sidebar-header">
<img src="{{ asset('admin/assets/img/logo.jpeg') }}" alt="Logo" class="sidebar-logo">
        <span class="link-text">ES TEH KITA</span>
    </div>

    <ul>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> <span class="link-text">Dashboard</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.outlet.*') ? 'active' : '' }}" href="{{ route('admin.outlet.index') }}">
                <i class="fas fa-store"></i> <span class="link-text">Data Outlet</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.bahan.*') ? 'active' : '' }}" href="{{ route('admin.bahan.index') }}">
                <i class="fas fa-leaf"></i> <span class="link-text">Data Bahan</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.stok-masuk.*') ? 'active' : '' }}" href="{{ route('admin.stok-masuk.index') }}">
                <i class="fas fa-boxes"></i> <span class="link-text">Stok Masuk</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.distribusi.*') ? 'active' : '' }}" href="{{ route('admin.distribusi.index') }}">
                <i class="fas fa-truck"></i> <span class="link-text">Distribusi</span>
            </a>
        </li>
       
<li>
    <a class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}"
       href="{{ route('admin.profile.edit') }}">
        <i class="fas fa-cog"></i>
        <span class="link-text">Profile / Settings</span>
    </a>
</li>



        <li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link logout-btn">
                    <i class="fas fa-sign-out-alt"></i> <span class="link-text">Logout</span>
                </button>
            </form>
        </li>
    </ul>
</div>
