@extends('layouts.main')

@section('title', 'Dashboard Admin')
@section('page', 'Dashboard Admin')

@section('content')

{{-- 1. STATISTIC CARDS --}}
<div class="row">
    <div class="col-lg-3 col-md-6 mb-4">
        <a href="{{ route('admin.outlet.index') }}" class="text-decoration-none">
            <div class="card dashboard-card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-1">{{ $outlet ?? 0 }}</h3>
                        <p class="text-muted mb-0">Total Outlet</p>
                    </div>
                    <div class="dashboard-icon bg-soft-success">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <a href="{{ route('admin.bahan.index') }}" class="text-decoration-none">
            <div class="card dashboard-card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-1">{{ $bahan ?? 0 }}</h3>
                        <p class="text-muted mb-0">Total Bahan</p>
                    </div>
                    <div class="dashboard-icon bg-soft-info">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <a href="{{ route('admin.stok-masuk.index') }}" class="text-decoration-none">
            <div class="card dashboard-card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-1">{{ $stokMasuk ?? 0 }}</h3>
                        <p class="text-muted mb-0">Stok Masuk</p>
                    </div>
                    <div class="dashboard-icon bg-soft-warning">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <a href="{{ route('admin.distribusi.index') }}" class="text-decoration-none">
            <div class="card dashboard-card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-1">{{ $distribusi ?? 0 }}</h3>
                        <p class="text-muted mb-0">Total Distribusi</p>
                    </div>
                    <div class="dashboard-icon bg-soft-danger">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- 2. MONITORING REAL-TIME --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-desktop text-primary me-2"></i> Monitoring Pemakaian Outlet Hari Ini</h5>
                <span class="badge bg-soft-primary text-primary">{{ date('d M Y') }}</span>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Outlet</th>
                            <th width="35%">Progress Pemakaian</th>
                            <th class="text-center">Realisasi / Target</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monitoringOutlets as $m)
                        <tr>
                            <td class="fw-bold text-dark">{{ $m->nama }}</td>
                            <td>
                                <div class="progress" style="height: 12px; border-radius: 10px; background-color: #f0f0f0;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $m->warna }}" 
                                         role="progressbar" 
                                         style="width: {{ min($m->persentase, 100) }}%">
                                    </div>
                                </div>
                                <small class="text-muted mt-1 d-block">{{ number_format($m->persentase, 1) }}% Kapasitas Terpakai</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border py-2 px-3">
                                    {{ $m->realisasi }} / {{ $m->target }} <small>Unit</small>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $m->warna }} py-2 px-3 shadow-sm" style="min-width: 90px;">
                                    {{ $m->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada aktivitas hari ini</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- 4. GRAFIK & KALENDER --}}
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-semibold mb-0">Grafik Pemakaian 5 Outlet</h5>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 350px; width: 100%;">
                    <canvas id="pemakaianChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-semibold mb-0">Kalender Distribusi</h5>
            </div>
            <div class="card-body">
                <div id="calendar-container" style="height: 350px;">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 5. TABLE SECTION --}}
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-semibold mb-0">Data Outlet Terbaru</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr><th>Nama Outlet</th><th>No HP</th></tr>
                    </thead>
                    <tbody>
                        @foreach($outlets as $o)
                        <tr><td class="fw-semibold">{{ $o->nama_outlet }}</td><td>{{ $o->no_hp }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-semibold mb-0">Stok Masuk Terbaru</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr><th>Bahan</th><th>Jumlah</th></tr>
                    </thead>
                    <tbody>
                        @foreach($latestStokMasuk as $s)
                        <tr><td class="fw-semibold">{{ $s->bahan->nama_bahan ?? '-' }}</td><td><span class="badge bg-success">{{ $s->jumlah }}</span></td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- 3. RADAR STOK & LATEST CHAT --}}
<div class="row mb-4">
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm border-0 h-100">
            
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i> 
                    Radar Stok Kritis (<= 5)
                </h5>

                {{-- Tombol Lihat Semua --}}
                <a href="{{ route('admin.laporan.stok-kritis') }}" 
                   class="btn btn-sm btn-outline-danger">
                    Lihat Semua
                </a>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-danger text-white">
                        <tr>
                            <th>Outlet</th>
                            <th>Bahan</th>
                            <th class="text-center">Sisa</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokKritis as $sk)
                        <tr>
                            <td class="fw-bold">
                                {{ $sk->outlet->nama_outlet ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $sk->bahan->nama_bahan ?? 'N/A' }}
                            </td>

                            <td class="text-center">
                                <span class="badge bg-danger rounded-pill px-3">
                                    {{ $sk->stok }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-danger-subtle text-danger px-3">
                                    Kritis
                                </span>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('admin.laporan.stok-kritis') }}" 
                                   class="btn btn-sm btn-outline-danger">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                ✅ Stok semua outlet aman
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    
<div class="col-lg-5 mb-4">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-comments text-primary me-2"></i> Chat Terbaru</h5>
            @if($unreadCount > 0) <span class="badge bg-danger rounded-pill">{{ $unreadCount }} Baru</span> @endif
        </div>
        
        <div class="card-body p-0" style="max-height: 380px; overflow-y: auto;">
            <div class="list-group list-group-flush">
                @forelse($latestChats as $chat)
                    @php 
                        $isMe = $chat->sender_id === auth()->id();
                        // Tentukan siapa lawan bicaranya
                        $opponent = $isMe ? $chat->receiver : $chat->sender;
                        // Logika Nama: Jika punya outlet, tampilkan nama outlet, jika tidak tampilkan nama user
                        $displayName = $opponent->outlet ? $opponent->outlet->nama_outlet : $opponent->name;
                    @endphp
                    
                    <a href="{{ route('chat.show', $opponent->id) }}" class="list-group-item list-group-item-action border-0 px-3 py-3">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                {{-- Inisial berdasarkan Nama Outlet --}}
                                <div class="bg-soft-primary text-primary rounded-circle d-flex justify-content-center align-items-center fw-bold me-3" style="width: 40px; height: 40px; font-size: 0.8rem;">
                                    {{ strtoupper(substr($displayName, 0, 1)) }}
                                </div>
                                <div style="max-width: 180px;">
                                    {{-- Nama Outlet yang muncul di sini --}}
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">{{ $displayName }}</h6>
                                    <p class="mb-0 text-muted text-truncate small">
                                        {{ $isMe ? 'Anda: ' : '' }}{{ $chat->message }}
                                    </p>
                                </div>
                            </div>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ $chat->created_at->diffForHumans(null, true) }}</small>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-comment-slash fa-2x text-light mb-2"></i>
                        <p class="text-muted small">Tidak ada pesan</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="card-footer bg-white border-0 text-center py-3">
            <a href="{{ route('chat.index') }}" class="text-primary fw-bold text-decoration-none small">Lihat Semua Pesan <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>
</div>
</div>

@endsection

@push('js')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Grafik
    const ctx = document.getElementById('pemakaianChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: @json($pemakaianChart ?? []),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // Kalender
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: '100%',
            headerToolbar: { left: 'prev,next', center: 'title', right: 'today' },
            events: @json($calendarEvents ?? [])
        });
        calendar.render();
    }
});
</script>
@endpush