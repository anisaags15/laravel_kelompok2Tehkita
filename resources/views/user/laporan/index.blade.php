@extends('layouts.main')

@section('title', 'Laporan Outlet')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-semibold mb-1">Laporan Outlet</h4>
                <small class="text-muted">
                    Akses laporan operasional dan ringkasan data outlet
                </small>
            </div>
            <div class="text-muted small">
                {{ now()->format('d F Y') }}
            </div>
        </div>
    </div>

    {{-- MENU LAPORAN --}}
    <div class="row g-4">

        {{-- LAPORAN STOK --}}
        <div class="col-md-4">
            <a href="{{ route('user.laporan.stok') }}" class="text-decoration-none">
                <div class="card h-100 border shadow-sm laporan-card">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-box fa-2x text-success mb-3"></i>
                        <h6 class="fw-semibold text-dark mb-2">
                            Laporan Stok Outlet
                        </h6>
                        <p class="text-muted small mb-0">
                            Informasi ketersediaan stok bahan pada outlet.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        {{-- LAPORAN DISTRIBUSI --}}
        <div class="col-md-4">
            <a href="{{ route('user.laporan.distribusi') }}" class="text-decoration-none">
                <div class="card h-100 border shadow-sm laporan-card">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-truck fa-2x text-primary mb-3"></i>
                        <h6 class="fw-semibold text-dark mb-2">
                            Laporan Distribusi
                        </h6>
                        <p class="text-muted small mb-0">
                            Riwayat distribusi bahan yang diterima outlet.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        {{-- RINGKASAN BULANAN --}}
        <div class="col-md-4">
            <a href="{{ route('user.laporan.ringkasan') }}" class="text-decoration-none">
                <div class="card h-100 border shadow-sm laporan-card">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-chart-line fa-2x text-warning mb-3"></i>
                        <h6 class="fw-semibold text-dark mb-2">
                            Ringkasan Bulanan
                        </h6>
                        <p class="text-muted small mb-0">
                            Rekap total distribusi dan kondisi stok per bulan.
                        </p>
                    </div>
                </div>
            </a>
        </div>

    </div>

</div>

<style>
.laporan-card {
    border-radius: 10px;
    transition: all 0.25s ease-in-out;
}
.laporan-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
</style>
@endsection