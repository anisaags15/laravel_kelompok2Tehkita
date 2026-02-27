@extends('layouts.main')

@section('title', 'Laporan Stok Outlet')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Laporan Stok Outlet</h3>
                    <p class="text-muted mb-0">
                        <i class="fas fa-store-alt me-2"></i>{{ auth()->user()->outlet->nama_outlet }}
                    </p>
                </div>
                <div class="btn-group shadow-sm">
                    <a href="{{ route('user.laporan.stok.pdf') }}" class="btn btn-success px-4">
                        <i class="fas fa-file-pdf me-2"></i>Cetak PDF
                    </a>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-success text-white">
                        <div class="card-body p-3">
                            <small class="d-block opacity-75">Total Jenis Bahan</small>
                            <h4 class="fw-bold mb-0">{{ $stok->count() }} Item</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-uppercase fs-xs fw-bold text-muted" width="8%">No</th>
                                    <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Nama Bahan</th>
                                    <th class="py-3 text-uppercase fs-xs fw-bold text-muted text-center" width="25%">Status Stok</th>
                                    <th class="pe-4 py-3 text-uppercase fs-xs fw-bold text-muted text-end" width="15%">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stok as $item)
                                <tr>
                                    <td class="ps-4">
                                        <span class="text-muted fw-medium">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $item->bahan->nama_bahan }}</span>
                                            <small class="text-muted small">ID: #{{ $item->bahan->id }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($item->stok <= 5)
                                            <span class="badge rounded-pill bg-danger-subtle text-danger px-3">
                                                <i class="fas fa-exclamation-triangle me-1"></i> Stok Menipis
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-success-subtle text-success px-3">
                                                <i class="fas fa-check-circle me-1"></i> Aman
                                            </span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        <span class="fs-5 fw-bold {{ $item->stok <= 5 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($item->stok, 0, ',', '.') }}
                                        </span>
                                        <small class="text-muted ms-1">{{ $item->bahan->satuan ?? 'Unit' }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-5 text-center">
                                        <img src="https://illustrations.popsy.co/gray/data-analysis.svg" alt="Empty" style="width: 150px;" class="mb-3">
                                        <p class="text-muted mb-0">Belum ada data stok yang tersedia.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 py-3 text-center">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Data diperbarui otomatis: {{ now()->translatedFormat('d F Y, H:i') }}
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection