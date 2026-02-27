@extends('layouts.main')

@section('title', 'Ringkasan Laporan Bulanan')
@section('page', 'Ringkasan Laporan Bulanan')

@push('css')
 <link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">

    @php
        $bulanAktif = (int) request('bulan', now()->month);
        $tahunAktif = (int) request('tahun', now()->year);
        $namaBulanAktif = \Carbon\Carbon::create()->month($bulanAktif)->translatedFormat('F');
        
        // Dinamis: Ambil rentang tahun dari 5 tahun lalu sampai tahun depan
        $tahunMulai = now()->subYears(5)->year;
        $tahunSelesai = now()->addYear()->year;
    @endphp

    {{-- HEADER & EXPORT ACTION --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h3 class="font-weight-bold text-dark mb-1">Laporan Eksekutif Bulanan</h3>
            <p class="text-muted mb-0">
                <i class="far fa-calendar-alt mr-1"></i> Fokus Analisis: <span class="text-primary font-weight-bold">{{ $namaBulanAktif }} {{ $tahunAktif }}</span>
            </p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.laporan.cetak', ['bulan' => $bulanAktif, 'tahun' => $tahunAktif]) }}" 
               class="btn btn-success shadow-sm px-4 py-2" style="border-radius: 10px; font-weight: 600;">
                <i class="fas fa-file-pdf mr-2"></i> Download PDF Resmi
            </a>
        </div>
    </div>

    {{-- FILTER SECTION --}}
    <div class="filter-section shadow-sm mb-4">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="row align-items-end">
            <div class="col-md-4 mb-3 mb-md-0">
                <label class="small font-weight-bold text-uppercase text-muted">Periode Bulan</label>
                <select name="bulan" class="form-control border-0 bg-light shadow-none font-weight-bold">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ $m }}" {{ $bulanAktif == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <label class="small font-weight-bold text-uppercase text-muted">Tahun Anggaran</label>
                <select name="tahun" class="form-control border-0 bg-light shadow-none font-weight-bold">
                    @for($y = $tahunMulai; $y <= $tahunSelesai; $y++)
                        <option value="{{ $y }}" {{ $tahunAktif == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary btn-block shadow-sm py-2 font-weight-bold">
                    <i class="fas fa-sync-alt mr-2"></i> Sinkronkan Data
                </button>
            </div>
        </form>
    </div>

    {{-- STATS CARDS --}}
    <div class="row">
        @php
            $stats = [
                ['label'=>'Total Distribusi','value'=>$totalDistribusi ?? 0,'color'=>'primary','icon'=>'fa-shipping-fast', 'bg' => 'bg-primary-soft', 'border' => 'border-primary'],
                ['label'=>'Stok Masuk','value'=>$stokMasuk ?? 0,'color'=>'success','icon'=>'fa-box-open', 'bg' => 'bg-success-soft', 'border' => 'border-success'],
                ['label'=>'Outlet Aktif','value'=>$outletAktif ?? 0,'color'=>'info','icon'=>'fa-store-alt', 'bg' => 'bg-info-soft', 'border' => 'border-info'],
                ['label'=>'Bahan Menipis','value'=>$stokMenipis ?? 0,'color'=>'danger','icon'=>'fa-exclamation-circle', 'bg' => 'bg-danger-soft', 'border' => 'border-danger'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card shadow-sm h-100 {{ $stat['border'] }}">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape {{ $stat['bg'] }} mr-3 shadow-sm">
                            <i class="fas {{ $stat['icon'] }} fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs font-weight-bold text-uppercase text-muted mb-0">{{ $stat['label'] }}</p>
                            <h3 class="font-weight-bold text-dark mb-0">{{ number_format($stat['value']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- TABLES SECTION --}}
    <div class="row">
        {{-- OUTLET TERAKTIF --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; border-top: 4px solid #15803d !important;">
                <div class="card-header bg-white border-0 py-3 card-header-accent d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-chart-line mr-2 text-success"></i>Peringkat Outlet Teraktif</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-luxury mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Unit Outlet</th>
                                    <th class="text-center">Volume Aktivitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($outletTeraktif ?? [] as $item)
                                <tr>
                                    <td class="font-weight-bold text-dark">{{ $item->nama_outlet }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-success badge-pill-custom">
                                            {{ $item->total }} Transaksi
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="text-center py-5 text-muted small">Data transaksi tidak ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAHAN TERLARIS --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; border-top: 4px solid #4338ca !important;">
                <div class="card-header bg-white border-0 py-3 card-header-accent d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-box mr-2 text-primary"></i>Bahan Baku Terpopuler</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-luxury mb-0">
                            <thead>
                                <tr>
                                    <th>Deskripsi Material</th>
                                    <th class="text-center">Total Penggunaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bahanTerbanyak ?? [] as $item)
                                <tr>
                                    <td class="font-weight-bold text-dark">{{ $item->nama_bahan }}</td>
                                    <td class="text-center text-primary font-weight-bold">
                                        {{ number_format($item->total) }} <span class="small text-muted">Unit</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="text-center py-5 text-muted small">Data stok belum terakumulasi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- QUICK NAVIGATION FOOTER --}}
    <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(to right, #ffffff, #f8f9fa);">
        <div class="card-body py-3 d-flex flex-column flex-md-row justify-content-center align-items-center">
            <span class="text-muted small mr-md-4 mb-2 mb-md-0 font-italic">Lihat rincian data lainnya melalui navigasi cepat:</span>
            <div class="btn-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                <a href="{{ route('admin.laporan.stok-outlet') }}" class="btn btn-white border-right btn-sm px-4 font-weight-bold">
                    <i class="fas fa-warehouse mr-2 text-info"></i> Log Stok Outlet
                </a>
                <a href="{{ route('admin.laporan.distribusi') }}" class="btn btn-white btn-sm px-4 font-weight-bold">
                    <i class="fas fa-exchange-alt mr-2 text-success"></i> Riwayat Distribusi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection