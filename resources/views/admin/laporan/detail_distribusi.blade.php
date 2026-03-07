@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}?v={{ time() }}">

<div class="container-fluid py-4" style="background: #f4f7f6; min-height: 100vh;">
    
    <div class="executive-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('admin.laporan.distribusi') }}">Laporan</a></li>
                        <li class="breadcrumb-item active">Detail Distribusi</li>
                    </ol>
                </nav>
                <h2 class="font-weight-bold mb-1">Rincian Distribusi Bahan</h2>
                <p class="opacity-75 mb-0">Periode: {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('admin.laporan.distribusi') }}" class="btn-return-formal">
                    <i class="fas fa-chevron-left"></i> Kembali 
                </a>
            </div>
        </div>
    </div>

    <div class="main-report-card mx-3 mb-4">
        <div class="card-body p-4">
            <div class="row text-center text-md-left">
                <div class="col-md-6 border-right">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <div class="avatar-box mr-3">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-dark mb-0">{{ $outlet->nama_outlet }}</h5>
                            <small class="text-muted">{{ $outlet->alamat }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 border-right py-3 py-md-0">
                    <span class="info-label d-block">Intensitas Pengiriman</span>
                    <span class="h4 font-weight-bold text-success">{{ $items->count() }}x</span>
                </div>
                <div class="col-md-3 py-3 py-md-0">
                    <span class="info-label d-block">Status Verifikasi</span>
                    <span class="badge badge-success px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.7rem;">TERVERIFIKASI</span>
                </div>
            </div>
        </div>
    </div>

    <div class="main-report-card mx-3">
        <div class="card-header bg-white py-4 d-flex flex-column flex-md-row justify-content-between align-items-center border-0">
            <h6 class="font-weight-bold text-dark mb-0">LOG DISTRIBUSI & PENERIMAAN</h6>
            <div class="search-wrapper-custom no-print mt-3 mt-md-0">
                <i class="fas fa-search" style="position:absolute; left:12px; top:12px; color:#cbd5e1;"></i>
                <input type="text" id="searchInput" class="form-control border-0" placeholder="Cari bahan baku...">
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-luxury">
                <thead>
                    <tr>
                        <th class="text-center" width="50">No</th>
                        <th>Jadwal Kirim (Pusat)</th>
                        <th>Bahan Baku</th>
                        <th class="text-center">Kuantitas</th>
                        <th>Status Penerimaan (Outlet)</th>
                    </tr>
                </thead>
                <tbody id="distributionTable">
                    @foreach ($items as $item)
                    <tr>
                        <td class="text-center text-muted font-weight-bold">{{ $loop->iteration }}</td>
                        <td>
                            <span class="font-weight-bold text-dark d-block">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </span>
                            <span class="text-time">Pukul {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</span>
                        </td>
                        <td>
                            <span class="font-weight-bold text-dark">{{ $item->bahan->nama_bahan }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge-count-premium">
                                {{ number_format($item->jumlah) }} {{ $item->bahan->satuan }}
                            </span>
                        </td>
                        <td>
                            <div class="p-2 bg-light rounded" style="border-left: 3px solid #1a7a4a;">
                                <span class="info-label" style="font-size: 0.6rem !important;">Diterima pada:</span>
                                <span class="font-weight-bold text-dark d-block" style="font-size: 0.85rem;">
                                    {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-time">Pukul {{ \Carbon\Carbon::parse($item->updated_at)->format('H:i') }} WIB</span>
                                <span class="status-received">
                                    <i class="fas fa-check-circle mr-1"></i> BARANG SUDAH DITERIMA
                                </span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-light py-4 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small"><i>* Laporan ini dihasilkan otomatis oleh sistem logistik Teh Kita.</i></span>
                <div class="text-right">
                    <span class="info-label d-block">Total Volume Terdistribusi</span>
                    <span class="h5 font-weight-bold text-success">{{ number_format($items->sum('jumlah')) }} Items</span>
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