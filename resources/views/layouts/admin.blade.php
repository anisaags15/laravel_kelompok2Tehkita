<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Tailwind --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Content Wrapper: Navbar + Content --}}
    <div class="content-wrapper">

        {{-- Navbar --}}
        @include('partials.navbar')

        {{-- Content --}}
        <div class="content">
            @yield('content')
        </div>
    </div>

<script>
    // =========================
    // TOGGLE SIDEBAR
    // =========================
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });

    // =========================
    // PROFILE & NOTIF POPUP
    // =========================
    const profileBtn = document.querySelector('.profile-btn button');
    const bellBtn = document.querySelector('.bell-btn');

    // PROFILE POPUP
    let profilePopup = document.createElement('div');
    profilePopup.classList.add('popup');
    profilePopup.innerHTML = `
        <div class="profile-info">
            <i class="fas fa-user-circle text-3xl mr-2"></i>
            <div>
                <strong>{{ Auth::user()->name }}</strong>
                <div>{{ Auth::user()->email }}</div>
            </div>
        </div>
        <a href="{{ route('profile.edit') }}" class="block py-1">Edit Profile</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-left py-1">Logout</button>
        </form>
    `;
    profileBtn.parentElement.appendChild(profilePopup);

    // NOTIF POPUP
    let notifPopup = document.createElement('div');
    notifPopup.classList.add('popup');
    notifPopup.innerHTML = `
        <div class="notification-item">Notifikasi 1</div>
        <div class="notification-item">Notifikasi 2</div>
        <div class="notification-item">Notifikasi 3</div>
    `;
    bellBtn.parentElement.appendChild(notifPopup);

    // Toggle profile popup
    profileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        profilePopup.style.display = profilePopup.style.display === 'block' ? 'none' : 'block';
        notifPopup.style.display = 'none';
    });

    // Toggle notif popup
    bellBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        notifPopup.style.display = notifPopup.style.display === 'block' ? 'none' : 'block';
        profilePopup.style.display = 'none';
    });

    // Tutup popup kalau klik di luar
    document.addEventListener('click', () => {
        profilePopup.style.display = 'none';
        notifPopup.style.display = 'none';
    });
</script>

</body>
</html>
