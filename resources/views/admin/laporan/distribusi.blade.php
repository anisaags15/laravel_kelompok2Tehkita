@extends('layouts.main')

@section('title', 'Laporan Distribusi')
@section('page', 'Laporan Distribusi')

@section('content')
<link rel="stylesheet" href="{{ asset('css/laporan-user.css') }}">

<div class="container-fluid py-4">
    <div class="executive-header shadow-lg">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h2 class="font-weight-bold text-white mb-1">Log Distribusi Bulanan</h2>
                <p class="text-white-50 mb-0">Arsip pengiriman stok pusat ke seluruh unit outlet.</p>
            </div>
            <div class="mt-3 mt-md-0 bg-white-20 p-2 px-4 rounded-pill border border-white-50 shadow-sm">
                <i class="fas fa-archive text-white mr-2"></i>
                <span class="small font-weight-bold text-white">Total: {{ $distribusis->count() }} Periode Rekaman</span>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg mt-n4 mx-md-4" style="border-radius: 20px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-layer-group mr-2 text-primary"></i>Riwayat Distribusi Outlet
            </h6>
            <div class="search-wrapper no-print">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari outlet atau periode...">
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-luxury table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center py-4" width="70">No</th>
                            <th class="py-4">Periode Laporan</th>
                            <th class="py-4">Unit Outlet</th>
                            <th class="py-4 text-center">Aktivitas</th>
                            <th class="text-center py-4">Volume Barang</th>
                            <th class="text-center py-4" width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="logTable">
                        @forelse ($distribusis as $d)
                        <tr>
                            <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-box-modern bg-soft-primary mr-3" style="width: 40px; height: 40px;">
                                        <i class="far fa-calendar-alt text-primary"></i>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold text-dark d-block text-capitalize">
                                            {{ \Carbon\Carbon::create()->month($d->bulan)->translatedFormat('F') }}
                                        </span>
                                        <small class="text-muted fw-bold">{{ $d->tahun }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-box bg-soft-success text-success mr-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <span class="font-weight-bold text-dark">{{ $d->outlet->nama_outlet }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-pill badge-soft-info px-3 py-2 font-weight-bold">
                                    {{ $d->total_pengiriman }} Transaksi
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="badge-count-premium">
                                    {{ number_format($d->total_qty) }} <small class="fw-bold">Items</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.laporan.distribusi.detail', [$d->outlet_id, $d->bulan, $d->tahun]) }}" 
                                       class="btn-action-custom btn-detail mx-1" title="Buka Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.laporan.distribusi.cetak', [$d->outlet_id, $d->bulan, $d->tahun]) }}" 
                                       class="btn-action-custom btn-print mx-1" target="_blank" title="Cetak Laporan">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                                    <p class="text-muted italic">Tidak ada rekaman distribusi yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3 border-top-0">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted italic">
                    <i class="fas fa-info-circle mr-1 text-primary"></i> 
                    Data ini bersifat otomatis berdasarkan entri log distribusi gudang.
                </small>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#logTable tr');
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
        });
    });
</script>
@endsection