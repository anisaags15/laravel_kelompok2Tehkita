@extends('layouts.main')
@section('title', 'Laporan Stok Kritis')
@section('content')
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <h3 class="fw-bold mb-0">
                <i class="fas fa-warehouse text-primary mr-2"></i> Pengawasan Stok Kritis
            </h3>
            <p class="text-muted small">Monitoring real-time bahan baku di bawah ambang batas minimum seluruh outlet.</p>
        </div>
        <div class="text-right">
            <a href="{{ route('admin.laporan.stok-kritis.cetak', ['outlet_id' => request('outlet_id')]) }}" 
               class="btn btn-danger btn-sm px-4 shadow-sm">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="row mb-4 no-print">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small mb-1">Total Item Kritis</h6>
                            <h2 class="fw-bold mb-0">{{ $stokKritis->count() }}</h2>
                        </div>
                        <i class="fas fa-exclamation-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small text-muted mb-1">Outlet Terdampak</h6>
                            <h2 class="fw-bold mb-0 text-dark">{{ $stokKritis->unique('outlet_id')->count() }}</h2>
                        </div>
                        <i class="fas fa-store-alt fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small text-muted mb-1">Batas Minimum</h6>
                            <h2 class="fw-bold mb-0 text-dark">≤ 5</h2>
                        </div>
                        <i class="fas fa-chart-line fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 no-print border-left-primary">
        <div class="card-body">
            <form action="{{ route('admin.laporan.stok-kritis') }}" method="GET" class="row align-items-end">
                <div class="col-md-5">
                    <label class="small fw-bold text-muted">Pilih Lokasi Outlet</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-search-location"></i></span>
                        </div>
                        <select name="outlet_id" class="form-control border-left-0 shadow-none">
                            <option value="">Seluruh Jaringan Outlet</option>
                            @foreach($outlets as $o)
                                <option value="{{ $o->id }}" {{ request('outlet_id') == $o->id ? 'selected' : '' }}>{{ $o->nama_outlet }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block shadow-sm">
                        <i class="fas fa-filter mr-1"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Inventaris Kritis</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center border-0 px-4">NO</th>
                            <th class="border-0">OUTLET</th>
                            <th class="border-0">NAMA BAHAN</th>
                            <th class="text-center border-0">SISA STOK</th>
                            <th class="text-center border-0">SATUAN</th>
                            <th class="text-center border-0">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokKritis as $s)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td><span class="fw-bold">{{ $s->outlet->nama_outlet }}</span></td>
                            <td>{{ $s->bahan->nama_bahan }}</td>
                            <td class="text-center">
                                <span class="badge badge-pill badge-danger px-3 py-2" style="font-size: 0.9rem;">
                                    {{ $s->stok }}
                                </span>
                            </td>
                            <td class="text-center text-muted small">{{ strtoupper($s->bahan->satuan) }}</td>
                            <td class="text-center">
                                <span class="text-danger small fw-bold italic"><i class="fas fa-arrow-down mr-1"></i> Restock Segera</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-check-circle fa-3x mb-3 text-success opacity-50"></i>
                                <p class="mb-0">Stok di seluruh outlet terpantau aman.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Gradient Effect */
    .bg-gradient-danger {
        background: linear-gradient(45deg, #d9534f, #ff6b6b);
    }
    
    /* Border Accent */
    .border-left-primary {
        border-left: 4px solid #4e73df !important;
    }

    .table td {
        vertical-align: middle !important;
    }

    .opacity-50 { opacity: 0.5; }

    @media print {
        .no-print { display: none !important; }
        .card { border: 1px solid #ddd !important; }
        .badge-danger { color: red !important; border: 1px solid red !important; background: transparent !important; }
    }
</style>
@endsection