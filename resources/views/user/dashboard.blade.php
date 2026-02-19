@extends('layouts.main')

@section('title', 'Dashboard ' . (auth()->user()->outlet->nama_outlet ?? 'Outlet'))
@section('page', 'Dashboard')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between bg-white p-4 rounded-3 shadow-sm border-left-success" style="border-left: 5px solid #28a745;">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fas fa-store text-success me-2"></i>
                    Dashboard {{ auth()->user()->outlet->nama_outlet ?? 'Outlet' }}
                </h3>
                <p class="text-muted mb-0">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong></p>
            </div>
            <div class="text-end d-none d-md-block">
                <span class="badge bg-light text-success border px-3 py-2 rounded-pill">
                    <i class="fas fa-calendar-alt me-1"></i> {{ date('d M Y') }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0">{{ $totalStok }}</h3>
                    <small class="text-muted">Jenis Bahan Tersedia</small>
                </div>
                <div class="p-3 rounded-3" style="background-color: #e8f5e9;"><i class="fas fa-box fa-2x text-success"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0">{{ $pemakaianHariIni }}</h3>
                    <small class="text-muted">Pemakaian Hari Ini</small>
                </div>
                <div class="p-3 rounded-3" style="background-color: #fffde7;"><i class="fas fa-utensils fa-2x text-warning"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0">{{ $distribusi }}</h3>
                    <small class="text-muted">Stok Masuk (Bulan Ini)</small>
                </div>
                <div class="p-3 rounded-3" style="background-color: #e1f5fe;"><i class="fas fa-truck-loading fa-2x text-info"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-success"><i class="fas fa-layer-group me-2"></i>Detail Stok Terakhir</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Bahan</th><th class="text-center">Stok</th></tr>
                    </thead>
                    <tbody>
                        @forelse($stokOutlets as $stok)
                        <tr>
                            <td>{{ $stok->bahan->nama_bahan ?? '-' }}</td>
                            <td class="text-center"><span class="badge {{ $stok->stok > 10 ? 'bg-success' : 'bg-danger' }} rounded-pill">{{ $stok->stok }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-muted">No data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-warning"><i class="fas fa-history me-2"></i>Riwayat Pemakaian</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Bahan</th><th>Jumlah</th><th>Tanggal</th></tr>
                    </thead>
                    <tbody>
                        @forelse($pemakaians as $pem)
                        <tr>
                            <td>{{ $pem->bahan->nama_bahan ?? '-' }}</td>
                            <td>{{ $pem->jumlah }}</td>
                            <td><small>{{ \Carbon\Carbon::parse($pem->tanggal)->format('d/m/Y') }}</small></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">No data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection