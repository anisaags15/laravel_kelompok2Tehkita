<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teh Kita</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('templates/dist/css/auth-style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <i class="fas fa-leaf leaf"></i><i class="fas fa-leaf leaf"></i>
    <i class="fas fa-leaf leaf"></i><i class="fas fa-leaf leaf"></i>
    <i class="fas fa-leaf leaf"></i>

    <div class="auth-card">
        <div class="brand-top">
            <div class="brand-logo"><i class="fas fa-leaf"></i></div>
            <span class="brand-wordmark">Teh Kita</span>
            <p style="font-size: 8px; color: #2d6a4f; font-weight: 700; letter-spacing: 1px; margin-bottom: 10px;">INVENTORY SYSTEM</p>
        </div>
        {{ $slot }}
    </div>
</body>
</html>