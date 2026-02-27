@extends('layouts.main')

@section('title', 'Detail Stok Outlet')
@section('page', 'Detail Stok Outlet')

@push('css')
 <link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold text-dark mb-1">Laporan Inventaris Outlet</h4>
            <p class="text-muted small mb-0">Rincian ketersediaan stok bahan baku secara real-time.</p>
        </div>

        <div class="d-flex" style="gap: 10px;">
            <a href="{{ route('admin.laporan.stok-outlet') }}" class="btn btn-light border px-4" style="border-radius: 12px; font-weight: 600;">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <a href="{{ route('admin.laporan.stok-outlet.cetak', $outlet->id) }}"
               class="btn btn-print-red px-4">
                <i class="fas fa-file-pdf mr-2"></i> Cetak PDF
            </a>
        </div>
    </div>

    {{-- INFORMASI OUTLET --}}
    <div class="card info-card mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="outlet-avatar shadow-sm">
                        {{ substr($outlet->nama_outlet, 0, 1) }}
                    </div>
                </div>
                <div class="col">
                    <h5 class="font-weight-bold text-dark mb-1">{{ $outlet->nama_outlet }}</h5>
                    <p class="text-muted mb-0"><i class="fas fa-map-marker-alt text-danger mr-2"></i>{{ $outlet->alamat ?? 'Alamat belum diatur' }}</p>
                </div>
                <div class="col-md-3 text-right">
                    <div class="p-2">
                        <small class="text-muted d-block text-uppercase font-weight-bold" style="letter-spacing: 1px;">Total Item</small>
                        <h3 class="font-weight-bold text-success mb-0">{{ $stok->count() }} <small class="h6">Bahan</small></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DATA STOK --}}
    <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
        <div class="card-header bg-white py-4 border-0">
            <h6 class="font-weight-bold text-dark mb-0">
                <i class="fas fa-boxes mr-2 text-success"></i>Daftar Persediaan Bahan
            </h6>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="text-center py-3 border-0" width="80">NO</th>
                            <th class="py-3 border-0">NAMA BAHAN BAKU</th>
                            <th class="text-center py-3 border-0">TERAKHIR DITERIMA</th>
                            <th class="text-center py-3 border-0">SISA STOK</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($stok as $item)
                            <tr class="border-bottom">
                                <td class="text-center">
                                    <div class="no-badge-sm mx-auto shadow-sm border">{{ $loop->iteration }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-soft-success p-2 rounded mr-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-box text-success"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">{{ $item->bahan->nama_bahan }}</div>
                                            <small class="text-muted">{{ $item->bahan->kategori->nama_kategori ?? 'Bahan Utama' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="text-muted font-weight-bold" style="font-size: 0.9rem;">
                                        @if($item->tanggal_pengiriman)
                                            <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($item->tanggal_pengiriman)->translatedFormat('d M Y') }}
                                        @else
                                            <span class="badge badge-light">Belum ada rekaman</span>
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="stock-badge shadow-sm d-inline-block">
                                        <span class="h6 font-weight-bold mb-0 {{ $item->stok <= 5 ? 'text-danger' : 'text-success' }}">
                                            {{ $item->stok }}
                                        </span>
                                        <small class="text-muted ml-1">{{ $item->bahan->satuan }}</small>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-5 text-center">
                                    <div class="py-5">
                                        <i class="fas fa-folder-open fa-4x text-light mb-3"></i>
                                        <h5 class="text-muted font-weight-light">Data stok untuk outlet ini masih kosong.</h5>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-0 py-4 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle mr-1 text-primary"></i> Angka stok diperbarui secara otomatis berdasarkan laporan harian outlet.
                </small>
                <span class="badge badge-soft-success px-3 py-2" style="border-radius: 8px;">
                    Sesuai Database Pusat
                </span>
            </div>
        </div>
    </div>
</div>
@endsection