<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Tailwind (optional, untuk cards keren) --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Content --}}
    <div class="content">
        @yield('content')
    </div>

</body>
</html>
