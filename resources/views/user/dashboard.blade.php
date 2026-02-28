@extends('layouts.main')

@section('title', 'Dashboard | ' . (auth()->user()->outlet->nama_outlet ?? 'Outlet'))
@section('page', 'Dashboard')

@section('content')

{{-- 1. Welcome Header --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between bg-white p-4 rounded-3 shadow-sm border-left-success" style="border-left: 5px solid #28a745;">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-store text-success me-2"></i> {{ auth()->user()->outlet->nama_outlet ?? 'Outlet Belum Terdaftar' }}</h3>
                <p class="text-muted mb-0">Status Sistem: <span class="text-success fw-bold">Online & Terpantau</span></p>
            </div>
            <div class="text-end d-none d-md-block">
                <span class="badge bg-light text-success border px-3 py-2 rounded-pill">
                    <i class="fas fa-calendar-alt me-1"></i> {{ date('d M Y') }}
                </span>
            </div>
        </div>
    </div>
</div>

{{-- 2. Statistik Cards --}}
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-3 bg-light me-3" style="width: 70px; height: 70px; background-color: rgba(40, 167, 69, 0.1) !important;">
                    <i class="fas fa-box fa-2x text-success"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $totalStok ?? 0 }}</h4>
                    <small class="text-muted">Bahan Tersedia</small>
                    <div class="mt-1">
                        <span class="badge rounded-pill {{ ($totalStok ?? 0) < 50 ? 'bg-danger' : 'bg-success' }}" style="font-size: 10px;">
                            <i class="fas {{ ($totalStok ?? 0) < 50 ? 'fa-exclamation-circle' : 'fa-check-circle' }} me-1"></i> {{ $statusStok ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-light" style="width: 60px; height: 60px; background-color: rgba(255, 193, 7, 0.1) !important;">
                        <i class="fas fa-utensils fa-xl text-warning"></i>
                    </div>
                    <div class="text-end">
                        <h4 class="fw-bold mb-0 text-dark">{{ $pemakaianHariIni ?? 0 }}</h4>
                        <small class="text-muted">Pemakaian Hari Ini</small>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted" style="font-size: 11px;">Target: <strong>{{ $target ?? 0 }} unit</strong></small>
                        <small class="fw-bold {{ ($persentaseTarget ?? 0) >= 100 ? 'text-success' : 'text-muted' }}" style="font-size: 11px;">
                            {{ number_format($persentaseTarget ?? 0, 0) }}%
                        </small>
                    </div>
                    <div class="progress" style="height: 8px; background-color: #f0f0f0; border-radius: 10px;">
                        <div class="progress-bar {{ $warnaProgress ?? 'bg-success' }} {{ ($persentaseTarget ?? 0) >= 90 ? 'progress-bar-animated progress-bar-striped' : '' }}" 
                             role="progressbar" style="width: {{ min(($persentaseTarget ?? 0), 100) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-3 bg-light me-3" style="width: 70px; height: 70px; background-color: rgba(23, 162, 184, 0.1) !important;">
                    <i class="fas fa-truck-loading fa-2x text-info"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $distribusiTotal ?? 0 }}</h4>
                    <small class="text-muted">Stok Masuk (Bulan Ini)</small>
                    <div class="mt-1">
                        <small class="text-info fw-bold" style="font-size: 10px;">
                            <i class="fas fa-clock me-1"></i> {{ $infoMasuk ?? 'Belum ada data' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3. Chart & Activity Feed --}}
