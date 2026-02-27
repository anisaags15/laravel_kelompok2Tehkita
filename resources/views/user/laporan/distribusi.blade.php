@extends('layouts.main')

@section('title', 'Laporan Distribusi Outlet')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Laporan Distribusi</h3>
                    <p class="text-muted mb-0">
                        <i class="fas fa-truck-loading me-2 text-success"></i>{{ auth()->user()->outlet->nama_outlet }}
                    </p>
                </div>
                <div class="btn-group shadow-sm">
                    <a href="{{ route('user.laporan.distribusi.pdf') }}" class="btn btn-success px-4">
                        <i class="fas fa-file-pdf me-2"></i>Cetak PDF
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="m-0 fw-bold text-success">
                                <i class="fas fa-list me-2"></i>Riwayat Distribusi Bahan
                            </h6>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-uppercase fs-xs fw-bold text-muted" width="5%">No</th>
                                    <th class="py-3 text-uppercase fs-xs fw-bold text-muted" width="20%">Tanggal</th>
                                    <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Nama Bahan</th>
                                    <th class="pe-4 py-3 text-uppercase fs-xs fw-bold text-muted text-end" width="20%">Jumlah Distribusi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($distribusi as $item)
                                <tr>
                                    <td class="ps-4">
                                        <span class="text-muted small">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-shape bg-success-subtle text-success rounded me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                <i class="far fa-calendar-check"></i>
                                            </div>
                                            <span class="fw-medium text-dark">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->bahan->nama_bahan }}</div>
                                        <small class="text-muted text-uppercase" style="font-size: 0.7rem;">ID: #{{ $item->bahan->id }}</small>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-inline-block px-3 py-1 rounded-pill bg-success-subtle text-success fw-bold">
                                            {{ number_format($item->jumlah, 0, ',', '.') }} 
                                            <small class="fw-normal">{{ $item->bahan->satuan ?? 'Unit' }}</small>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-5 text-center">
                                        <div class="py-4">
                                            <i class="fas fa-inbox fa-3x text-light mb-3"></i>
                                            <p class="text-muted">Tidak ada data distribusi yang tercatat.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-light border-top-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Menampilkan <strong>{{ $distribusi->count() }}</strong> entri distribusi</small>
                        <small class="text-muted small">
                            <i class="fas fa-sync-alt me-1"></i> Terakhir sinkron: {{ now()->format('H:i') }}
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection