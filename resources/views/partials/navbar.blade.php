<nav class="navbar">
    {{-- Tombol toggle sidebar --}}
    <button id="sidebarToggle" class="toggle-btn">
        <i class="fas fa-bars"></i>
    </button>

    {{-- Right icons --}}
    <div class="navbar-right">

        {{-- Notifikasi --}}
        <button class="bell-btn">
            <i class="fas fa-bell"></i>
            <span class="notification-dot"></span>
        </button>

        {{-- Profile --}}
        <div class="profile-btn">
            <button type="button">
                <i class="fas fa-user-circle text-2xl"></i>

                {{-- 🔥 AMBIL LANGSUNG DARI USER LOGIN --}}
                <span class="profile-name">
                </span>
            </button>
        </div>

    </div>
</nav>