<div class="row mb-4">
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-chart-line text-success me-2"></i>Tren Pemakaian 7 Hari Terakhir</h6>
            </div>
            <div class="card-body">
                <div style="height: 300px;"><canvas id="usageChart"></canvas></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0 text-primary"><i class="fas fa-bolt me-2"></i>Live Activity Feed</h6>
            </div>
            <div class="card-body p-3">
                <div class="timeline-simple" style="max-height: 350px; overflow-y: auto; overflow-x: hidden;">
                    @forelse($activityFeeds as $feed)
                        @php
                            $namaBahan = strtolower($feed->bahan->nama_bahan ?? 'bahan');
                            $bgClass = 'bg-secondary';
                            $iconDefault = 'fa-box';
                            
                            // Logika Ikon Berdasarkan Nama Bahan
                            if (str_contains($namaBahan, 'cup')) {
                                $bgClass = 'bg-primary'; $iconDefault = 'fa-glass-whiskey';
                            } elseif (str_contains($namaBahan, 'gula') || str_contains($namaBahan, 'aren')) {
                                $bgClass = 'bg-warning'; $iconDefault = 'fa-flask';
                            } elseif (str_contains($namaBahan, 'teh')) {
                                $bgClass = 'bg-success'; $iconDefault = 'fa-leaf';
                            }

                            // Cek jika aktivitas adalah Waste
                            if (isset($feed->tipe) && $feed->tipe == 'waste') {
                                $bgClass = 'bg-danger'; $iconClass = 'fa-trash-alt';
                            } else {
                                $iconClass = ($feed->tipe_aktivitas == 'distribusi') ? 'fa-truck' : $iconDefault;
                            }
                        @endphp
                        <div class="d-flex mb-3 border-bottom pb-2">
                            <div class="me-3 position-relative">
                                <span class="btn btn-sm {{ $bgClass }} rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm" style="width:40px;height:40px;">
                                    <i class="fas {{ $iconClass }} fa-sm"></i>
                                </span>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle {{ $feed->tipe_aktivitas == 'pemakaian' ? 'bg-danger' : 'bg-success' }} p-1" style="border: 2px solid white;">
                                    <i class="fas {{ $feed->tipe_aktivitas == 'pemakaian' ? 'fa-arrow-down' : 'fa-arrow-up' }}" style="font-size: 7px;"></i>
                                </span>
                            </div>
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-start">
                                    <strong class="small text-dark">{{ $feed->bahan->nama_bahan ?? 'Bahan' }}</strong>
                                    <small class="text-muted" style="font-size: 9px;">{{ $feed->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 text-muted" style="font-size: 11px;">
                                    <span class="fw-bold {{ (isset($feed->tipe) && $feed->tipe == 'waste') ? 'text-danger' : ($feed->tipe_aktivitas == 'pemakaian' ? 'text-warning' : 'text-info') }}">
                                        {{ (isset($feed->tipe) && $feed->tipe == 'waste') ? 'Waste/Rusak' : ucfirst($feed->tipe_aktivitas) }}
                                    </span> sebanyak <strong>{{ $feed->jumlah }} unit</strong>
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">Belum ada aktivitas terekam.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 4. Baris Tabel Bawah --}}
<div class="row">
    {{-- Stok Saat Ini (Kiri) --}}
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between" style="display: flex !important; justify-content: space-between !important; width: 100%;">
                <h6 class="fw-bold mb-0 text-success">
                    <i class="fas fa-layer-group me-2"></i>Stok Saat Ini
                </h6>
                <a href="{{ route('user.stok-outlet.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-sm" style="font-size: 11px; font-weight: 600; margin-left: auto;">
                    Detail <i class="fas fa-chevron-right ms-1"></i>
                </a>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
<table class="table table-hover mb-0 align-middle" style="width: 100%; table-layout: fixed; border-collapse: collapse;">
    <thead style="background-color: #f8faf9;">
        <tr>
            {{-- 1. Nama Bahan --}}
            <th class="ps-3 py-3 border-0 text-muted small fw-bold" style="width: 40%;">Bahan</th>
            
            {{-- 2. Stok --}}
            <th class="py-3 border-0 text-muted small fw-bold text-center" style="width: 25%;">Stok</th>
            
            {{-- 3. Status (Disesuaikan agar sejajar dengan badge) --}}
            <th class="py-3 border-0 text-muted small fw-bold text-center" style="width: 35%;">
                <div style="padding-left: 20px;">Status</div>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($stokOutlets as $s)
        <tr onclick="window.location='{{ route('user.stok-outlet.index') }}'" style="cursor: pointer; border-bottom: 1px solid #f8faf9;">
            {{-- Nama Bahan --}}
            <td class="ps-3 py-3">
                <span class="fw-bold text-dark d-block text-truncate">{{ $s->bahan->nama_bahan ?? 'Unknown' }}</span>
            </td>

            {{-- Angka Stok --}}
            <td class="py-3 text-center">
                <span class="fw-bold text-secondary" style="font-size: 15px;">
                    {{ number_format($s->stok, 0, ',', '.') }}
                </span>
            </td>

            {{-- Status Badge (Sekarang pakai text-center + padding agar sejajar header) --}}
            <td class="py-3 text-center">
                <div style="display: flex; justify-content: center; padding-left: 20px;">
                    @if ($s->stok == 0)
                        <span class="badge rounded-pill px-3 py-2 shadow-sm" style="background-color: rgba(220, 53, 69, 0.1); color: #dc3545; font-size: 11px; min-width: 95px; border: 1px solid rgba(220, 53, 69, 0.2);">
                            <i class="fas fa-times-circle me-1"></i> Habis
                        </span>
                    @elseif ($s->stok <= 10)
                        <span class="badge rounded-pill px-3 py-2 shadow-sm" style="background-color: rgba(255, 193, 7, 0.1); color: #856404; font-size: 11px; min-width: 95px; border: 1px solid rgba(255, 193, 7, 0.2);">
                            <i class="fas fa-exclamation-triangle me-1"></i> Tipis
                        </span>
                    @else
                        <span class="badge rounded-pill px-3 py-2 shadow-sm" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745; font-size: 11px; min-width: 95px; border: 1px solid rgba(40, 167, 69, 0.2);">
                            <i class="fas fa-check-circle me-1"></i> Aman
                        </span>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3 text-center">
                 <small class="text-muted" style="font-size: 10px;">
                    <i class="fas fa-info-circle me-1"></i> Klik baris untuk manajemen stok
                 </small>
            </div>
        </div>
    </div>

    {{-- Riwayat Pemakaian Terbaru (Kanan) --}}
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm border-0 h-100">
           <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between" style="display: flex !important; justify-content: space-between !important; width: 100%;">
                <h6 class="fw-bold mb-0 text-warning">
                    <i class="fas fa-history me-2"></i>Riwayat Pemakaian Terbaru
                </h6>
                <a href="{{ route('user.riwayat_pemakaian') }}" class="btn btn-sm btn-outline-warning rounded-pill px-3 shadow-sm" style="font-size: 11px; font-weight: 600; margin-left: auto;">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 text-center align-middle">
                        <thead style="background-color: #fefaf4;">
                            <tr>
                                <th class="py-3 border-0 text-muted small">Bahan</th>
                                <th class="py-3 border-0 text-muted small">Jumlah</th>
                                <th class="py-3 border-0 text-muted small">Tanggal</th>
                                <th class="py-3 border-0 text-muted small">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pemakaians as $p)
                            <tr onclick="window.location='{{ route('user.riwayat_pemakaian') }}'" style="cursor: pointer;">
                                <td class="py-3 fw-bold text-dark">{{ $p->bahan->nama_bahan ?? 'Unknown' }}</td>
                                <td class="py-3">
                                    <span class="badge bg-light text-dark border px-2 py-1">{{ $p->jumlah }} unit</span>
                                </td>
                                <td class="py-3 text-muted" style="font-size: 12px;">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                                <td class="py-3">
                                    {{-- REVISI LOGIKA STATUS WASTE --}}
                                    @if(isset($p->tipe) && $p->tipe == 'waste')
                                        <span class="badge rounded-pill px-2 py-1" style="background-color: rgba(220, 53, 69, 0.1); color: #dc3545; font-size: 10px;">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Waste: {{ $p->keterangan ?? 'Rusak' }}
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-2 py-1" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745; font-size: 10px;">
                                            <i class="fas fa-check-circle me-1"></i>Verified (Jualan)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    Chart.register(ChartDataLabels);
    const ctx = document.getElementById('usageChart').getContext('2d');
    let gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(40, 167, 69, 0.2)');
    gradient.addColorStop(1, 'rgba(40, 167, 69, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels ?? []) !!},
            datasets: [{
                label: 'Unit Terpakai',
                data: {!! json_encode($chartData ?? []) !!},
                borderColor: '#28a745',
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#28a745',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                datalabels: {
                    anchor: 'end', align: 'top', color: '#28a745',
                    font: { weight: 'bold', size: 11 },
                    formatter: (v) => v > 0 ? v : ''
                }
            },
            scales: { 
                y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush