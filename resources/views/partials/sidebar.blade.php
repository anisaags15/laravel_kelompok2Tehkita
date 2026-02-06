<div class="sidebar">
    <div class="sidebar-header">
        ES TEH KITA
    </div>
    <ul>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>

        <li>
            <a class="nav-link {{ request()->routeIs('admin.outlet.*') ? 'active' : '' }}" href="{{ route('admin.outlet.index') }}">
                <i class="fas fa-store"></i> Data Outlet
            </a>
        </li>

        <li>
            <a class="nav-link {{ request()->routeIs('admin.bahan.*') ? 'active' : '' }}" href="{{ route('admin.bahan.index') }}">
                <i class="fas fa-leaf"></i> Data Bahan
            </a>
        </li>

        <li>
            <a class="nav-link {{ request()->routeIs('admin.stok-masuk.*') ? 'active' : '' }}" href="{{ route('admin.stok-masuk.index') }}">
                <i class="fas fa-boxes"></i> Stok Masuk
            </a>
        </li>

        <li>
            <a class="nav-link {{ request()->routeIs('admin.distribusi.*') ? 'active' : '' }}" href="{{ route('admin.distribusi.index') }}">
                <i class="fas fa-truck"></i> Distribusi
            </a>
        </li>

        <li>
            <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                <i class="fas fa-cog"></i> Profile / Settings
            </a>
        </li>

        <li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</div>
