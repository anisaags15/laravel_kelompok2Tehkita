@extends('layouts.main')

@section('title', 'Laporan Inventori Wilayah')
@section('page', 'Laporan Stok Outlet')

@section('content')
<link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}?v={{ time() }}">

<div class="container-fluid py-4" style="background: #f4f7f6; min-height: 100vh;">

    {{-- SECTION 1: EXECUTIVE HEADER (Green Theme) --}}
    <div class="executive-header shadow-lg mb-5">
        <div class="row align-items-center">
            <div class="col-md-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-2">
                        <li class="breadcrumb-item"><a href="#" class="text-white-50">Laporan</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Stok Outlet</li>
                    </ol>
                </nav>
                <h2 class="font-weight-bold mb-1">Stock Control Center</h2>
                <p class="text-white-50 mb-0">Pemantauan ketersediaan inventaris di seluruh cabang outlet Teh Kita secara real-time.</p>
            </div>
            <div class="col-md-5 text-md-right mt-3 mt-md-0">
                {{-- TOMBOL EKSPOR WILAYAH (Super Bagus) --}}
                <a href="{{ route('admin.laporan.stok-outlet.cetak-semua') }}" 
                   class="btn-kembali-premium px-4 shadow-sm">
                    <i class="fas fa-file-pdf mr-2"></i> Ekspor Rekap Wilayah
                </a>
            </div>
        </div>
    </div>

    {{-- SECTION 2: MINI STATS (Menggunakan Stat Card dari CSS-mu) --}}
    <div class="row mb-4" style="margin-top: -30px; position: relative; z-index: 10;">
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card stat-card border-success shadow-sm bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-success-soft mr-3">
                            <i class="fas fa-store-alt fa-lg"></i>
                        </div>
                        <div>
                            <span class="text-muted small font-weight-bold uppercase">CABANG TERDAFTAR</span>
                            <h3 class="font-weight-bold mb-0 text-dark">{{ $outlets->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card stat-card border-primary shadow-sm bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-primary-soft mr-3">
                            <i class="fas fa-boxes fa-lg"></i>
                        </div>
                        <div>
                            <span class="text-muted small font-weight-bold uppercase">TOTAL SKU ITEM</span>
                            <h3 class="font-weight-bold mb-0 text-dark">{{ $outlets->sum(fn($o) => $o->stokOutlet->count()) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 3: MAIN REPORT CARD --}}
    <div class="main-report-card mx-1">
        <div class="card-header bg-white py-4 border-0 px-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h5 class="font-weight-bold text-dark mb-1">Daftar Inventaris Outlet</h5>
                    <p class="text-muted small mb-0">Gunakan kolom pencarian untuk memfilter data cabang dengan cepat.</p>
                </div>
                {{-- FITUR SEARCH (Menggunakan search-wrapper-custom dari CSS-mu) --}}
                <div class="search-wrapper-custom mt-3 mt-md-0">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" class="form-control border-0 shadow-none" placeholder="Cari nama outlet...">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                {{-- Menggunakan table-luxury dari CSS-mu --}}
                <table class="table table-luxury mb-0" id="outletTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="80">RANK</th>
                            <th>IDENTITAS OUTLET</th>
                            <th class="text-center">VARIASI BAHAN</th>
                            <th class="text-center">STATUS SISTEM</th>
                            <th class="text-center" width="180">OPSI LAPORAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($outlets as $outlet)
                        <tr>
                            <td class="text-center">
                                <span class="badge badge-light p-2 font-weight-bold text-muted shadow-sm" style="border-radius: 8px;">#{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- Avatar Box dari CSS-mu (disesuaikan warna brand) --}}
                                    <div class="avatar-box mr-3 bg-light">
                                        <i class="fas fa-store" style="color: #1a7a4a;"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark mb-0" style="font-size: 1rem;">{{ $outlet->nama_outlet }}</div>
                                        <small class="text-muted">Kode: <span class="text-primary font-weight-bold">TCK-{{ 100 + $outlet->id }}</span></small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                {{-- Badge Count Premium dari CSS-mu --}}
                                <span class="badge-count-premium">
                                    {{ $outlet->stokOutlet->count() }} <small>Bahan</small>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge-soft-success">
                                    <i class="fas fa-check-circle mr-1"></i> Terhubung
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('admin.laporan.stok-outlet.detail', $outlet->id) }}" 
                                       class="btn-action-premium btn-view mr-2" 
                                       title="Lihat Detail Stok">
                                        <i class="fas fa-chart-line"></i>
                                    </a>
                                    
                                    <a href="{{ route('admin.laporan.stok-outlet.cetak', $outlet->id) }}" 
                                       target="_blank"
                                       class="btn-action-premium btn-pdf" 
                                       title="Ekspor PDF Cabang">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center">
                                <div class="py-4">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3 opacity-25"></i>
                                    <h6 class="text-muted font-italic">Belum ada data outlet yang tersedia untuk laporan.</h6>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white border-0 py-4 px-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 small text-muted">
                        <i class="fas fa-info-circle mr-1"></i> Data mencakup total <strong>{{ $outlets->count() }}</strong> outlet aktif.
                    </p>
                </div>
                <div class="col-md-6 text-md-right">
                    <span class="text-time">
                        <i class="fas fa-sync-alt mr-1"></i> Live Update: {{ now()->format('d M Y, H:i') }} WIB
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT SEARCH --}}
@push('js')
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll("#outletTable tbody tr");
        
        rows.forEach(row => {
            let outletName = row.cells[1].textContent.toUpperCase();
            if (outletName.indexOf(filter) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
@endpush
@endsection