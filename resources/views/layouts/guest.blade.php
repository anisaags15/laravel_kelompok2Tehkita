<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Teh Kita') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('templates/dist/css/auth.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    {{-- KIRI: Branding --}}
    <div class="auth-left">
        <div class="brand-top">
            <div class="brand-logo"><i class="fas fa-leaf"></i></div>
            <span class="brand-wordmark">Teh Kita</span>
        </div>

        <div>
            <h1 class="brand-tagline">
                Distribusi<br>
                Lebih<br>
                <em>Efisien.</em>
            </h1>
            <p class="brand-desc">
                Platform manajemen stok & distribusi terpadu untuk seluruh jaringan outlet Teh Kita.
            </p>

            <ul class="feature-list">
                <li><i class="fas fa-chart-line"></i> Monitoring stok real-time</li>
                <li><i class="fas fa-truck"></i> Manajemen distribusi mudah</li>
                <li><i class="fas fa-bell"></i> Alert stok kritis otomatis</li>
                <li><i class="fas fa-file-alt"></i> Laporan PDF lengkap</li>
            </ul>
        </div>
    </div>

    {{-- KANAN: Form --}}
    <div class="auth-right">
        <div class="auth-form-wrapper">
            {{ $slot }}
        </div>
    </div>

</body>
</html>