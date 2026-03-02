@extends('layouts.main')

@section('title', 'Pusat Laporan Outlet')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER DENGAN GRADIENT BACKGROUND --}}
    <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="bg-success p-4 d-flex justify-content-between align-items-center text-white">
                <div>
                    <h3 class="fw-bold mb-1">Pusat Laporan Outlet</h3>
                    <p class="mb-0 opacity-75">Kelola, pantau, dan unduh data operasional secara efisien.</p>
                </div>
                <div class="d-none d-md-block">
                    <i class="fas fa-file-invoice fa-3x opacity-25"></i>
                </div>
            </div>
            <div class="bg-white px-4 py-2 d-flex justify-content-between align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 bg-transparent p-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-success text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Laporan</li>
                    </ol>
                </nav>
                <div class="text-muted small fw-medium">
                    <i class="far fa-calendar-alt me-1 text-success"></i> {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>
        </div>
    </div>

    {{-- GRID MENU LAPORAN --}}
    <div class="row g-4 justify-content-center">

        {{-- LAPORAN STOK --}}
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('user.laporan.stok') }}" class="text-decoration-none group-card">
                <div class="card h-100 border-0 shadow-sm laporan-card overflow-hidden">
                    <div class="card-body p-4 position-relative">
                        <div class="icon-box bg-success-subtle mb-4">
                            <i class="fas fa-boxes-stacked fa-2x text-success"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Laporan Stok Outlet</h5>
                        <p class="text-muted small">Monitor sisa bahan baku, peringatan stok kritis, dan status inventaris terkini secara akurat.</p>
                        <hr class="opacity-10">
                        <div class="d-flex align-items-center text-success fw-bold small">
                            Lihat Detail <i class="fas fa-arrow-right ms-2 transition-icon"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- LAPORAN DISTRIBUSI --}}
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('user.laporan.distribusi') }}" class="text-decoration-none group-card">
                <div class="card h-100 border-0 shadow-sm laporan-card overflow-hidden">
                    <div class="card-body p-4">
                        <div class="icon-box bg-primary-subtle mb-4">
                            <i class="fas fa-shipping-fast fa-2x text-primary"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Laporan Distribusi</h5>
                        <p class="text-muted small">Lacak semua riwayat pengiriman bahan dari pusat yang masuk ke outlet Anda secara berkala.</p>
                        <hr class="opacity-10">
                        <div class="d-flex align-items-center text-primary fw-bold small">
                            Lihat Detail <i class="fas fa-arrow-right ms-2 transition-icon"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- RINGKASAN BULANAN --}}
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('user.laporan.ringkasan') }}" class="text-decoration-none group-card">
                <div class="card h-100 border-0 shadow-sm laporan-card overflow-hidden border-start border-warning border-4">
                    <div class="card-body p-4">
                        <div class="icon-box bg-warning-subtle mb-4">
                            <i class="fas fa-chart-pie fa-2x text-warning"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Ringkasan Bulanan</h5>
                        <p class="text-muted small">Analisis performa bulanan melalui rekapitulasi penggunaan dan sisa stok dalam satu periode.</p>
                        <hr class="opacity-10">
                        <div class="d-flex align-items-center text-warning fw-bold small">
                            Lihat Detail <i class="fas fa-arrow-right ms-2 transition-icon"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

{{-- LAPORAN WASTE --}}
<div class="col-md-6 col-xl-4">
    <a href="{{ route('user.laporan.waste') }}" class="text-decoration-none group-card">
        <div class="card h-100 border-0 shadow-sm laporan-card overflow-hidden">
            <div class="card-body p-4">
                <div class="icon-box bg-danger-subtle mb-4">
                    <i class="fas fa-trash-alt fa-2x text-danger"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Laporan Waste</h5>
                <p class="text-muted small">Rekapitulasi bahan baku yang rusak, tumpah, atau expired untuk evaluasi kerugian outlet.</p>
                <hr class="opacity-10">
                <div class="d-flex align-items-center text-danger fw-bold small">
                    Lihat Detail <i class="fas fa-arrow-right ms-2 transition-icon"></i>
                </div>
            </div>
        </div>
    </a>
</div>

    {{-- INFO FOOTER --}}
    <div class="text-center mt-5">
        <p class="text-muted small">
            <i class="fas fa-info-circle me-1"></i> Butuh bantuan dengan laporan? Hubungi <a href="{{ route('chat.index') }}" class="text-success text-decoration-none fw-bold">Pusat Bantuan</a>
        </p>
    </div>

</div>

<style>
    /* CSS KUSTOM UNTUK TAMPILAN PREMIUM */
    .laporan-card {
        border-radius: 15px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #ffffff;
    }

    .icon-box {
        width: 65px;
        height: 65px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        transition: transform 0.3s ease;
    }

    .laporan-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }

    .laporan-card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .transition-icon {
        transition: transform 0.3s ease;
    }

    .laporan-card:hover .transition-icon {
        transform: translateX(5px);
    }

    .bg-success-subtle { background-color: #e8f5e9; }
    .bg-primary-subtle { background-color: #e3f2fd; }
    .bg-warning-subtle { background-color: #fff8e1; }

    .group-card:hover h5 {
        color: #28a745 !important;
    }

    /* Khusus Laporan Bulanan Hover */
    .col-md-6:last-child .group-card:hover h5 {
        color: #ffc107 !important;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .icon-box { width: 50px; height: 50px; }
        .icon-box i { font-size: 1.5rem; }
    }
</style>
@endsection