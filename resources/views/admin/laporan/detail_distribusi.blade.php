@extends('layouts.main')

@section('title', 'Detail Distribusi - ' . $outlet->nama_outlet)

@section('content')
<link rel="stylesheet" href="{{ asset('css/laporan-user.css') }}">

<div class="container-fluid py-4">
    <div class="executive-header shadow-lg mb-0">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-2">
                        <li class="breadcrumb-item"><a href="#" class="text-white-50">Laporan</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Detail Distribusi</li>
                    </ol>
                </nav>
                <h2 class="font-weight-bold text-white mb-1">Rincian Distribusi Bahan</h2>
                <p class="text-white-50 mb-0"><i class="fas fa-calendar-alt mr-2"></i> Periode: {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</p>
            </div>
            <div class="mt-3 mt-md-0 no-print">
                <a href="{{ route('admin.laporan.distribusi') }}" class="btn btn-light btn-sm px-3 mr-2">
                    <i class="fas fa-chevron-left mr-1"></i> Kembali
                </a>
                <a href="{{ route('admin.laporan.distribusi.cetak', [$outlet->id, $bulan, $tahun]) }}" 
                   class="btn btn-primary btn-sm px-4 shadow-sm" target="_blank">
                    <i class="fas fa-file-pdf mr-1"></i> Cetak Laporan Resmi
                </a>
            </div>
        </div>
    </div>

    <div class="row px-4">
        <div class="col-12">
            <div class="card filter-card shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 border-right">
                            <div class="d-flex align-items-center">
                                <div class="avatar-box mr-3 shadow-sm" style="background: var(--primary-gradient);">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div>
                                    <h5 class="font-weight-bold text-dark mb-0">{{ $outlet->nama_outlet }}</h5>
                                    <small class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $outlet->alamat }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center border-right mt-3 mt-md-0">
                            <span class="fs-xs d-block text-muted mb-1">Total Pengiriman</span>
                            <span class="h4 font-weight-bold text-primary mb-0">{{ $items->count() }} Kali</span>
                        </div>
                        <div class="col-md-3 text-center mt-3 mt-md-0">
                            <span class="fs-xs d-block text-muted mb-1">Status Laporan</span>
                            <span class="badge-soft-success px-3 py-1 rounded-pill">
                                <i class="fas fa-check-circle mr-1"></i> Terverifikasi
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-n3" style="border-radius: 20px;">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="font-weight-bold text-dark mb-0">
                <i class="fas fa-clipboard-list text-primary mr-2"></i> Log Pengiriman Unit
            </h6>
            <div class="search-wrapper no-print">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari bahan...">
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-luxury table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="60">No</th>
                            <th>Tanggal Kirim</th>
                            <th>Nama Bahan Baku</th>
                            <th class="text-center">Kuantitas</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="distributionTable">
                        @forelse ($items as $item)
                        <tr>
                            <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-box-modern bg-light mr-2" style="width: 35px; height: 35px; border-radius: 8px;">
                                        <i class="far fa-calendar-alt text-primary"></i>
                                    </div>
                                    <span class="font-weight-bold text-dark">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="font-weight-bold text-dark">{{ $item->bahan->nama_bahan ?? 'N/A' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge-count-premium">
                                    {{ number_format($item->jumlah) }} {{ $item->bahan->satuan ?? 'Unit' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted small italic">{{ $item->keterangan ?? 'Distribusi Rutin' }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-light mb-3"></i>
                                <p class="text-muted italic">Data pengiriman tidak ditemukan untuk periode ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light border-0 py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small class="text-muted italic"><i class="fas fa-info-circle mr-1"></i> Menampilkan data distribusi berdasarkan input gudang pusat.</small>
                </div>
                <div class="col-md-6 text-md-right">
                    <span class="text-dark font-weight-bold mr-2">Total Item Terdistribusi:</span>
                    <span class="h5 font-weight-bold text-primary mb-0">{{ number_format($items->sum('jumlah')) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#distributionTable tr');
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
        });
    });
</script>
@endsection