@extends('layouts.main')

@section('title', 'Ringkasan Bulanan Outlet')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER SECTION --}}
    <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="bg-warning p-4 d-flex justify-content-between align-items-center text-dark">
                <div>
                    <h3 class="fw-bold mb-1"><i class="fas fa-chart-line me-2"></i>Ringkasan Bulanan</h3>
                    <p class="mb-0 opacity-75">Performa operasional outlet: <strong>{{ auth()->user()->outlet->nama_outlet }}</strong></p>
                </div>
                <div class="text-end">
                    <a href="{{ route('user.laporan.ringkasan.pdf') }}" class="btn btn-dark shadow-sm px-4">
                        <i class="fas fa-file-pdf me-2"></i>Cetak PDF
                    </a>
                </div>
            </div>
            <div class="bg-white px-4 py-2 d-flex justify-content-between align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 bg-transparent p-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('user.laporan.index') }}" class="text-warning text-decoration-none">Laporan</a></li>
                        <li class="breadcrumb-item active">Ringkasan Bulanan</li>
                    </ol>
                </nav>
                <div class="text-muted small">
                    Periode: <strong>{{ now()->translatedFormat('F Y') }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="row g-4 mb-4 text-center">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 py-2" style="border-radius: 12px; border-bottom: 4px solid #28a745 !important;">
                <div class="card-body">
                    <div class="text-success mb-2"><i class="fas fa-boxes fa-2x"></i></div>
                    <h6 class="text-muted mb-1 small text-uppercase fw-bold">Total Item Stok</h6>
                    <h2 class="fw-bold mb-0">{{ $totalStok }}</h2>
                    <small class="text-muted">Item terdaftar</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 py-2" style="border-radius: 12px; border-bottom: 4px solid #007bff !important;">
                <div class="card-body">
                    <div class="text-primary mb-2"><i class="fas fa-truck-loading fa-2x"></i></div>
                    <h6 class="text-muted mb-1 small text-uppercase fw-bold">Total Distribusi</h6>
                    <h2 class="fw-bold mb-0">{{ $totalDistribusi }}</h2>
                    <small class="text-muted">Transaksi bulan ini</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 py-2" style="border-radius: 12px; border-bottom: 4px solid #dc3545 !important;">
                <div class="card-body">
                    <div class="text-danger mb-2"><i class="fas fa-exclamation-triangle fa-2x"></i></div>
                    <h6 class="text-muted mb-1 small text-uppercase fw-bold">Stok Menipis</h6>
                    <h2 class="fw-bold mb-0">{{ $stokMenipis }}</h2>
                    <small class="text-muted">Perlu restock segera</small>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT LOG TABLE --}}
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold"><i class="fas fa-list text-warning me-2"></i>Detail Distribusi Terkini</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Tanggal</th>
                            <th>Bahan Baku</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($distribusi as $key => $item)
                        <tr>
                            <td class="ps-4">{{ $key+1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td class="fw-medium text-dark">{{ $item->bahan->nama_bahan }}</td>
                            <td class="text-center"><span class="badge bg-info-subtle text-info px-3">{{ $item->jumlah }}</span></td>
                            <td class="text-end pe-4">
                                <span class="badge bg-success text-white small">Diterima</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted small">Belum ada riwayat distribusi bulan ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-center py-3">
            <p class="text-muted small mb-0 font-italic">
                <i class="fas fa-sync-alt me-1"></i> Data diperbarui secara real-time berdasarkan sistem log pusat.
            </p>
        </div>
    </div>
</div>
@endsection