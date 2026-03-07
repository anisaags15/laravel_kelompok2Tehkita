@section('content')
<link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}">

<div class="container-fluid py-4">
@extends('layouts.main')

@section('title', 'Laporan Distribusi')
@section('page', 'Laporan Distribusi')

@section('content')
<link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}">

<div class="container-fluid py-4">
    <div class="executive-header shadow-lg">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h2 class="font-weight-bold mb-1 text-white">Log Distribusi Bulanan</h2>
                <p class="text-white-50 mb-0">Arsip pengiriman stok pusat ke seluruh unit outlet "Teh Kita".</p>
            </div>
            <div class="mt-3 mt-md-0 bg-white-20 p-2 px-4 shadow-sm no-print rounded-pill">
                <i class="fas fa-archive text-white mr-2"></i>
                <span class="small font-weight-bold text-white">Total: {{ $distribusis->count() }} Rekaman</span>
            </div>
        </div>
    </div>

    <div class="card main-report-card mx-md-4">
        <div class="card-header bg-white py-4 border-0 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-layer-group mr-2 text-success"></i>Riwayat Distribusi Outlet
            </h6>
            <div class="search-wrapper-custom no-print">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" class="form-control form-control-sm border-0 shadow-none" placeholder="Cari data...">
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-luxury align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="70">No</th>
                            <th>Periode Laporan</th>
                            <th>Unit Outlet</th>
                            <th class="text-center">Aktivitas</th>
                            <th class="text-center">Volume Barang</th>
                            <th class="text-center no-print" width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="logTable">
                        @forelse ($distribusis as $d)
                        <tr>
                            <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-primary-soft mr-3 shadow-sm" style="width: 40px; height: 40px;">
                                        <i class="far fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold text-dark d-block text-capitalize">
                                            {{ \Carbon\Carbon::create()->month($d->bulan)->translatedFormat('F') }}
                                        </span>
                                        <small class="text-muted fw-bold">{{ $d->tahun }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-success-soft mr-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <span class="font-weight-bold text-dark">{{ $d->outlet->nama_outlet }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-pill badge-light text-primary border px-3 py-2 font-weight-bold">
                                    {{ $d->total_pengiriman }} Transaksi
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="badge-qty d-inline-block">
                                    {{ number_format($d->total_qty) }} <small class="fw-bold">Items</small>
                                </div>
                            </td>
                            <td class="text-center no-print">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('admin.laporan.distribusi.detail', [$d->outlet_id, $d->bulan, $d->tahun]) }}" 
                                       class="btn-action-premium btn-view mx-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.laporan.distribusi.cetak', [$d->outlet_id, $d->bulan, $d->tahun]) }}" 
                                       class="btn-action-premium btn-pdf mx-1" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection