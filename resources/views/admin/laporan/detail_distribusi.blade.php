@extends('layouts.main')

@section('title', 'Detail Distribusi')
@section('page', 'Detail Distribusi')

@push('css')
    <link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- TOP NAVIGATION & ACTIONS --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.laporan.distribusi') }}" class="btn btn-back border-0 mr-3">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
        <div>
            <a href="{{ route('admin.laporan.distribusi.cetak', $distribusi->id) }}" 
               class="btn btn-print-premium" 
               target="_blank">
                <i class="fas fa-file-pdf mr-2"></i> Cetak Laporan
            </a>
        </div>
    </div>

    <div class="row">
        {{-- INFORMASI UTAMA --}}
        <div class="col-md-4">
            <div class="card detail-card mb-4">
                <div class="card-header bg-white pt-4 border-0 text-center">
                    <div class="icon-shape bg-soft-success text-success rounded-circle mb-3 mx-auto" style="width: 60px; height: 60px;">
                        <i class="fas fa-shipping-fast fa-lg"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark">Ringkasan Distribusi</h5>
                    <div class="status-badge-detail mt-2">
                        <i class="fas fa-check-circle mr-1"></i> Terverifikasi
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    <hr class="my-4">
                    <div class="mb-4">
                        <div class="info-label">ID Transaksi</div>
                        <div class="info-value text-success">#DST-{{ str_pad($distribusi->id, 5, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="mb-4">
                        <div class="info-label">Tanggal Pengiriman</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($distribusi->tanggal)->translatedFormat('l, d F Y') }}</div>
                    </div>
                    <div class="mb-0">
                        <div class="info-label">Admin Pengirim</div>
                        <div class="info-value">{{ auth()->user()->name ?? 'System Admin' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DETAIL OUTLET & ITEM --}}
        <div class="col-md-8">
            {{-- KARTU OUTLET --}}
            <div class="card detail-card mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="bg-light p-3 rounded-lg">
                                <i class="fas fa-store fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="info-label mb-0">Outlet Tujuan</div>
                            <h4 class="font-weight-bold text-dark mb-1">{{ $distribusi->outlet->nama_outlet }}</h4>
                            <p class="text-muted mb-0 small">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $distribusi->outlet->alamat ?? 'Alamat tidak terdaftar' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL ITEM --}}
            <div class="card detail-card">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="font-weight-bold text-dark mb-0">Bahan Baku Yang Dikirim</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-muted border-0" width="80">NO</th>
                                    <th class="py-3 text-muted border-0">ITEM / BAHAN BAKU</th>
                                    <th class="text-center py-3 text-muted border-0">KUANTITAS</th>
                                    <th class="text-right px-4 py-3 text-muted border-0">SATUAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 text-center font-weight-bold text-muted">01</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-soft-success text-success p-2 rounded mr-3">
                                                <i class="fas fa-box"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark">{{ $distribusi->bahan->nama_bahan }}</div>
                                                <small class="text-muted">{{ $distribusi->bahan->kategori->nama_kategori ?? 'Umum' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <h5 class="font-weight-bold text-success mb-0">{{ $distribusi->jumlah }}</h5>
                                    </td>
                                    <td class="text-right px-4 text-muted font-weight-bold">
                                        {{ $distribusi->bahan->satuan }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-soft-light border-0 py-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</span>
                        <div class="text-right">
                            <span class="text-muted small d-block">Total Item Terdistribusi</span>
                            <span class="h4 font-weight-bold text-dark">1 Macam Bahan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection