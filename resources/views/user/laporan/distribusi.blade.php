@extends('layouts.main')

@section('title', 'Laporan Distribusi Outlet')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            
            {{-- HEADER & ACTION --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Rekap Laporan Distribusi</h3>
                    <p class="text-muted mb-0">
                        <i class="fas fa-file-invoice me-2 text-success"></i>Periode: 
                        <span class="badge bg-soft-success text-success px-3">
                            {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d/m/Y') : 'Awal' }} 
                            - 
                            {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d/m/Y') : 'Sekarang' }}
                        </span>
                    </p>
                </div>
                <div class="btn-group shadow-sm">
                    {{-- Link PDF sudah otomatis membawa filter tanggal dari URL --}}
                    <a href="{{ route('user.laporan.distribusi.pdf', request()->query()) }}" class="btn btn-success px-4 fw-bold">
                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                    </a>
                </div>
            </div>

            {{-- STATISTIC CARDS --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-soft-success me-3">
                                <i class="fas fa-boxes fa-lg"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block small">Total Item Diterima</small>
                                <h4 class="fw-bold mb-0">{{ number_format($distribusi->sum('jumlah'), 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-soft-info me-3">
                                <i class="fas fa-truck-loading fa-lg"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block small">Frekuensi Distribusi</small>
                                <h4 class="fw-bold mb-0">{{ $distribusi->count() }} Transaksi</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-soft-warning me-3">
                                <i class="fas fa-calendar-check fa-lg"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block small">Update Terakhir</small>
                                <h4 class="fw-bold mb-0" style="font-size: 1.1rem;">
                                    {{ $distribusi->first() ? \Carbon\Carbon::parse($distribusi->first()->tanggal)->diffForHumans() : '-' }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILTER BOX --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body py-3">
                    <form action="{{ route('user.laporan.distribusi') }}" method="GET" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Mulai Tanggal</label>
                            <input type="date" name="start_date" class="form-control form-control-sm border-light" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control form-control-sm border-light" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-dark btn-sm w-100 fw-bold">
                                <i class="fas fa-filter me-1"></i> Saring Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TABLE SECTION --}}
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-dark">
                        <i class="fas fa-table me-2 text-success"></i>Rincian Data Distribusi: {{ $outlet->nama_outlet }}
                    </h6>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted" width="5%">No</th>
                                    <th class="py-3 text-muted" width="20%">Waktu Terima</th>
                                    <th class="py-3 text-muted">Detail Bahan</th>
                                    <th class="pe-4 py-3 text-muted text-end" width="25%">Jumlah Masuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($distribusi as $item)
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</div>
                                        <small class="text-muted" style="font-size: 0.7rem;">ID Transaksi: #DIST-{{ $item->id }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->bahan->nama_bahan }}</div>
                                        <span class="badge bg-light text-muted border py-1" style="font-size: 0.65rem;">
                                            {{ $item->bahan->kategori ?? 'Bahan Baku' }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="fw-bold text-success fs-5">
                                            + {{ number_format($item->jumlah, 0, ',', '.') }} 
                                            <small class="fw-normal text-muted" style="font-size: 0.75rem;">{{ $item->bahan->satuan ?? 'Unit' }}</small>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-5 text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-box-open fa-3x text-light"></i>
                                        </div>
                                        <p class="text-muted">Ops! Belum ada data distribusi untuk periode ini.</p>
                                        <a href="{{ route('user.laporan.distribusi') }}" class="btn btn-sm btn-outline-secondary">Reset Filter</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-top-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-muted italic">Dicetak oleh: {{ auth()->user()->nama }}</span>
                        <span class="small text-muted fw-bold">{{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .bg-soft-success { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
    .bg-soft-info { background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
    .bg-soft-warning { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }
    .icon-box { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 12px; }
</style>
@endsection