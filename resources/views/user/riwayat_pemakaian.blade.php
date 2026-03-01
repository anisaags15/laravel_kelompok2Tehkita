@extends('layouts.main')

@section('title', 'Riwayat Pemakaian Lengkap')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER SECTION --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="font-weight-bold text-adaptive mb-1" style="letter-spacing: -1px;">Arsip Pemakaian</h2>
            <p class="text-muted mb-0">Daftar lengkap riwayat penggunaan bahan baku outlet.</p>
        </div>
        <a href="{{ route('user.dashboard') }}" class="btn btn-adaptive-secondary shadow-sm px-4" style="border-radius: 12px; font-weight: 600;">
            <i class="fas fa-arrow-left mr-2 text-primary"></i> Kembali
        </a>
    </div>
{{-- FILTER SEARCH --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; background: transparent;">
    <div class="card-body p-0"> {{-- P-0 agar tidak ada double padding --}}
        <form action="{{ route('user.riwayat_pemakaian') }}" method="GET">
            <div class="row no-gutters shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <div class="col-md-10 col-9">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute" style="left: 20px; top: 50%; transform: translateY(-50%); color: #a1a1a1; z-index: 5;"></i>
                        <input type="text" name="search" class="form-control border-0 input-adaptive" 
                               style="height: 60px; padding-left: 55px; border-radius: 0;"
                               placeholder="Cari nama bahan (contoh: Gula Aren...)" 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 col-3">
                    <button type="submit" class="btn btn-primary w-100 h-100 shadow-none font-weight-bold" 
                            style="border-radius: 0; background: #4e73df; border: none; min-height: 60px;">
                        <span class="d-none d-md-inline">Cari Arsip</span>
                        <i class="fas fa-search d-md-none"></i> {{-- Muncul icon saja di mobile --}}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

    {{-- TABLE CARD --}}
    <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-adaptive">
                    <thead>
                        <tr class="table-header-adaptive">
                            <th class="py-4 pl-4 font-weight-bold text-uppercase small" width="80">No</th>
                            <th class="py-4 font-weight-bold text-uppercase small">Detail Bahan Baku</th>
                            <th class="py-4 font-weight-bold text-uppercase small text-center">Jumlah Keluar</th>
                            <th class="py-4 font-weight-bold text-uppercase small text-center">Waktu Transaksi</th>
                            <th class="py-4 font-weight-bold text-uppercase small text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $item)
                        <tr class="table-row-modern border-adaptive">
                            <td class="pl-4">
                                <span class="text-muted font-weight-bold" style="font-size: 0.9rem;">
                                    {{ str_pad(($riwayat->currentPage() - 1) * $riwayat->perPage() + $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="py-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-icon mr-3 bg-soft-success text-success">
                                        {{ strtoupper(substr($item->bahan->nama_bahan ?? 'B', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-adaptive mb-0">{{ $item->bahan->nama_bahan ?? '-' }}</div>
                                        <div class="text-muted small" style="font-size: 0.7rem;">ID Transaksi: #TRX-{{ $item->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="usage-bubble-adaptive shadow-sm">
                                    <span class="amount text-danger">-{{ number_format($item->jumlah, 0, ',', '.') }}</span>
                                    <span class="unit text-muted small">{{ $item->bahan->satuan ?? 'unit' }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="text-adaptive font-weight-bold mb-0">
                                    {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                                </div>
                                <small class="text-primary font-weight-bold">
                                    {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->diffForHumans() }}
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge-status-success">
                                    <span class="dot"></span> Terverifikasi
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center">
                                <div class="opacity-25 mb-3">
                                    <i class="fas fa-history fa-4x text-muted"></i>
                                </div>
                                <h5 class="text-muted">Tidak ada riwayat ditemukan</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- PAGINATION --}}
        <div class="card-footer footer-adaptive py-4 px-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <p class="text-muted mb-3 mb-md-0" style="font-size: 0.85rem;">
                    Menampilkan <strong>{{ $riwayat->firstItem() ?? 0 }}</strong> - <strong>{{ $riwayat->lastItem() ?? 0 }}</strong> dari <strong>{{ $riwayat->total() }}</strong> arsip
                </p>
                <div class="pagination-container">
                    {{ $riwayat->appends(request()->input())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Tambahan Styling Spesifik Riwayat */
    .bg-soft-success { background-color: rgba(28, 200, 138, 0.1); }
    
    .avatar-icon {
        width: 42px; height: 42px; 
        border-radius: 12px; 
        display: flex; align-items: center; justify-content: center; 
        font-weight: bold;
    }

    /* BUBBLE ADAPTIVE */
    .usage-bubble-adaptive {
        background: #fffafa;
        border: 1px solid #ffebeb;
        padding: 5px 12px;
        border-radius: 10px;
        display: inline-block;
    }
    .dark-mode .usage-bubble-adaptive {
        background: rgba(231, 74, 59, 0.05); /* Soft Red Transparan */
        border: 1px solid rgba(231, 74, 59, 0.1);
    }
    .usage-bubble-adaptive .amount { font-weight: 800; font-size: 1rem; }

    /* BADGE STATUS */
    .badge-status-success {
        background: rgba(28, 200, 138, 0.1);
        color: #1cc88a;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid rgba(28, 200, 138, 0.3);
    }
    .badge-status-success .dot {
        height: 8px; width: 8px; background-color: #1cc88a;
        border-radius: 50%; display: inline-block; margin-right: 5px;
    }

    /* INPUT ADAPTIVE */
    .input-adaptive {
        background-color: #f8f9fc;
        border-radius: 12px;
    }
    .dark-mode .input-adaptive {
        background-color: #2c3034 !important;
        color: #e0e0e0 !important;
    }

    /* PAGINATION STYLING */
    .pagination-container .pagination { margin-bottom: 0; gap: 5px; }
    .pagination-container .page-item .page-link {
        border-radius: 8px !important;
        border: none;
        background: #f8f9fc;
        color: #4e73df;
        font-weight: 600;
        padding: 8px 14px;
        transition: 0.3s;
    }
    .dark-mode .pagination-container .page-item .page-link {
        background: #2c3034;
        color: #aab2bd;
    }
    .pagination-container .page-item.active .page-link {
        background: #4e73df;
        color: white !important;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
    }
    <style>
    /* FIX TOMBOL GEPENG & TEKS VERTIKAL */
    .btn-primary {
        display: flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap; /* Mencegah teks turun ke bawah */
    }

    /* INPUT ADAPTIVE - Sesuai Screen Shot Dark Mode Kamu */
    .input-adaptive {
        background-color: #f8f9fc;
        color: #333;
        transition: all 0.3s;
    }
    .dark-mode .input-adaptive {
        background-color: #1e2125 !important;
        color: #e0e0e0 !important;
    }

    /* TABLE IMPROVEMENT */
    .table-header-adaptive {
        background-color: #fcfcfd;
    }
    .dark-mode .table-header-adaptive {
        background-color: #1a1d21;
        color: #aab2bd;
    }
    
    .border-adaptive {
        border-bottom: 1px solid #f1f1f4;
    }
    .dark-mode .border-adaptive {
        border-bottom: 1px solid #2d3238;
    }

    /* PAGINATION FIX */
    .pagination-container .page-link {
        border-radius: 10px !important;
        margin: 0 3px;
        border: none !important;
    }
    .dark-mode .pagination-container .page-item:not(.active) .page-link {
        background: #2c3034 !important;
        color: #aab2bd;
    }
</style>
</style>
@endsection