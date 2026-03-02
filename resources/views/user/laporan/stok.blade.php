@extends('layouts.main')

@section('title', 'Laporan Stok Outlet')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">
                        <i class="fas fa-boxes text-success me-2"></i>Laporan Stok Outlet
                    </h3>
                    <p class="text-muted mb-0">
                        Monitoring inventaris bahan baku real-time di <strong>{{ auth()->user()->outlet->nama_outlet }}</strong>
                    </p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('user.laporan.stok.pdf') }}" class="btn btn-success shadow-sm px-4 py-2 hover-up">
                        <i class="fas fa-file-pdf me-2"></i>Export PDF Resmi
                    </a>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="card border-0 shadow-sm bg-white overflow-hidden h-100">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="rounded-circle bg-success-subtle p-3 me-3">
                                <i class="fas fa-clipboard-list text-success fa-2x"></i>
                            </div>
                            <div>
                                <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1">Total Katalog</small>
                                <h3 class="fw-bold mb-0 text-dark">{{ $stok->count() }} <span class="fs-6 fw-normal">Item</span></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="card border-0 shadow-sm bg-white border-start border-danger border-4 h-100">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="rounded-circle bg-danger-subtle p-3 me-3">
                                <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                            </div>
                            <div>
                                <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1">Perlu Re-order</small>
                                <h3 class="fw-bold mb-0 text-danger">{{ $stok->where('stok', '<=', 5)->count() }} <span class="fs-6 fw-normal">Bahan</span></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-success text-white h-100">
                        <div class="card-body p-4">
                            <small class="opacity-75 text-uppercase fw-bold ls-1 d-block mb-1 text-white">Status Gudang</small>
                            <h3 class="fw-bold mb-0">
                                @if($stok->where('stok', '<=', 5)->count() > 0)
                                    Peringatan Stok!
                                @else
                                    Semua Aman ✓
                                @endif
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0 text-dark">Daftar Inventaris Bahan</h5>
                        <span class="badge bg-light text-muted fw-normal px-3 py-2 border">
                            <i class="fas fa-sync-alt me-1 fa-spin"></i> Terakhir Diperbarui: {{ now()->format('H:i') }} WIB
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-uppercase fs-xs fw-bold text-muted" width="5%">No</th>
                                    <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Informasi Bahan Baku</th>
                                    <th class="py-3 text-uppercase fs-xs fw-bold text-muted text-center" width="20%">Indikator</th>
                                    <th class="pe-4 py-3 text-uppercase fs-xs fw-bold text-muted text-end" width="20%">Jumlah Tersedia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stok as $item)
                                <tr>
                                    <td class="ps-4">
                                        <span class="text-muted fw-medium">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-3 d-none d-sm-block">
                                                <i class="fas fa-box text-success"></i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark fs-6">{{ $item->bahan->nama_bahan }}</span>
                                                <small class="text-muted">SKU: #BHN-{{ str_pad($item->bahan->id, 4, '0', STR_PAD_LEFT) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($item->stok <= 5)
                                            <span class="badge bg-danger-subtle text-danger px-3 py-2 border border-danger">
                                                <i class="fas fa-arrow-down me-1"></i> Stok Kritis
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle text-success px-3 py-2 border border-success">
                                                <i class="fas fa-check-circle me-1"></i> Stok Aman
                                            </span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        <h5 class="fw-bold mb-0 {{ $item->stok <= 5 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($item->stok, 0, ',', '.') }}
                                            <small class="text-muted fw-normal ms-1 fs-6">{{ $item->bahan->satuan ?? 'Unit' }}</small>
                                        </h5>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-5 text-center">
                                        <div class="opacity-50 mb-3">
                                            <i class="fas fa-folder-open fa-4x text-muted"></i>
                                        </div>
                                        <h5 class="text-muted fw-normal">Belum ada data stok yang tercatat di sistem.</h5>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top py-3 text-center">
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle me-1"></i> 
                        Pastikan melakukan pengecekan fisik barang setiap pergantian shift untuk akurasi data.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Utility Styles untuk tampilan lebih bersih */
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }
    .ls-1 { letter-spacing: 0.5px; }
    .fs-xs { font-size: 0.75rem; }
    .hover-up { transition: all 0.3s ease; }
    .hover-up:hover { transform: translateY(-3px); }
    .table-hover tbody tr:hover { background-color: #f8fafc; }
</style>
@endsection