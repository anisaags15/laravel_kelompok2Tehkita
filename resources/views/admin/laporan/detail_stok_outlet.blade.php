@extends('layouts.main')

@section('title', 'Executive Inventory Detail - Teh Kita')
@section('page', 'Laporan Detail Stok')

@push('css')
<link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}">
<style>
    .btn-kembali-premium {
        background: #f8f9fa;
        color: #334155;
        border: 2px solid #e2e8f0;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-kembali-premium:hover {
        background: #ffffff;
        border-color: #22c55e;
        color: #22c55e;
        transform: translateX(-5px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
    }
    .log-container-simple {
        background: white;
        padding: 8px 15px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="detail-container container-fluid py-4 px-lg-5">

    {{-- SECTION 1: HEADER --}}
    <div class="header-prestige d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <h2 class="font-weight-bold text-dark mb-1" style="letter-spacing: -0.5px;">Laporan Stok Seluruh Outlet</h2>
            <p class="text-muted mb-0 small uppercase font-weight-bold" style="letter-spacing: 1px;">
                <i class="fas fa-fingerprint text-success mr-2"></i> Real-time Database Access Verified
            </p>
        </div>
        <a href="{{ route('admin.laporan.stok-outlet') }}" class="btn-kembali-premium shadow-sm">
            <i class="fas fa-arrow-left mr-3"></i> KEMBALI KE DAFTAR
        </a>
    </div>

    {{-- SECTION 2: OUTLET PROFILE CARD --}}
    <div class="card info-card-prestige mb-5 shadow-sm border-0" style="border-radius: 24px;">
        <div class="card-body p-5">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="outlet-icon-diamond" style="background: linear-gradient(135deg, #22c55e 0%, #15803d 100%); color: white; width: 80px; height: 80px; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        <i class="fas fa-store-alt"></i>
                    </div>
                </div>
                <div class="col pl-lg-4">
                    <span class="badge badge-soft-success px-3 py-2 mb-3" style="border-radius: 8px; font-weight: 700; font-size: 0.7rem;">TITIK DISTRIBUSI AKTIF</span>
                    <h1 class="font-weight-bold text-dark mb-2" style="font-size: 2.2rem;">{{ $outlet->nama_outlet }}</h1>
                    <p class="text-muted mb-0 font-weight-medium">
                        <i class="fas fa-map-marked-alt text-danger mr-2"></i>
                        {{ $outlet->alamat ?? 'Lokasi spesifik belum terdaftar di sistem GIS pusat.' }}
                    </p>
                </div>
                <div class="col-md-3 text-md-right mt-4 mt-md-0 border-left">
                    <div class="pl-md-4">
                        <small class="text-muted d-block font-weight-bold text-uppercase mb-2" style="letter-spacing: 1px;">Kapasitas Inventaris</small>
                        <h2 class="font-weight-bold text-dark mb-0">{{ $stok->count() }} <span class="h5 text-muted font-weight-light">Bahan</span></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 3: DATA TABLE --}}
    <div class="card card-table-elite overflow-hidden shadow-lg border-0" style="border-radius: 24px;">
        <div class="card-header bg-white py-4 px-5 border-0 d-flex justify-content-between align-items-center">
            <h5 class="font-weight-bold text-dark mb-0">
                <i class="fas fa-stream mr-3 text-success"></i>Daftar Persediaan Bahan Baku
            </h5>
            <div class="pulse-status d-flex align-items-center">
                <div class="pulse-dot bg-success mr-2"></div>
                <small class="font-weight-bold text-success small">LIVE SYSTEM</small>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-elite mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center py-4" width="100">Index</th>
                            <th class="py-4">Deskripsi Material</th>
                            <th class="text-center py-4">Log Kedatangan Terakhir</th>
                            <th class="text-center py-4">Volume Tersedia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stok as $item)
                        <tr>
                            <td class="text-center">
                                <span class="text-muted font-weight-bold">#{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center py-2">
                                    <div class="bg-soft-success p-3 rounded-lg mr-3 shadow-sm" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-archive text-success fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark" style="font-size: 1.05rem;">{{ $item->bahan->nama_bahan }}</div>
                                        <small class="text-muted font-weight-bold uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ $item->bahan->kategori->nama_kategori ?? 'Bahan Dasar' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($item->tanggal_terakhir_diterima)
                                    <div class="log-container-simple shadow-sm">
                                        <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                                            <i class="far fa-calendar-alt text-primary mr-2"></i>
                                            {{ \Carbon\Carbon::parse($item->tanggal_terakhir_diterima)->translatedFormat('d F Y') }}
                                        </span>
                                    </div>
                                @else
                                    <span class="badge badge-light border px-3 py-2 text-muted" style="font-weight: 500;">BELUM ADA LOG</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="stock-visual {{ $item->stok <= 5 ? 'visual-warning' : 'visual-safe' }} shadow-sm mx-auto" style="width: fit-content; padding: 10px 20px; border-radius: 15px;">
                                    <span class="stock-number font-weight-bold" style="font-size: 1.2rem;">{{ $item->stok }}</span>
                                    <small class="text-uppercase ml-1" style="font-size: 0.7rem; font-weight: 700;">{{ $item->bahan->satuan }}</small>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-5 text-center">
                                <div class="opacity-25 mb-3">
                                    <i class="fas fa-box-open fa-5x text-muted"></i>
                                </div>
                                <h5 class="text-muted font-weight-light">Belum ada data inventaris untuk outlet ini.</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-light border-0 py-4 px-5">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small">
                    <span class="text-muted font-italic">Internal Document:</span> 
                    <strong class="text-dark ml-1">SYSTEM-TK-{{ date('Ymd') }}</strong>
                </div>
                <div class="text-muted small font-weight-bold uppercase" style="letter-spacing: 0.5px;">
                    <i class="fas fa-calendar-check mr-2"></i> Update Tanggal: {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection