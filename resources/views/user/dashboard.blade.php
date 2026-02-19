@extends('layouts.main')

@section('title', 'Dashboard ' . (auth()->user()->outlet->nama_outlet ?? 'Outlet'))
@section('page', 'Dashboard')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between bg-white p-4 rounded-3 shadow-sm border-left-success" style="border-left: 5px solid #28a745;">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fas fa-store text-success me-2"></i>
                    Dashboard {{ auth()->user()->outlet->nama_outlet ?? 'Outlet' }}
                </h3>
                <p class="text-muted mb-0">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong></p>
            </div>
            <div class="text-end d-none d-md-block">
                <span class="badge bg-light text-success border px-3 py-2 rounded-pill">
                    <i class="fas fa-calendar-alt me-1"></i> {{ date('d M Y') }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalStok }}</h3>
                    <small class="text-muted">Jenis Bahan Tersedia</small>
                </div>
                <div class="p-3 rounded-3" style="background-color: #e8f5e9;"><i class="fas fa-box fa-2x text-success"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $pemakaianHariIni }}</h3>
                    <small class="text-muted">Pemakaian Hari Ini</small>
                </div>
                <div class="p-3 rounded-3" style="background-color: #fffde7;"><i class="fas fa-utensils fa-2x text-warning"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $distribusi }}</h3>
                    <small class="text-muted">Stok Masuk (Bulan Ini)</small>
                </div>
                <div class="p-3 rounded-3" style="background-color: #e1f5fe;"><i class="fas fa-truck-loading fa-2x text-info"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-chart-line text-success me-2"></i>Tren Pemakaian 7 Hari Terakhir
                </h6>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="usageChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0 text-success"><i class="fas fa-layer-group me-2"></i>Stok Saat Ini</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Bahan</th><th class="text-center">Stok</th></tr>
                        </thead>
                        <tbody>
                            @forelse($stokOutlets as $stok)
                            <tr>
                                <td class="small fw-bold">{{ $stok->bahan->nama_bahan ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $stok->stok > 10 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">
                                        {{ $stok->stok }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center text-muted small py-4">Belum ada data stok</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0 text-warning"><i class="fas fa-history me-2"></i>Riwayat Pemakaian Terbaru</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light text-muted small uppercase">
                            <tr>
                                <th class="ps-4">Bahan</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pemakaians as $pem)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $pem->bahan->nama_bahan ?? '-' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $pem->jumlah }} unit</span></td>
                                <td><small class="text-muted"><i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($pem->tanggal)->format('d M Y') }}</small></td>
                                <td class="text-center"><span class="badge bg-soft-success text-success">Selesai</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada riwayat pemakaian</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    const ctx = document.getElementById('usageChart').getContext('2d');
    
    // Gradient untuk area chart
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(40, 167, 69, 0.4)');
    gradient.addColorStop(1, 'rgba(40, 167, 69, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            // Data Labels (Contoh: 7 hari terakhir)
            labels: {!! json_encode($chartLabels ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!},
            datasets: [{
                label: 'Jumlah Pemakaian',
                data: {!! json_encode($chartData ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                borderColor: '#28a745',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4, // Membuat garis jadi melengkung (smooth)
                pointBackgroundColor: '#fff',
                pointBorderColor: '#28a745',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { drawBorder: false, color: '#f0f0f0' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush