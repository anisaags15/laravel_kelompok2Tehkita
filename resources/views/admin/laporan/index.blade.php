@extends('layouts.main')

@section('title', 'Laporan Eksekutif Bulanan')
@section('page', 'Ringkasan Laporan Bulanan')

@section('content')
<link rel="stylesheet" href="{{ asset('css/laporan-user.css') }}">

<div class="container-fluid py-4">

    @php
        $bulanAktif = (int) request('bulan', now()->month);
        $tahunAktif = (int) request('tahun', now()->year);
        $namaBulanAktif = \Carbon\Carbon::create()->month($bulanAktif)->translatedFormat('F');
        
        $tahunMulai = now()->subYears(5)->year;
        $tahunSelesai = now()->addYear()->year;
    @endphp

    {{-- HEADER --}}
    <div class="executive-header shadow-lg no-print">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-2">
                    <div class="bg-white-20 p-2 rounded-lg mr-3">
                        <i class="fas fa-chart-line fa-2x text-white"></i>
                    </div>
                    <div>
                        <h2 class="font-weight-bold text-white mb-0">Dashboard Analitik</h2>
                        <p class="text-white-50 mb-0">Periode {{ $namaBulanAktif }} {{ $tahunAktif }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('admin.laporan.cetak', ['bulan' => $bulanAktif, 'tahun' => $tahunAktif]) }}" 
                   class="btn btn-light shadow-sm font-weight-bold text-primary px-4" style="border-radius: 12px;">
                    <i class="fas fa-file-invoice mr-2"></i> Ekspor Ringkasan
                </a>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="card filter-card shadow-lg no-print mx-3">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="row align-items-end">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="fs-xs fw-bold text-muted mb-2">Periode Analisis</label>
                    <div class="input-group bg-light rounded-pill px-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0"><i class="fas fa-calendar-alt text-primary"></i></span>
                        </div>
                        <select name="bulan" class="form-control border-0 bg-transparent font-weight-bold shadow-none">
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}" @if($bulanAktif == $m) selected @endif>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="fs-xs fw-bold text-muted mb-2">Tahun Fiskal</label>
                    <div class="input-group bg-light rounded-pill px-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0"><i class="fas fa-university text-primary"></i></span>
                        </div>
                        <select name="tahun" class="form-control border-0 bg-transparent font-weight-bold shadow-none">
                            @for($y = $tahunMulai; $y <= $tahunSelesai; $y++)
                                <option value="{{ $y }}" @if($tahunAktif == $y) selected @endif>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold shadow-sm rounded-pill">
                        <i class="fas fa-sync-alt mr-2"></i> Perbarui Visualisasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div class="row mt-2 px-2">
        @php
            $kpis = [
                ['label'=>'Volume Distribusi','value'=>$totalDistribusi ?? 0,'icon'=>'fa-shipping-fast','color'=>'primary'],
                ['label'=>'Alur Masuk Stok','value'=>$stokMasuk ?? 0,'icon'=>'fa-box-open','color'=>'success'],
                ['label'=>'Jaringan Aktif','value'=>$outletAktif ?? 0,'icon'=>'fa-store-alt','color'=>'info'],
                ['label'=>'Peringatan Kritis','value'=>$stokMenipis ?? 0,'icon'=>'fa-exclamation-triangle','color'=>'danger'],
            ];
        @endphp
        @foreach($kpis as $kpi)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 15px;">
                <div class="card-body">
                    <div class="icon-box-modern mb-3" style="background: rgba(0,0,0,0.03); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                        <i class="fas {{ $kpi['icon'] }} text-{{ $kpi['color'] }}"></i>
                    </div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">{{ $kpi['label'] }}</h6>
                    <h2 class="font-weight-bold text-dark mb-0">{{ number_format($kpi['value']) }}</h2>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ANALITIK --}}
    <div class="row px-2">
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="m-0 font-weight-bold text-dark">Outlet Performa Terbaik</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 border-0">Outlet</th>
                                    <th class="text-center border-0">Aktivitas</th>
                                    <th class="px-4 text-right border-0">Dominasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($outletTeraktif ?? [] as $item)
                                @php 
                                    $persen = min(($item->total / 100) * 100, 100); 
                                    // REVISI ANTI MERAH: Satukan style ke dalam satu string PHP
                                    $inlineStyle = "width: " . $persen . "%";
                                @endphp
                                <tr>
                                    <td class="px-4">
                                        <span class="font-weight-bold text-dark">{{ $item->nama_outlet }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-soft-primary px-3">{{ $item->total }} Trans.</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-5">Data tidak tersedia.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-lg h-100 text-white" style="border-radius: 20px; background: #1e293b;">
                <div class="card-header bg-transparent border-0 py-4">
                    <h5 class="m-0 font-weight-bold">Bahan Paling Dibutuhkan</h5>
                </div>
                <div class="card-body">
                    @forelse ($bahanTerbanyak ?? [] as $item)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-white-50 small">{{ $item->nama_bahan }}</span>
                            <span class="text-success small fw-bold">{{ number_format($item->total) }} Unit</span>
                        </div>
                        <div class="progress" style="height: 6px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: 70%">
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 opacity-50">Belum ada data.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection