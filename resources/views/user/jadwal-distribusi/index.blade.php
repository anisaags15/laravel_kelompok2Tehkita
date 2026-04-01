@extends('layouts.main')

@section('title', 'Jadwal Distribusi')

@push('css')
    {{-- CSS Terpisah --}}
    <link rel="stylesheet" href="{{ asset('templates/dist/css/custom-user.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/dist/css/jadwal-distribusi.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 fw-bold">Jadwal Distribusi</h1>
            </div>
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
                <div class="ms-auto ps-3 d-none d-md-block">
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
                            <div class="countdown-box">
                                <div class="days">{{ $hariLagi }}</div>
                                <div class="label">hari lagi</div>
                            </div>
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

                {{-- SECTION: TERLAMBAT/BELUM KONFIRMASI --}}
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