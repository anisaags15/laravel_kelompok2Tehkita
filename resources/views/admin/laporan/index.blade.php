@extends('layouts.main')

@section('title', 'Laporan Eksekutif Bulanan')
@section('page', 'Ringkasan Laporan Bulanan')

@push('css')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        --success-gradient: linear-gradient(135deg, #15803d 0%, #22c55e 100%);
        --danger-gradient: linear-gradient(135deg, #b91c1c 0%, #ef4444 100%);
        --glass-bg: rgba(255, 255, 255, 0.95);
    }

    .executive-header {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);
    }

    .filter-card {
        border: none;
        border-radius: 15px;
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        transform: translateY(-50px);
        margin-bottom: -20px;
    }

    .stat-card-modern {
        border: none;
        border-radius: 18px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .stat-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }

    .icon-box-modern {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .progress-custom {
        height: 8px;
        border-radius: 10px;
        background-color: #f1f5f9;
    }

    .table-luxury thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border: none;
    }

    .table-luxury td {
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
    }

    .badge-soft-success { background: #dcfce7; color: #15803d; border: none; }
    .badge-soft-primary { background: #e0e7ff; color: #1e3a8a; border: none; }
    .badge-soft-info { background: #e0f2fe; color: #0369a1; border: none; }
    .badge-soft-danger { background: #fee2e2; color: #b91c1c; border: none; }

    @media print {
        .no-print { display: none !important; }
        .filter-card { transform: none; margin-top: 20px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    @php
        $bulanAktif = (int) request('bulan', now()->month);
        $tahunAktif = (int) request('tahun', now()->year);
        $namaBulanAktif = \Carbon\Carbon::create()->month($bulanAktif)->translatedFormat('F');
        
        $tahunMulai = now()->subYears(5)->year;
        $tahunSelesai = now()->addYear()->year;
    @endphp

    {{-- HEADER EKSEKUTIF --}}
    <div class="executive-header no-print">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="font-weight-bold mb-1">Dashboard Analitik</h2>
                <p class="opacity-75 mb-0">Laporan Konsolidasi Operasional Jaringan Teh Kita - Periode {{ $namaBulanAktif }} {{ $tahunAktif }}</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('admin.laporan.cetak', ['bulan' => $bulanAktif, 'tahun' => $tahunAktif]) }}" 
                   class="btn btn-light btn-lg px-4 shadow-sm font-weight-bold text-primary" style="border-radius: 12px;">
                    <i class="fas fa-file-invoice mr-2"></i> Ekspor Ringkasan Eksekutif
                </a>
            </div>
        </div>
    </div>

    {{-- KOTAK FILTER --}}
    <div class="card filter-card shadow-lg no-print mx-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="row align-items-end">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted text-uppercase">Periode Analisis</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-0"><i class="fas fa-calendar-alt text-primary"></i></span>
                        </div>
                        <select name="bulan" class="form-control border-0 bg-light font-weight-bold shadow-none">
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}" {{ $bulanAktif == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted text-uppercase">Tahun Fiskal</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-0"><i class="fas fa-university text-primary"></i></span>
                        </div>
                        <select name="tahun" class="form-control border-0 bg-light font-weight-bold shadow-none">
                            @for($y = $tahunMulai; $y <= $tahunSelesai; $y++)
                                <option value="{{ $y }}" {{ $tahunAktif == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold shadow-sm" style="border-radius: 10px;">
                        <i class="fas fa-sync-alt mr-2"></i> Perbarui Visualisasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- INDIKATOR KINERJA UTAMA (KPI) --}}
    <div class="row mt-4">
        @php
            $kpis = [
                ['label'=>'Volume Distribusi','value'=>$totalDistribusi ?? 0,'icon'=>'fa-shipping-fast','color'=>'primary','trend'=>'+12%'],
                ['label'=>'Alur Masuk Stok','value'=>$stokMasuk ?? 0,'icon'=>'fa-box-open','color'=>'success','trend'=>'+5%'],
                ['label'=>'Jaringan Aktif','value'=>$outletAktif ?? 0,'icon'=>'fa-store-alt','color'=>'info','trend'=>'Stabil'],
                ['label'=>'Peringatan Kritis','value'=>$stokMenipis ?? 0,'icon'=>'fa-exclamation-triangle','color'=>'danger','trend'=>'Butuh Tindakan'],
            ];
        @endphp

        @foreach($kpis as $kpi)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card-modern shadow-sm h-100 bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="icon-box-modern bg-{{ $kpi['color'] }}-light text-{{ $kpi['color'] }}" style="background-color: rgba(var(--{{ $kpi['color'] }}-rgb), 0.1)">
                            <i class="fas {{ $kpi['icon'] }} fa-lg text-{{ $kpi['color'] }}"></i>
                        </div>
                        <span class="badge badge-soft-{{ $kpi['color'] }} align-self-start py-1 px-2">{{ $kpi['trend'] }}</span>
                    </div>
                    <h6 class="text-muted small font-weight-bold text-uppercase mb-1">{{ $kpi['label'] }}</h6>
                    <h2 class="font-weight-bold text-dark mb-0">{{ number_format($kpi['value']) }}</h2>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- SEKSI ANALITIK --}}
    <div class="row">
        {{-- ANALITIK OUTLET --}}
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold text-dark">Outlet Performa Terbaik</h5>
                        <span class="badge badge-pill badge-primary">Peringkat Aktivitas</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-luxury mb-0">
                            <thead>
                                <tr>
                                    <th class="px-4">Identitas Outlet</th>
                                    <th class="text-center">Metrik Aktivitas</th>
                                    <th class="px-4 text-right">Progres</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($outletTeraktif ?? [] as $item)
                                <tr>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm mr-3 bg-light rounded-circle text-center" style="width: 35px; height: 35px; line-height: 35px;">
                                                <i class="fas fa-store text-muted small"></i>
                                            </div>
                                            <span class="font-weight-bold">{{ $item->nama_outlet }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center font-weight-bold text-primary">{{ $item->total }} <small>Trans.</small></td>
                                    <td class="px-4">
                                        <div class="progress progress-custom">
                                            @php $percentage = min(($item->total / 100) * 100, 100); @endphp
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-5 text-muted small italic">Tidak ada aktivitas terdeteksi pada periode ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- TREN INVENTARIS --}}
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100 bg-dark text-white" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0 py-4">
                    <h5 class="m-0 font-weight-bold">Penggunaan Bahan Baku Tertinggi</h5>
                </div>
                <div class="card-body">
                    @forelse ($bahanTerbanyak ?? [] as $item)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="font-weight-bold">{{ $item->nama_bahan }}</span>
                            <span class="text-success font-weight-bold">{{ number_format($item->total) }} Unit</span>
                        </div>
                        <div class="progress progress-custom bg-secondary" style="height: 4px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 75%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 opacity-50">Log inventaris kosong.</div>
                    @endforelse
                </div>
                <div class="card-footer bg-transparent border-top border-secondary py-3">
                    <a href="{{ route('admin.laporan.stok-outlet') }}" class="btn btn-outline-light btn-block btn-sm">Tinjau Log Inventaris Lengkap</a>
                </div>
            </div>
        </div>
    </div>

    {{-- NAVIGASI FOOTER GLOBAL --}}
    <div class="row no-print">
        <div class="col-12 text-center mt-2">
            <p class="text-muted small mb-3">Sistem Intelijen Bisnis v2.1 • Manajemen Resmi Teh Kita</p>
            <div class="btn-group shadow-sm bg-white" style="border-radius: 30px; padding: 5px;">
                <a href="{{ route('admin.laporan.distribusi') }}" class="btn btn-white border-0 px-4 py-2 small font-weight-bold">
                    <i class="fas fa-truck text-success mr-2"></i> Riwayat Distribusi
                </a>
                <div class="vr bg-light" style="width: 1px;"></div>
                <a href="{{ route('admin.laporan.stok-kritis') }}" class="btn btn-white border-0 px-4 py-2 small font-weight-bold">
                    <i class="fas fa-radiation text-danger mr-2"></i> Analisis Risiko Stok
                </a>
            </div>
        </div>
    </div>
</div>
@endsection