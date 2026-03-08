@extends('layouts.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('templates/dist/css/laporn-user.css') }}">
@endpush

@section('title', 'Laporan Distribusi — Teh Kita')

@section('content')
<div class="container-fluid py-5 px-lg-5">

    {{-- EXECUTIVE HEADER --}}
    <div class="executive-header d-flex align-items-center justify-content-between mb-5">
        <div class="header-text-group">
            <h6 class="text-accent-gold font-weight-bold text-uppercase mb-2 fs-xs">
                <i class="fas fa-chart-line mr-2"></i> Laporan
            </h6>
            <h1 class="display-header mb-0">Rekap Distribusi</h1>
            <p class="text-white-80 mb-0 mt-2 italic font-weight-light">
                Pantau alur distribusi barang masuk ke outlet secara mendalam.
            </p>
        </div>
        {{-- Tombol Export Master PDF telah dihapus sesuai permintaan --}}
    </div>

    {{-- STATISTIC BOXES --}}
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="icon-square bg-soft-success text-success mr-3">
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                    <div>
                        <p class="text-uppercase small fw-bold text-muted mb-1" style="letter-spacing: 1px;">Total Diterima</p>
                        <h3 class="fw-extrabold mb-0 text-dark">{{ number_format($distribusi->sum('jumlah'), 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="icon-square bg-soft-success text-success mr-3">
                        <i class="fas fa-truck-loading fa-2x"></i>
                    </div>
                    <div>
                        <p class="text-uppercase small fw-bold text-muted mb-1" style="letter-spacing: 1px;">Total Bahan</p>
                        <h3 class="fw-extrabold mb-0 text-dark">{{ $distribusi->count() }} <small class="h6 text-muted font-weight-bold">BATCH</small></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="icon-square bg-soft-info text-info mr-3">
                        <i class="fas fa-history fa-2x"></i>
                    </div>
                    <div>
                        <p class="text-uppercase small fw-bold text-muted mb-1" style="letter-spacing: 1px;">Update Terakhir</p>
                        @php $last = $distribusi->first(); @endphp
                        @if($last)
                            <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($last->updated_at)->translatedFormat('d M Y') }}</div>
                            <small class="text-info fw-bold">{{ \Carbon\Carbon::parse($last->updated_at)->format('H:i') }} WIB</small>
                        @else
                            <h3 class="fw-extrabold mb-0">-</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN TABLE --}}
    <div class="card border-0 shadow-lg table-container">
        <div class="card-header bg-white border-0 py-4 px-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-extrabold text-dark mb-0">Log Distribusi Bulanan</h5>
                    <div class="h-line bg-success mt-2"></div>
                </div>
                <span class="badge badge-premium-success px-3 py-2"> DATA</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-luxury table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="80" class="pl-4 text-center">No</th>
                            <th>Periode Operasional</th>
                            <th>Outlet Tujuan</th>
                            <th class="text-center">Jumlah Bahan</th>
                            <th class="text-right pr-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $grouped = $distribusi->groupBy(function($item){
                                return \Carbon\Carbon::parse($item->tanggal)->format('F Y');
                            });
                        @endphp

                        @forelse ($grouped as $periode => $items)
                        <tr>
                            <td class="pl-4 text-center">
                                <span class="badge badge-light text-muted fw-bold">#{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <div class="fw-extrabold text-success text-uppercase mb-0" style="font-size: 0.9rem;">
                                    {{ \Carbon\Carbon::parse($items->first()->tanggal)->translatedFormat('F Y') }}
                                </div>
                                <small class="text-muted italic">Rekapitulasi Sistem</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-box-small mr-2">
                                        <i class="fas fa-store text-success"></i>
                                    </div>
                                    <span class="fw-bold text-dark">{{ $items->first()->outlet->nama_outlet ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge-count-premium">
                                    {{ number_format($items->sum('jumlah'), 0, ',', '.') }} <small>ITEM</small>
                                </span>
                            </td>
                            <td class="text-right pr-4">
                                @php $periodeParam = \Carbon\Carbon::parse($items->first()->tanggal)->format('Y-m'); @endphp
                                <div class="btn-group-luxury shadow-sm">
                                    <a href="{{ route('user.laporan.distribusi.detail', $periodeParam) }}" class="btn-luxe-icon" title="Lihat">
                                        <i class="fas fa-eye text-primary"></i>
                                    </a>
                                    <a href="{{ route('user.laporan.distribusi.detail.pdf', $periodeParam) }}" class="btn-luxe-icon" title="Unduh">
                                        <i class="fas fa-file-pdf text-danger"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted fw-bold">Belum ada data distribusi tersedia.</p>
                                </div>
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