@extends('layouts.main')

@section('title', 'Riwayat Pemakaian Lengkap')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER (Tetap Sama) --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1" style="letter-spacing: -1px;">Arsip Pemakaian</h2>
            <p class="text-muted mb-0">Daftar lengkap riwayat penggunaan bahan baku outlet.</p>
        </div>
        <a href="{{ route('user.dashboard') }}" class="btn btn-light border-0 shadow-sm px-4" style="border-radius: 12px; font-weight: 600;">
            <i class="fas fa-arrow-left mr-2 text-primary"></i> Kembali
        </a>
    </div>

    {{-- FILTER SEARCH --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-3">
            <form action="{{ route('user.riwayat_pemakaian') }}" method="GET" class="row align-items-center">
                <div class="col-md-10">
                    <div class="input-group-modern position-relative">
                        <i class="fas fa-search position-absolute" style="left: 15px; top: 18px; color: #a1a1a1; z-index: 5;"></i>
                        <input type="text" name="search" class="form-control pl-5 py-4 border-0" 
                               style="background-color: #f8f9fc; border-radius: 12px;"
                               placeholder="Cari nama bahan (contoh: Gula Aren...)" 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 mt-2 mt-md-0">
                    <button type="submit" class="btn btn-primary w-100 py-3 shadow-sm font-weight-bold" style="border-radius: 12px; background: #4e73df; border: none;">
                        Cari Arsip
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted" style="background-color: #fcfcfd; border-bottom: 1px solid #f1f1f4;">
                            <th class="py-4 pl-4 font-weight-bold text-uppercase small" width="80">No</th>
                            <th class="py-4 font-weight-bold text-uppercase small">Detail Bahan Baku</th>
                            <th class="py-4 font-weight-bold text-uppercase small text-center">Jumlah Keluar</th>
                            <th class="py-4 font-weight-bold text-uppercase small text-center">Waktu Transaksi</th>
                            <th class="py-4 font-weight-bold text-uppercase small text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $item)
                        <tr class="table-row-modern">
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
                                        <div class="font-weight-bold text-dark mb-0">{{ $item->bahan->nama_bahan ?? '-' }}</div>
                                        <div class="text-muted small" style="font-size: 0.7rem;">ID Transaksi: #TRX-{{ $item->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="usage-bubble shadow-sm">
                                    <span class="amount text-danger">-{{ number_format($item->jumlah, 0, ',', '.') }}</span>
                                    <span class="unit text-muted small">{{ $item->bahan->satuan ?? 'unit' }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="text-dark font-weight-bold mb-0">
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
                        {{-- Empty State (Sama) --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- PAGINATION FIX --}}
        <div class="card-footer bg-white border-top-0 py-4 px-4">
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
    /* Styling Dasar agar senada dengan Menu Distribusi */
    .bg-soft-success { background-color: rgba(28, 200, 138, 0.1); }
    
    .avatar-icon {
        width: 42px; 
        height: 42px; 
        border-radius: 12px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-weight: bold;
    }

    .usage-bubble {
        background: #fffafa;
        border: 1px solid #ffebeb;
        padding: 5px 12px;
        border-radius: 10px;
        display: inline-block;
    }

    .usage-bubble .amount { font-weight: 800; font-size: 1rem; }

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

    .table-row-modern:hover {
        background-color: #f8f9fc !important;
        transition: 0.2s;
    }

    /* FIX PAGINATION OVERFLOW */
    .pagination-container .pagination {
        margin-bottom: 0;
        gap: 5px;
    }

    .pagination-container .page-item .page-link {
        border-radius: 8px !important;
        border: none;
        background: #f8f9fc;
        color: #4e73df;
        font-weight: 600;
        padding: 8px 14px;
        transition: 0.3s;
    }

    .pagination-container .page-item.active .page-link {
        background: #4e73df;
        color: white;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
    }

    .pagination-container .page-link:hover {
        background: #e2e6ea;
    }
</style>
@endsection