@extends('layouts.main')

@section('title', 'Dashboard Admin')
@section('page', 'Dashboard Admin')

@section('content')

{{-- ================= STATISTIC CARDS ================= --}}
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


{{-- ================= GRAFIK & KALENDER ================= --}}
<div class="row">

    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0">
                <h5 class="fw-semibold mb-0">Grafik Pemakaian 5 Outlet</h5>
            </div>
            <div class="card-body">
                <canvas id="pemakaianChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0">
                <h5 class="fw-semibold mb-0">Kalender Distribusi</h5>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

</div>


{{-- ================= TABLE SECTION ================= --}}
<div class="row">

    {{-- DATA OUTLET --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0">
                <h5 class="fw-semibold mb-0">Data Outlet</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Outlet</th>
                            <th>Admin</th>
                            <th>No HP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($outlets as $o)
                        <tr>
                            <td class="fw-semibold">{{ $o->nama_outlet }}</td>
                            <td>{{ $o->user->name ?? '-' }}</td>
                            <td>{{ $o->no_hp }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- STOK MASUK --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0">
                <h5 class="fw-semibold mb-0">Stok Masuk Terbaru</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Bahan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestStokMasuk as $s)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($s->tanggal)->format('d M Y') }}</td>
                            <td class="fw-semibold">{{ $s->bahan->nama_bahan ?? '-' }}</td>
                            <td><span class="badge bg-success">{{ $s->jumlah }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- DISTRIBUSI --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0">
                <h5 class="fw-semibold mb-0">Distribusi Terbaru</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Outlet</th>
                            <th>Bahan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestDistribusi as $d)
                        <tr>
                            <td>{{ $d->tanggal }}</td>
                            <td>{{ $d->outlet->nama_outlet }}</td>
                            <td>{{ $d->bahan->nama_bahan }}</td>
                            <td><span class="badge bg-primary">{{ $d->jumlah }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CHAT --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-semibold mb-0">Chat Outlet</h5>
                @if($unreadCount > 0)
                    <span class="badge bg-danger">{{ $unreadCount }} Baru</span>
                @endif
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Outlet</th>
                            <th>Pesan</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
<tbody>
@forelse($latestChats as $chat)

@php
    // Tentukan lawan chat
    $isSenderMe = $chat->sender_id == auth()->id();
    $targetUser = $isSenderMe ? $chat->receiver : $chat->sender;
@endphp

<tr onclick="window.location='{{ route('chat.show', $targetUser->id) }}'"
    style="cursor:pointer; transition:0.2s;"
    onmouseover="this.style.backgroundColor='#f8f9fa'"
    onmouseout="this.style.backgroundColor=''">

    <td class="fw-semibold">
        {{ $targetUser->outlet->nama_outlet ?? $targetUser->name }}
    </td>

    <td>
        {{ \Illuminate\Support\Str::limit($chat->message, 40) }}
    </td>

    <td>
        <small class="text-muted">
            {{ $chat->created_at->diffForHumans() }}
        </small>
    </td>

</tr>

@empty
<tr>
    <td colspan="3" class="text-center text-muted">
        Belum ada chat
    </td>
</tr>
@endforelse
</tbody>             
</table>
            </div>
        </div>
    </div>

</div>

@endsection


@push('js')
<script>
document.addEventListener("DOMContentLoaded", function () {

    // ================= CHART =================
    const ctx = document.getElementById('pemakaianChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: @json($pemakaianChart ?? []),
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                tension: 0.4
            }
        });
    }

    // ================= FULLCALENDAR PREMIUM =================
    const calendarEl = document.getElementById('calendar');

    if (calendarEl) {

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 500,
            events: @json($calendarEvents ?? []),

            eventDidMount: function(info) {
                // Tooltip hover
                info.el.setAttribute(
                    "title",
                    "Jumlah Distribusi: " + info.event.extendedProps.jumlah
                );
            },

            eventClick: function(info) {
                if (info.event.extendedProps.url) {
                    window.location.href = info.event.extendedProps.url;
                }
            }
        });

        calendar.render();
    }

});
</script>
@endpush

