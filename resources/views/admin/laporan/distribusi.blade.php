@extends('layouts.main')

@section('title', 'Laporan Distribusi')
@section('page', 'Laporan Distribusi')

@push('css')
     <link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold text-dark mb-1">Log Distribusi Bahan</h4>
            <p class="text-muted small mb-0">Riwayat pengiriman stok dari gudang ke seluruh unit cabang.</p>
        </div>
        <div class="bg-white p-2 px-3 rounded-pill shadow-sm border">
            <i class="fas fa-shipping-fast text-success mr-2"></i>
            <span class="small font-weight-bold text-dark">Total: {{ $distribusis->count() }} Pengiriman</span>
        </div>
    </div>

    {{-- TABEL LAPORAN --}}
    <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
        <div class="card-header bg-white py-4 border-0">
            <h6 class="card-title font-weight-bold mb-0 text-dark">
                <i class="fas fa-list-ul mr-2 text-success"></i>Data Distribusi Bahan
            </h6>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-premium align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center py-4 text-uppercase fs-xs fw-bold text-muted" width="70">No</th>
                            <th class="py-4 text-uppercase fs-xs fw-bold text-muted" width="150">Tanggal</th>
                            <th class="py-4 text-uppercase fs-xs fw-bold text-muted">Outlet Tujuan</th>
                            <th class="py-4 text-uppercase fs-xs fw-bold text-muted">Nama Bahan</th>
                            <th class="text-center py-4 text-uppercase fs-xs fw-bold text-muted" width="150">Jumlah</th>
                            <th class="text-center py-4 text-uppercase fs-xs fw-bold text-muted" width="180">Opsi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($distribusis as $d)
                            <tr class="border-bottom">
                                <td class="text-center">
                                    <span class="text-muted small fw-bold">{{ $loop->iteration }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-calendar-alt mr-2 text-success opacity-50"></i>
                                        <span class="font-weight-bold text-dark">
                                            {{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d M Y') }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape bg-soft-success text-success mr-2 shadow-sm" style="width: 32px; height: 32px; border-radius: 8px; font-size: 0.8rem;">
                                            <i class="fas fa-store"></i>
                                        </div>
                                        <span class="font-weight-bold text-dark">{{ $d->outlet->nama_outlet }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-dark font-weight-bold mb-0">{{ $d->bahan->nama_bahan }}</div>
                                    <small class="text-muted">Kategori: {{ $d->bahan->kategori->nama_kategori ?? 'Umum' }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="jumlah-bubble shadow-sm">
                                        {{ $d->jumlah }} <small class="font-weight-normal text-muted">{{ $d->bahan->satuan }}</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        {{-- Tombol Detail --}}
                                        <a href="{{ route('admin.laporan.distribusi.detail', $d->id) }}"
                                           class="btn-action-sq btn-soft-success"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Tombol Cetak --}}
                                        <a href="{{ route('admin.laporan.distribusi.cetak', $d->id) }}"
                                           class="btn-action-sq btn-soft-danger"
                                           target="_blank"
                                           title="Cetak PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center">
                                    <div class="py-4">
                                        <i class="fas fa-truck-loading fa-4x text-light mb-3"></i>
                                        <h5 class="text-muted fw-light">Belum ada aktivitas distribusi terdeteksi.</h5>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white py-4 border-0 text-center">
            <p class="text-muted small mb-0 italic">
                <i class="fas fa-info-circle mr-1 text-success"></i> 
                Data ini merupakan rekaman resmi pengeluaran stok dari Gudang Pusat.
            </p>
        </div>
    </div>

</div>
@endsection