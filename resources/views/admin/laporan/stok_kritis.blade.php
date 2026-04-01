@extends('layouts.main')

@section('title', 'Laporan Stok Kritis')
@section('page', 'Stok Kritis Wilayah')

@section('content')

<link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}?v={{ time() }}">

<div class="container-fluid py-4" style="background: #fcf8f8; min-height: 100vh;">

    {{-- SECTION 1: EXECUTIVE HEADER (Red/Danger Theme) --}}
    <div class="executive-header shadow-lg mb-5" style="background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);">
        <div class="row align-items-center">
            <div class="col-md-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-2">
                        <li class="breadcrumb-item"><a href="#" class="text-white-50">Laporan</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Stok Kritis</li>
                    </ol>
                </nav>
                <h2 class="font-weight-bold mb-1">Critical Stock Monitor</h2>
                <p class="text-white-50 mb-0">Segera lakukan distribusi untuk bahan yang berada di bawah ambang batas stok aman.</p>
            </div>
            <div class="col-md-5 text-md-right mt-3 mt-md-0">
                {{-- TOMBOL EKSPOR WILAYAH (Warna Merah Premium) --}}
                <a href="{{ route('admin.laporan.stok-kritis.cetak', ['outlet_id' => request('outlet_id')]) }}" 
                   class="btn-kembali-premium px-4 shadow-sm" style="background: white; color: #d32f2f;">
                    <i class="fas fa-file-pdf mr-2"></i> Ekspor Rekap Wilayah
                </a>
            </div>
        </div>
    </div>

    {{-- SECTION 2: MINI STATS (Nuansa Merah) --}}
    <div class="row mb-4" style="margin-top: -30px; position: relative; z-index: 10;">
        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card stat-card border-danger shadow-sm bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-danger text-white mr-3 shadow-sm" style="border-radius: 12px; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                        </div>
                        <div>
                            <span class="text-muted small font-weight-bold uppercase">TOTAL ITEM KRITIS</span>
                            <h3 class="font-weight-bold mb-0 text-danger">{{ $totalKritisGlobal }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card stat-card border-warning shadow-sm bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-warning-soft text-warning mr-3" style="border-radius: 12px; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; background: #fff3e0;">
                            <i class="fas fa-store-alt fa-lg"></i>
                        </div>
                        <div>
                            <span class="text-muted small font-weight-bold uppercase">OUTLET TERDAMPAK</span>
                            <h3 class="font-weight-bold mb-0 text-dark">{{ $laporanOutlet->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card stat-card border-info shadow-sm bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-info-soft text-info mr-3" style="border-radius: 12px; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; background: #e0f7fa;">
                            <i class="fas fa-shield-alt fa-lg"></i>
                        </div>
                        <div>
                            <span class="text-muted small font-weight-bold uppercase">AMBANG BATAS</span>
                            <h3 class="font-weight-bold mb-0 text-dark">≤ 5</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- SECTION 3: FILTER & MAIN REPORT --}}
<div class="main-report-card mx-1">
    {{-- bg-white dihapus agar mengikuti dark mode, py & px disesuaikan --}}
    <div class="card-header py-4 border-0 px-4" style="background: transparent;">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
            
            {{-- Judul & Deskripsi --}}
            <div class="mb-3 mb-lg-0">
                <h5 class="font-weight-bold mb-1" style="color: inherit;">Daftar Outlet Terdampak</h5>
                <p class="text-muted small mb-0">Klik "Lihat Detail" untuk melihat daftar bahan spesifik yang menipis.</p>
            </div>
            
            {{-- Bagian Kanan: Filter & Search --}}
            <div class="d-flex align-items-center">
                {{-- FORM FILTER --}}
                <form action="{{ route('admin.laporan.stok-kritis') }}" method="GET" class="mr-2">
                    <div class="search-wrapper-custom">
                        {{-- Icon filter dihapus agar tidak menabrak teks --}}
                        <select name="outlet_id" class="form-control border-0 shadow-none custom-select-clean" onchange="this.form.submit()" style="cursor: pointer; width: 180px;">
                            <option value="">Semua Lokasi</option>
                            @foreach($outlets as $o)
                                <option value="{{ $o->id }}" {{ request('outlet_id') == $o->id ? 'selected' : '' }}>
                                    {{ $o->nama_outlet }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                {{-- FORM SEARCH --}}
                <div class="search-wrapper-custom">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" class="form-control border-0 shadow-none" placeholder="Cari outlet..." style="width: 200px;">
                </div>
            </div>

        </div>
    </div>
</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-luxury mb-0" id="outletTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="80">NO</th>
                            <th>IDENTITAS OUTLET TERDAMPAK</th>
                            <th class="text-center">JUMLAH ITEM KRITIS</th>
                            <th class="text-center">TINGKAT URGENSI</th>
                            <th class="text-center" width="200">OPSI TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporanOutlet as $l)
                        <tr>
                            <td class="text-center">
                                <span class="badge badge-light p-2 font-weight-bold text-muted shadow-sm" style="border-radius: 8px;">#{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-box mr-3 bg-light">
                                        <i class="fas fa-store text-danger"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark mb-0" style="font-size: 1rem;">{{ $l->outlet->nama_outlet }}</div>
                                        <small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i> {{ $l->outlet->alamat }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge-count-premium" style="background: #fff5f5; color: #e53e3e; border: 1px solid #feb2b2;">
                                    {{ $l->total_item_kritis }} <small>Bahan</small>
                                </span>
                            </td>
                            <td class="text-center">
                                @if($l->total_item_kritis > 3)
                                    <span class="badge-soft-danger pulse-animation">
                                        <i class="fas fa-fire mr-1"></i> Sangat Tinggi
                                    </span>
                                @else
                                    <span class="badge-soft-warning">
                                        <i class="fas fa-clock mr-1"></i> Medium
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('admin.laporan.stok-kritis.detail', $l->outlet_id) }}" 
                                       class="btn-action-premium btn-view mr-2" 
                                       title="Lihat Detail">
                                        <i class="fas fa-chart-line"></i>
                                    </a>
                                    
                                    {{-- Tombol Cetak PDF Per Outlet (Sama seperti Stok Outlet) --}}
                                    <a href="{{ route('admin.laporan.stok-kritis.cetak', $l->outlet_id) }}" 
                                       target="_blank"
                                       class="btn-action-premium btn-pdf" 
                                       title="Cetak PDF Outlet">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center">
                                <div class="py-4">
                                    <i class="fas fa-check-circle fa-4x text-success mb-3 opacity-25"></i>
                                    <h6 class="text-muted font-italic">Luar biasa! Seluruh stok di semua outlet terpantau aman.</h6>
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
                        <i class="fas fa-info-circle mr-1"></i> Menampilkan <strong>{{ $laporanOutlet->count() }}</strong> outlet yang membutuhkan restock segera.
                    </p>
                </div>
                <div class="col-md-6 text-md-right">
                    <span class="text-time text-danger">
                        <i class="fas fa-history mr-1"></i> Terakhir Diperbarui: {{ now()->format('H:i') }} WIB
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT SEARCH & ANIMATION --}}
@push('js')
<style>
    /* Tambahan animasi pulse untuk urgensi tinggi */
    .pulse-animation {
        animation: pulse-red 2s infinite;
        box-shadow: 0 0 0 0 rgba(229, 62, 62, 0.7);
    }
    @keyframes pulse-red {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(229, 62, 62, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(229, 62, 62, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(229, 62, 62, 0); }
    }
    .badge-soft-danger { background: #fff5f5; color: #e53e3e; padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.75rem; display: inline-flex; align-items: center; }
    .badge-soft-warning { background: #fffaf0; color: #dd6b20; padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.75rem; display: inline-flex; align-items: center; }
</style>

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