@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}">

{{-- CUSTOM CSS: ADAPTIVE (LIGHT/DARK) & PRINT --}}
<style>
    /* ========================================= */
    /* 1. ADAPTIVE STYLING (MODE TERANG - DEFAULT) */
    /* ========================================= */
    .prestige-card {
        background-color: #ffffff; 
        border: 1px solid #e3e6f0;
        border-radius: 15px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .custom-adaptive-input {
        background-color: #ffffff;
        color: #495057;
        border: 1px solid #ced4da;
        border-radius: 10px;
        height: 45px;
    }
    
    .text-adaptive {
        color: #333333 !important; /* Teks gelap untuk mode terang */
    }
    
    .text-adaptive-muted {
        color: #858796 !important; /* Teks abu untuk mode terang */
    }

    .table-adaptive td, .table-adaptive th {
        color: #333333;
        border-top: 1px solid #e3e6f0;
        vertical-align: middle;
    }

    /* ========================================= */
    /* 2. ADAPTIVE STYLING (MODE GELAP)          */
    /* ========================================= */
    /* Mendeteksi class dark-mode bawaan AdminLTE / Template */
    body.dark-mode .prestige-card,
    [data-theme="dark"] .prestige-card,
    .dark-mode .prestige-card {
        background-color: #1e2227 !important;
        border-color: #2d3238 !important;
    }

    body.dark-mode .custom-adaptive-input,
    [data-theme="dark"] .custom-adaptive-input,
    .dark-mode .custom-adaptive-input {
        background-color: #2d3238 !important;
        color: #ffffff !important;
        border-color: #3e444a !important;
    }

    body.dark-mode .custom-adaptive-input option,
    [data-theme="dark"] .custom-adaptive-input option,
    .dark-mode .custom-adaptive-input option {
        background-color: #1e2227 !important;
        color: #ffffff !important;
    }

    body.dark-mode .text-adaptive,
    [data-theme="dark"] .text-adaptive,
    .dark-mode .text-adaptive {
        color: #ffffff !important; /* Teks putih untuk mode gelap */
    }

    body.dark-mode .text-adaptive-muted,
    [data-theme="dark"] .text-adaptive-muted,
    .dark-mode .text-adaptive-muted {
        color: #a8b2bc !important; /* Teks abu terang untuk mode gelap */
    }

    body.dark-mode .table-adaptive td, 
    body.dark-mode .table-adaptive th,
    [data-theme="dark"] .table-adaptive td, 
    [data-theme="dark"] .table-adaptive th {
        border-color: #2d3238 !important;
        color: #ffffff !important;
    }

    /* ========================================= */
    /* 3. SETTINGAN KHUSUS CETAK PDF / PRINT     */
    /* ========================================= */
    @media print {
        body {
            background-color: white !important;
        }

        /* Paksa header jadi hijau di PDF */
        .executive-header {
            background-color: #1a7a4a !important; 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important;
            padding: 20px !important;
            border-radius: 10px !important;
            margin-bottom: 20px !important;
        }
        
        .executive-header h1, 
        .executive-header p, 
        .executive-header strong,
        .executive-header i {
            color: white !important;
        }

        /* Bersihkan Card saat diprint */
        .prestige-card {
            background-color: white !important;
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }

        /* Paksa teks jadi hitam di kertas */
        .text-adaptive, .text-adaptive-muted, .prestige-card h5, .prestige-card span, .table-adaptive td, h2 {
            color: black !important;
        }

        /* Hilangkan elemen yang tidak perlu dicetak */
        .no-print, .btn, .filter-toolbar, .sidebar, .navbar {
            display: none !important;
        }
    }
</style>

<div class="container-fluid mb-5">
    @php
        $bulanAktif = (int) request('bulan', now()->month);
        $tahunAktif = (int) request('tahun', now()->year);
        $namaBulanAktif = \Carbon\Carbon::create()->month($bulanAktif)->translatedFormat('F');
    @endphp

    {{-- HEADER --}}
    <div class="executive-header"> 
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="d-flex align-items-center">
                    <div class="header-icon-glass mr-4">
                        <i class="fas fa-file-invoice-dollar fa-2x text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-white font-weight-bold mb-1" style="font-size: 1.8rem; letter-spacing: -0.5px;">Executive Dashboard</h1>
                        <p class="text-white-50 mb-0">Analisis Performa Sistem: <strong>{{ $namaBulanAktif }} {{ $tahunAktif }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 text-md-right mt-4 mt-md-0 no-print">
                <button onclick="window.print()" class="btn shadow-sm px-4 py-2" style="border-radius: 10px; font-weight: 700; color: #1a7a4a; background: white; border: none;">
                    <i class="fas fa-print mr-2"></i> Cetak Laporan
                </button>
            </div>
        </div>
    </div>

    {{-- STATS OVERLAP --}}
    <div class="stats-overlap mt-4">
        <div class="row">
            @php
                $kpis = [
                    ['title' => 'Distribusi', 'val' => $totalDistribusi ?? 0, 'icon' => 'fa-truck', 'bg' => 'bg-primary', 'unit' => 'Pesanan'],
                    ['title' => 'Stok Masuk', 'val' => $stokMasuk ?? 0, 'icon' => 'fa-arrow-down', 'bg' => 'bg-success', 'unit' => 'Item'],
                    ['title' => 'Outlet Aktif', 'val' => $outletAktif ?? 0, 'icon' => 'fa-store', 'bg' => 'bg-info', 'unit' => 'Mitra'],
                    ['title' => 'Peringatan', 'val' => $stokMenipis ?? 0, 'icon' => 'fa-exclamation-triangle', 'bg' => 'bg-danger', 'unit' => 'Kritis'],
                ];
            @endphp
            @foreach($kpis as $k)
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card prestige-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="icon-box-premium {{ $k['bg'] }} text-white shadow-sm p-2 rounded">
                                <i class="fas {{ $k['icon'] }}"></i>
                            </div>
                            <span class="badge badge-secondary font-weight-bold" style="font-size: 0.7rem;">{{ $k['unit'] }}</span>
                        </div>
                        {{-- Menggunakan text-adaptive agar menyesuaikan tema --}}
                        <h2 class="font-weight-bold mb-0 text-adaptive">{{ number_format($k['val']) }}</h2>
                        <p class="text-adaptive-muted small mb-0 font-weight-bold text-uppercase mt-1">{{ $k['title'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- TOOLBAR FILTER --}}
    <div class="row px-2 mb-4">
        <div class="col-12">
            <div class="filter-toolbar card prestige-card p-3">
                <form action="" method="GET" class="row align-items-center m-0">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <h6 class="font-weight-bold mb-0 text-adaptive"><i class="fas fa-sliders-h mr-2 text-success"></i> Konfigurasi Laporan</h6>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <select name="bulan" class="form-control custom-adaptive-input shadow-none">
                            @for($i=1;$i<=12;$i++)
                                <option value="{{$i}}" {{ $bulanAktif == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <select name="tahun" class="form-control custom-adaptive-input shadow-none">
                            @foreach(range(now()->year, 2024) as $year)
                                <option value="{{ $year }}" {{ $tahunAktif == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success btn-block font-weight-bold shadow-sm" style="height: 45px; border-radius: 10px; background: #00b894; border: none;">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="row">
        {{-- TOP PERFORMA OUTLET --}}
        <div class="col-lg-8 mb-4">
            <div class="card prestige-card h-100">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="font-weight-bold mb-0 text-adaptive">Top Performa Outlet</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-adaptive mb-0">
                            <thead>
                                <tr>
                                    <th class="px-4 border-top-0 text-adaptive-muted">Outlet</th>
                                    <th class="text-center border-top-0 text-adaptive-muted">Aktivitas</th>
                                    <th class="px-4 border-top-0 text-adaptive-muted">Visualisasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outletTeraktif ?? [] as $o)
                                <tr>
                                    <td class="px-4">
                                        <span class="font-weight-bold text-adaptive">{{ $o->nama_outlet }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success p-2" style="background: #00b894; border-radius: 8px;">{{ $o->total }} Transaksi</span>
                                    </td>
                                    <td class="px-4">
                                        <div class="progress" style="height: 10px; border-radius: 10px; background-color: #e9ecef;">
                                            @php $p = ($o->total / ($outletTeraktif->max('total') ?: 1)) * 100; @endphp
                                            <div class="progress-bar bg-success" style="width: {{ $p }}%; border-radius: 10px;"></div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-5 text-adaptive-muted">Data tidak ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- KEBUTUHAN BAHAN --}}
        <div class="col-lg-4 mb-4">
            <div class="card prestige-card h-100">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="font-weight-bold mb-0 text-adaptive">Kebutuhan Bahan</h5>
                </div>
                <div class="card-body px-4">
                    @forelse($bahanTerbanyak ?? [] as $b)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="font-weight-bold text-adaptive small">{{ $b->nama_bahan }}</span>
                            <span class="font-weight-bold text-success small">{{ number_format($b->total) }} Unit</span>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 10px; background-color: #e9ecef;">
                            @php $pb = ($b->total / ($bahanTerbanyak->max('total') ?: 1)) * 100; @endphp
                            <div class="progress-bar bg-primary" style="width: {{ $pb }}%; border-radius: 10px;"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-adaptive-muted py-5">Kosong</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection