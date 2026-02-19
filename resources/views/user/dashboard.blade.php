@extends('layouts.main')

@section('title', 'Dashboard Outlet')
@section('page', 'Dashboard Outlet')

@section('content')

{{-- ================= STATISTIC CARDS ================= --}}
<div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">{{ $totalStok }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Jenis Bahan Tersedia</p>
                </div>
                <div class="p-3 rounded-3 bg-soft-success" style="background-color: #e8f5e9;">
                    <i class="fas fa-box fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">{{ $pemakaianHariIni }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Pemakaian Hari Ini</p>
                </div>
                <div class="p-3 rounded-3 bg-soft-warning" style="background-color: #fffde7;">
                    <i class="fas fa-utensils fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">{{ $distribusi }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Stok Masuk (Bulan Ini)</p>
                </div>
                <div class="p-3 rounded-3 bg-soft-info" style="background-color: #e1f5fe;">
                    <i class="fas fa-truck-loading fa-2x text-info"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= TABLES SECTION ================= --}}
<div class="row">
    
    {{-- DETAIL STOK TERAKHIR --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-semibold mb-0 text-success"><i class="fas fa-layer-group me-2"></i> Detail Stok Terakhir</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Bahan</th>
                            <th class="text-center">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokOutlets as $stok)
                        <tr>
                            <td class="fw-semibold">{{ $stok->bahan->nama_bahan ?? 'Tidak Diketahui' }}</td>
                            <td class="text-center">
                                <span class="badge {{ $stok->stok > 10 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $stok->stok }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">Belum ada data stok</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PEMAKAIAN TERAKHIR --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-semibold mb-0 text-warning"><i class="fas fa-history me-2"></i> Riwayat Pemakaian</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Bahan</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemakaians as $pem)
                        <tr>
                            <td class="fw-semibold">{{ $pem->bahan->nama_bahan ?? '-' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $pem->jumlah }}</span></td>
                            <td>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($pem->tanggal)->format('d/m/Y') }}
                                </small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada data pemakaian</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection