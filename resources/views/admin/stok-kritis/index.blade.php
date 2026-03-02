@extends('layouts.main')

@section('title', 'Rekapitulasi Stok Kritis')
@section('page', 'Stok Kritis')

@push('css')
    {{-- Memastikan file CSS custom tetap terpanggil --}}
    <link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            {{-- REVISI: Menggunakan class text-dark agar otomatis jadi putih di mode gelap --}}
            <h3 class="fw-bold text-dark mb-1">
                <i class="fas fa-exclamation-triangle text-danger mr-2"></i> Rekapitulasi Stok Kritis
            </h3>
            <p class="text-muted">Pantau bahan baku yang perlu segera di-restock agar operasional lancar.</p>
        </div>
        <div class="col-md-4 text-md-right text-left mt-3 mt-md-0">
            {{-- REVISI: Mengganti bg-white statis dengan class 'card' p-2 agar warna background dinamis --}}
            <div class="card d-inline-block border-0 shadow-sm rounded-pill px-4 py-2 mb-0">
                <div class="card-body p-0 d-flex align-items-center">
                    <i class="fas fa-bell text-danger mr-2"></i>
                    <span class="font-weight-bold text-dark">{{ $stokKritis->count() }} Item Kritis</span>
                </div>
            </div>
        </div>
    </div>

    {{-- CARD TABEL --}}
    <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    {{-- REVISI: Menghapus bg-light statis agar header tabel tidak kontras --}}
                    <thead>
                        <tr>
                            <th class="text-center py-4 text-muted border-0" width="60">NO</th>
                            <th class="py-4 text-muted border-0" width="20%">OUTLET</th>
                            <th class="py-4 text-muted border-0" width="25%">BAHAN BAKU</th>
                            <th class="text-center py-4 text-muted border-0">SISA STOK</th>
                            <th class="py-4 text-muted border-0 text-center">STATUS</th>
                            <th class="text-right px-4 py-4 text-muted border-0">TINDAKAN CEPAT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokKritis as $s)
                        <tr class="{{ $s->stok == 0 ? 'row-critical' : 'row-warning' }}">
                            {{-- KOLOM NO --}}
                            <td class="text-center">
                                {{-- REVISI: Menyesuaikan no-badge agar tidak terlalu terang --}}
                                <div class="no-badge mx-auto shadow-sm border-0 bg-soft-secondary text-dark font-weight-bold">
                                    {{ $loop->iteration }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- REVISI: Warna inisial outlet --}}
                                    <div class="mr-3 icon-outlet-placeholder">
                                        {{ substr($s->outlet->nama_outlet, 0, 1) }}
                                    </div>
                                    <span class="font-weight-bold text-dark">{{ $s->outlet->nama_outlet }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $s->bahan->nama_bahan }}</div>
                                <small class="text-muted text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">
                                    {{ $s->bahan->kategori->nama_kategori ?? 'UMUM' }}
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $s->stok == 0 ? 'badge-danger pulse-danger' : 'badge-warning' }} px-3 py-2 shadow-sm" style="font-size: 0.9rem; border-radius: 10px; font-weight: 800;">
                                    {{ $s->stok }} <small>{{ $s->bahan->satuan }}</small>
                                </span>
                            </td>
                            <td class="text-center">
                                @if($s->stok == 0)
                                    <span class="status-pill-custom border-danger text-danger">
                                        <i class="fas fa-times-circle mr-1"></i> Habis Total
                                    </span>
                                @else
                                    <span class="status-pill-custom border-warning text-warning">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Hampir Habis
                                    </span>
                                @endif
                            </td>
                            <td class="text-right px-4">
                                <a href="{{ route('admin.distribusi.create', ['outlet_id' => $s->outlet_id, 'bahan_id' => $s->bahan_id]) }}" 
                                   class="btn btn-success btn-sm rounded-pill px-3 font-weight-bold shadow-sm">
                                    <i class="fas fa-shipping-fast mr-2"></i> Kirim Stok
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center">
                                <div class="py-5">
                                    <i class="fas fa-shield-check fa-4x text-success mb-3 opacity-25"></i>
                                    <h5 class="text-muted fw-light">Sempurna! Semua stok di outlet dalam kondisi aman.</h5>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- REVISI: Footer mengikuti tema --}}
        <div class="card-footer bg-transparent border-top py-4 px-4 text-center">
            <span class="text-muted small italic">
                <i class="fas fa-info-circle mr-1 text-success"></i> 
                Klik tombol <strong>Kirim Stok</strong> untuk diarahkan ke halaman pembuatan distribusi otomatis.
            </span>
        </div>
    </div>
</div>

<style>
    /* Styling Dasar & Mode Gelap */
    .no-badge {
        width: 30px;
        height: 30px;
        line-height: 30px;
        border-radius: 8px;
        font-size: 0.8rem;
    }

    .bg-soft-secondary { background-color: rgba(108, 117, 125, 0.1); }

    .icon-outlet-placeholder {
        width: 35px; 
        height: 35px; 
        background: rgba(71, 85, 105, 0.1); 
        border-radius: 8px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-weight: 800; 
        color: #475569;
    }

    .dark-mode .icon-outlet-placeholder {
        background: rgba(255, 255, 255, 0.1);
        color: #cbd5e1;
    }

    /* Status Pill Custom */
    .status-pill-custom {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        border: 1px solid;
        background: transparent;
    }

    /* Row Backgrounds */
    .row-critical { background-color: rgba(220, 53, 69, 0.02); }
    .row-warning { background-color: rgba(255, 193, 7, 0.01); }

    .dark-mode .row-critical { background-color: rgba(220, 53, 69, 0.05); }
    .dark-mode .row-warning { background-color: rgba(255, 193, 7, 0.03); }

    /* Pulse Animation */
    .pulse-danger {
        animation: pulse-red 2s infinite;
    }

    @keyframes pulse-red {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }

    /* Hover Effect */
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.03) !important;
    }
    .dark-mode .table-hover tbody tr:hover {
        background-color: rgba(255,255,255,0.03) !important;
    }
</style>
@endsection