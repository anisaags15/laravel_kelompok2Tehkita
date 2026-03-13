@extends('layouts.main')

@section('title', 'Jadwal Distribusi')

@push('css')
<link rel="stylesheet" href="{{ asset('templates/dist/css/custom-user.css') }}">
<style>
    .jadwal-hero {
        background: linear-gradient(135deg, #1a7a3c 0%, #2ecc71 100%);
        border-radius: 16px;
        padding: 28px 32px;
        color: white;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    .jadwal-hero::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,0.07);
        pointer-events: none;
    }
    .jadwal-hero::after {
        content: '';
        position: absolute;
        bottom: -70px; right: 60px;
        width: 260px; height: 260px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
        pointer-events: none;
    }
    .hero-icon-wrap {
        width: 56px; height: 56px;
        background: rgba(255,255,255,0.18);
        border: 1.5px solid rgba(255,255,255,0.3);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        backdrop-filter: blur(4px);
    }
    .hero-icon-wrap i { font-size: 1.5rem; }
    .jadwal-hero h2 { font-size: 1.4rem; font-weight: 700; margin: 0 0 4px; line-height: 1.3; }
    .jadwal-hero p  { opacity: 0.82; margin: 0; font-size: 0.88rem; }
    .stat-pill {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 50px; padding: 6px 18px;
        font-size: 0.82rem; font-weight: 600;
        white-space: nowrap;
        position: relative; z-index: 1;
    }
    .stat-pill .dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: rgba(255,255,255,0.7);
        display: inline-block; flex-shrink: 0;
    }

    /* Jadwal card upcoming */
    .jadwal-card-upcoming {
        border-radius: 14px;
        border: 1.5px solid #d4edda;
        background: white;
        padding: 20px 24px;
        margin-bottom: 14px;
        box-shadow: 0 2px 12px rgba(40,167,69,0.07);
        transition: box-shadow 0.2s, transform 0.2s;
        position: relative;
        overflow: hidden;
    }
    .jadwal-card-upcoming::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: linear-gradient(135deg, #1a7a3c, #2ecc71);
        border-radius: 4px 0 0 4px;
    }
    .jadwal-card-upcoming:hover {
        box-shadow: 0 6px 20px rgba(40,167,69,0.15);
        transform: translateY(-1px);
    }

    /* Jadwal card telat */
    .jadwal-card-telat {
        border-radius: 14px;
        border: 1.5px solid #ffd8d8;
        background: white;
        padding: 20px 24px;
        margin-bottom: 14px;
        box-shadow: 0 2px 12px rgba(220,53,69,0.06);
        position: relative;
        overflow: hidden;
    }
    .jadwal-card-telat::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: #dc3545;
        border-radius: 4px 0 0 4px;
    }

    /* Jadwal card selesai */
    .jadwal-card-selesai {
        border-radius: 14px;
        border: 1.5px solid #e9ecef;
        background: #fafafa;
        padding: 18px 24px;
        margin-bottom: 10px;
        position: relative;
        overflow: hidden;
        opacity: 0.85;
    }
    .jadwal-card-selesai::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: #adb5bd;
        border-radius: 4px 0 0 4px;
    }

    .badge-upcoming {
        background: #fff8e1; color: #e65100;
        border: 1.5px solid #ffcc02; border-radius: 50px;
        padding: 5px 14px; font-size: 0.78rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .badge-selesai {
        background: #e8f5e9; color: #1b5e20;
        border: 1.5px solid #66bb6a; border-radius: 50px;
        padding: 5px 14px; font-size: 0.78rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .badge-telat {
        background: #fdecea; color: #c62828;
        border: 1.5px solid #ef9a9a; border-radius: 50px;
        padding: 5px 14px; font-size: 0.78rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .countdown-box {
        background: rgba(40,167,69,0.08);
        border: 1px solid rgba(40,167,69,0.2);
        border-radius: 10px;
        padding: 8px 16px;
        text-align: center;
        min-width: 100px;
        flex-shrink: 0;
    }
    .countdown-box .days {
        font-size: 1.6rem;
        font-weight: 800;
        color: #1a7a3c;
        line-height: 1;
    }
    .countdown-box .label {
        font-size: 0.7rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .section-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 700;
        color: #888;
        margin-bottom: 14px;
        padding-left: 4px;
    }
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #aaa;
    }
    .empty-state i { font-size: 2.5rem; opacity: 0.2; display: block; margin-bottom: 10px; }

    /* Dark mode */
    .dark-mode .jadwal-hero { background: linear-gradient(135deg, #145c2d 0%, #1a7a3c 100%); }
    .dark-mode .jadwal-card-upcoming { background: #1e2d24; border-color: #2d4a37; }
    .dark-mode .jadwal-card-telat { background: #2d1f1f; border-color: #5c2d2d; }
    .dark-mode .jadwal-card-selesai { background: #1a1e1a; border-color: #2a2e2a; }
    .dark-mode .countdown-box { background: rgba(46,204,113,0.08); border-color: rgba(46,204,113,0.2); }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">Jadwal Distribusi</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Jadwal Distribusi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        {{-- HERO --}}
        <div class="jadwal-hero">
            <div class="d-flex align-items-center w-100">
                <div class="hero-icon-wrap me-3">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <h2>Jadwal Pengiriman Barang</h2>
                    <p>Informasi jadwal distribusi dari Admin Pusat ke outlet kamu</p>
                </div>
                <div class="ms-auto ps-3">
                    <div class="stat-pill">
                        <i class="fas fa-clock" style="font-size:0.75rem;"></i>
                        {{ $jadwalUpcoming->count() }} Akan Datang
                        <span class="dot"></span>
                        <i class="fas fa-check-circle" style="font-size:0.75rem;"></i>
                        {{ $jadwalSelesai->count() }} Selesai
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">

                {{-- SECTION: AKAN DATANG --}}
                <p class="section-title"><i class="fas fa-clock me-2"></i>Jadwal Akan Datang</p>

                @forelse($jadwalUpcoming as $jadwal)
                    @php
                        $hariLagi = \Carbon\Carbon::today()->diffInDays($jadwal->tanggal_rencana);
                    @endphp
                    <div class="jadwal-card-upcoming">
                        <div class="d-flex align-items-center gap-3">
                            {{-- Countdown box --}}
                            <div class="countdown-box">
                                <div class="days">{{ $hariLagi }}</div>
                                <div class="label">hari lagi</div>
                            </div>
                            {{-- Info --}}
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                                    <div>
                                        <h6 class="fw-bold mb-1 text-adaptive">{{ $jadwal->keterangan }}</h6>
                                        <div class="text-success fw-bold" style="font-size:0.9rem;">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $jadwal->tanggal_rencana->format('d M Y') }}
                                        </div>
                                        @if($jadwal->catatan)
                                            <small class="text-muted mt-1 d-block">
                                                <i class="fas fa-sticky-note me-1"></i>{{ $jadwal->catatan }}
                                            </small>
                                        @endif
                                    </div>
                                    <span class="badge-upcoming">
                                        <i class="fas fa-clock"></i> Akan Datang
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p class="fw-bold mb-1">Belum ada jadwal upcoming</p>
                        <small>Admin pusat belum membuat jadwal distribusi berikutnya</small>
                    </div>
                @endforelse

                {{-- SECTION: TERLAMBAT --}}
                @if($jadwalTelat->count() > 0)
                    <p class="section-title mt-4"><i class="fas fa-exclamation-circle text-danger me-2"></i>Jadwal Belum Terkonfirmasi</p>
                    @foreach($jadwalTelat as $jadwal)
                        <div class="jadwal-card-telat">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <h6 class="fw-bold mb-1 text-adaptive">{{ $jadwal->keterangan }}</h6>
                                    <div class="text-danger fw-bold" style="font-size:0.9rem;">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $jadwal->tanggal_rencana->format('d M Y') }}
                                    </div>
                                    @if($jadwal->catatan)
                                        <small class="text-muted mt-1 d-block">
                                            <i class="fas fa-sticky-note me-1"></i>{{ $jadwal->catatan }}
                                        </small>
                                    @endif
                                </div>
                                <span class="badge-telat">
                                    <i class="fas fa-exclamation-triangle"></i> Belum Terkonfirmasi
                                </span>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- SECTION: RIWAYAT SELESAI --}}
                @if($jadwalSelesai->count() > 0)
                    <p class="section-title mt-4"><i class="fas fa-check-circle me-2"></i>Riwayat Distribusi</p>
                    @foreach($jadwalSelesai as $jadwal)
                        <div class="jadwal-card-selesai">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <h6 class="fw-bold mb-1 text-muted" style="font-size:0.9rem;">{{ $jadwal->keterangan }}</h6>
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $jadwal->tanggal_rencana->format('d M Y') }}
                                    </small>
                                    @if($jadwal->catatan)
                                        <small class="text-muted d-block">
                                            <i class="fas fa-sticky-note me-1"></i>{{ $jadwal->catatan }}
                                        </small>
                                    @endif
                                </div>
                                <span class="badge-selesai">
                                    <i class="fas fa-check-circle"></i> Selesai
                                </span>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>

    </div>
</div>
@endsection