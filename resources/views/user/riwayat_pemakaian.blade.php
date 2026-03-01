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
        <div class="card-body p-0">
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
                                style="border-radius: 0; background: #4e73df; border: none; min-height: 60px; display: flex; align-items: center; justify-content: center; white-space: nowrap;">
                            <span class="d-none d-md-inline">Cari Arsip</span>
                            <i class="fas fa-search d-md-none"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card shadow-lg border-0 bg-adaptive" style="border-radius: 20px; overflow: hidden;">
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
                                    <span class="unit text-muted small ml-1">{{ $item->bahan->satuan ?? 'unit' }}</span>
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
@endsection