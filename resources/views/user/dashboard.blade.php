@extends('layouts.main')

@section('title', 'Dashboard ' . (auth()->user()->outlet->nama_outlet ?? 'Outlet'))
@section('page', 'Dashboard')

@section('content')

{{-- Welcome Header --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between bg-white p-4 rounded-3 shadow-sm border-left-success" style="border-left: 5px solid #28a745;">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-store text-success me-2"></i> {{ auth()->user()->outlet->nama_outlet ?? 'Outlet' }}</h3>
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

{{-- Statistik Cards --}}
<div class="row mb-4">
    {{-- Card: Jenis Bahan --}}
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                {{-- Icon Fixed Size --}}
                <div class="d-flex align-items-center justify-content-center rounded-3 bg-soft-success me-3" style="width: 70px; height: 70px;">
                    <i class="fas fa-box fa-2x text-success"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $totalStok }}</h4>
                    <small class="text-muted">Bahan Tersedia</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Card: Pemakaian Hari Ini + PROGRESS BAR --}}
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    {{-- Icon Fixed Size --}}
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-soft-warning" style="width: 60px; height: 60px;">
                        <i class="fas fa-utensils fa-xl text-warning"></i>
                    </div>
                    <div class="text-end">
                        <h4 class="fw-bold mb-0 text-dark">{{ $pemakaianHariIni }}</h4>
                        <small class="text-muted">Pemakaian Hari Ini</small>
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted" style="font-size: 11px;">Target: <strong>{{ $target }} unit</strong></small>
                        <small class="fw-bold {{ $persentaseTarget >= 90 ? 'text-danger' : 'text-muted' }}" style="font-size: 11px;">
                            {{ number_format($persentaseTarget, 0) }}%
                        </small>
                    </div>
                    <div class="progress" style="height: 8px; background-color: #f0f0f0;">
                        <div class="progress-bar {{ $warnaProgress }} {{ $persentaseTarget >= 90 ? 'progress-bar-animated progress-bar-striped' : '' }}" 
                             role="progressbar" 
                             style="width: {{ min($persentaseTarget, 100) }}%">
                        </div>
                    </div>
                    @if($persentaseTarget >= 90)
                        <small class="text-danger mt-1 d-block fw-bold" style="font-size: 10px;">
                            <i class="fas fa-exclamation-triangle"></i> Hampir melebihi target harian!
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Card: Stok Masuk --}}
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                {{-- Icon Fixed Size --}}
                <div class="d-flex align-items-center justify-content-center rounded-3 bg-soft-info me-3" style="width: 70px; height: 70px;">
                    <i class="fas fa-truck-loading fa-2x text-info"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $distribusiTotal }}</h4>
                    <small class="text-muted">Total Stok Masuk</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Chart Pemakaian (Kiri) --}}
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

    {{-- Live Activity Feed (Kanan) --}}
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0 text-primary"><i class="fas fa-bolt me-2"></i>Live Activity Feed</h6>
            </div>
            <div class="card-body p-3">
                <div class="timeline-simple" style="max-height: 320px; overflow-y: auto;">
                    @forelse($activityFeeds as $feed)
                        <div class="d-flex mb-3 border-bottom pb-2">
                            <div class="me-3">
                                @if($feed->tipe_aktivitas == 'pemakaian')
                                    <span class="btn btn-sm btn-warning rounded-circle d-flex align-items-center justify-content-center" style="width:35px;height:35px;"><i class="fas fa-edit fa-xs text-white"></i></span>
                                @else
                                    <span class="btn btn-sm btn-info rounded-circle d-flex align-items-center justify-content-center" style="width:35px;height:35px;"><i class="fas fa-truck fa-xs text-white"></i></span>
                                @endif
                            </div>
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-start">
                                    <strong class="small text-dark text-capitalize">{{ $feed->tipe_aktivitas }}</strong>
                                    <small class="text-muted" style="font-size: 9px;">{{ $feed->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 text-muted" style="font-size: 11px;">
                                    {{ $feed->outlet->nama_outlet }} mencatat {{ $feed->jumlah }} unit {{ $feed->bahan->nama_bahan }}
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

{{-- Baris Tabel Bawah --}}
<div class="row">
    {{-- Tabel Stok --}}
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0 text-success"><i class="fas fa-layer-group me-2"></i>Stok Saat Ini</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 small">
                    <thead class="table-light"><tr><th class="ps-3">Bahan</th><th class="text-center">Sisa Stok</th></tr></thead>
                    <tbody>
                        @foreach($stokOutlets as $s)
                        <tr>
                            <td class="ps-3">
                                <span class="fw-bold text-dark">{{ $s->bahan->nama_bahan }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $s->stok < 10 ? 'bg-danger' : 'bg-success' }} rounded-pill px-3">
                                    {{ $s->stok }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3"><h6 class="fw-bold mb-0 text-warning"><i class="fas fa-history me-2"></i>Riwayat Pemakaian Terbaru</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 small text-center">
                        <thead class="table-light"><tr><th>Bahan</th><th>Jumlah</th><th>Tanggal</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($pemakaians as $p)
                            <tr>
                                <td class="fw-bold text-dark">{{ $p->bahan->nama_bahan }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $p->jumlah }} unit</span></td>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                                <td><span class="badge bg-soft-success text-success"><i class="fas fa-check-circle me-1"></i>Verified</span></td>
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
<script>
    const ctx = document.getElementById('usageChart').getContext('2d');
    let gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(40, 167, 69, 0.3)');
    gradient.addColorStop(1, 'rgba(40, 167, 69, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Unit Terpakai',
                data: {!! json_encode($chartData) !!},
                borderColor: '#28a745',
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#28a745',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { 
                y: { 
                    beginAtZero: true, 
                    grid: { color: '#f0f0f0' },
                    ticks: { stepSize: 20 }
                }, 
                x: { grid: { display: false } } 
            }
        }
    });
</script>
@endpush