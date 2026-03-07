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
    <div class="tea-blob blob-1"></div>
    <div class="tea-blob blob-2"></div>

    <div class="auth-card">
        <div class="brand-top">
            <div class="brand-logo">
                <i class="fas fa-leaf"></i>
            </div>
            <span class="brand-wordmark">Teh Kita</span>
        </div>
        
        <div class="auth-content">
            {{ $slot }}
        </div>
    </div>
</body>
</html>