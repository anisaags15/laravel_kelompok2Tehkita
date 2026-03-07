@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}">

<div class="container-fluid mb-5">
    @php
        $bulanAktif = (int) request('bulan', now()->month);
        $tahunAktif = (int) request('tahun', now()->year);
        $namaBulanAktif = \Carbon\Carbon::create()->month($bulanAktif)->translatedFormat('F');
    @endphp

    {{-- HEADER --}}
    <div class="executive-header no-print">
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
            <div class="col-md-5 text-md-right mt-4 mt-md-0">
                <button onclick="window.print()" class="btn btn-white shadow-sm px-4 py-2" style="border-radius: 10px; font-weight: 700; color: #1a7a4a;">
                    <i class="fas fa-print mr-2"></i> Cetak Laporan
                </button>
            </div>
        </div>
    </div>

    {{-- STATS OVERLAP --}}
    <div class="stats-overlap">
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
                            <div class="icon-box-premium {{ $k['bg'] }} text-white shadow-sm">
                                <i class="fas {{ $k['icon'] }}"></i>
                            </div>
                            <span class="badge badge-light text-muted font-weight-bold" style="font-size: 0.7rem;">{{ $k['unit'] }}</span>
                        </div>
                        <h2 class="font-weight-bold mb-0">{{ number_format($k['val']) }}</h2>
                        <p class="text-muted small mb-0 font-weight-bold text-uppercase mt-1">{{ $k['title'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- TOOLBAR FILTER --}}
    <div class="row px-2">
        <div class="col-12">
            <div class="filter-toolbar">
                <form action="" method="GET" class="row align-items-center">
                    <div class="col-md-4">
                        <h6 class="font-weight-bold mb-0"><i class="fas fa-sliders-h mr-2 text-success"></i> Konfigurasi Laporan</h6>
                    </div>
                    <div class="col-md-3">
                        <select name="bulan" class="form-control custom-select-premium">
                            @for($i=1;$i<=12;$i++)
                                <option value="{{$i}}" {{ $bulanAktif == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="tahun" class="form-control custom-select-premium">
                            @foreach(range(now()->year, 2024) as $year)
                                <option value="{{ $year }}" {{ $tahunAktif == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-success btn-block font-weight-bold shadow-sm" style="height: 45px; border-radius: 10px;">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="col-lg-8">
            <div class="card prestige-card">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="font-weight-bold mb-0 text-dark">Top Performa Outlet</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-prestige">
                            <thead>
                                <tr>
                                    <th>Outlet</th>
                                    <th class="text-center">Aktivitas</th>
                                    <th>Visualisasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outletTeraktif ?? [] as $o)
                                <tr>
                                    <td>
                                        <span class="font-weight-bold text-dark">{{ $o->nama_outlet }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-prestige bg-soft-green">{{ $o->total }} Transaksi</span>
                                    </td>
                                    <td>
                                        <div class="progress progress-prestige">
                                            @php $p = ($o->total / ($outletTeraktif->max('total') ?: 1)) * 100; @endphp
                                            <div class="progress-bar bg-success" style="width: {{ $p }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-5 text-muted">Data tidak ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card prestige-card h-100">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="font-weight-bold mb-0 text-dark">Kebutuhan Bahan</h5>
                </div>
                <div class="card-body">
                    @forelse($bahanTerbanyak ?? [] as $b)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="font-weight-bold text-dark small">{{ $b->nama_bahan }}</span>
                            <span class="font-weight-bold text-success small">{{ number_format($b->total) }} Unit</span>
                        </div>
                        <div class="progress progress-prestige">
                            @php $pb = ($b->total / ($bahanTerbanyak->max('total') ?: 1)) * 100; @endphp
                            <div class="progress-bar bg-primary" style="width: {{ $pb }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted py-5">Kosong</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection