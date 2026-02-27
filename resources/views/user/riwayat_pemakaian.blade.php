@extends('layouts.main')

@section('title', 'Riwayat Pemakaian Lengkap')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1" style="letter-spacing: -1px;">Arsip Pemakaian</h2>
            <p class="text-muted mb-0">Daftar lengkap riwayat penggunaan bahan baku outlet.</p>
        </div>
        <a href="{{ route('user.dashboard') }}" class="btn btn-light border-0 shadow-sm px-4" style="border-radius: 12px; font-weight: 600;">
            <i class="fas fa-arrow-left mr-2 text-primary"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-3">
            <form action="{{ route('user.riwayat_pemakaian') }}" method="GET" class="row align-items-center">
                <div class="col-md-10">
                    <div class="input-group-modern">
                        <div class="input-icon"><i class="fas fa-search"></i></div>
                        <input type="text" name="search" class="form-control input-modern border-0" 
                               style="background-color: #f8f9fc;"
                               placeholder="Cari nama bahan (contoh: Gula Aren, Bubuk Matcha...)" 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 mt-2 mt-md-0">
                    <button type="submit" class="btn btn-primary-modern w-100 py-3">
                        Cari Arsip
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
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
                        <tr class="table-row-modern border-bottom">
                            <td class="pl-4">
                                <span class="text-muted font-weight-bold" style="font-size: 0.9rem;">
                                    {{ str_pad(($riwayat->currentPage() - 1) * $riwayat->perPage() + $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            <td class="py-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-icon mr-3 bg-soft-success text-success" style="width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem;">
                                        {{ strtoupper(substr($item->bahan->nama_bahan ?? 'B', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark mb-0" style="font-size: 1rem;">
                                            {{ $item->bahan->nama_bahan ?? '-' }}
                                        </div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">ID Transaksi: #TRX-{{ $item->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="usage-bubble-small">
                                    <span class="amount text-danger font-weight-bold">-{{ number_format($item->jumlah, 0, ',', '.') }}</span>
                                    <span class="unit text-muted small text-uppercase" style="font-size: 0.7rem;">{{ $item->bahan->satuan ?? 'unit' }}</span>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="text-dark font-weight-bold mb-0">
                                    {{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y') }}
                                </div>
                                <small class="text-primary font-weight-bold">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Jakarta')->locale('id')->diffForHumans() }}
                                </small>
                            </td>

                            <td class="text-center">
                                <span class="badge-custom bg-soft-success text-success border border-success">
                                    <span class="dot bg-success"></span> Terverifikasi
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center">
                                <img src="https://illustrations.popsy.co/gray/searching.svg" alt="empty" style="width: 130px;" class="mb-3 opacity-50">
                                <h5 class="text-muted font-weight-bold">Tidak Menemukan Data</h5>
                                <p class="small text-muted">Belum ada riwayat pemakaian atau kata kunci tidak cocok.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white border-top-0 py-4 px-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <p class="text-muted small mb-3 mb-md-0">
                    Menampilkan <strong>{{ $riwayat->firstItem() ?? 0 }}</strong> - <strong>{{ $riwayat->lastItem() ?? 0 }}</strong> dari <strong>{{ $riwayat->total() }}</strong> arsip
                </p>
                <div class="pagination-modern">
                    {{ $riwayat->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection