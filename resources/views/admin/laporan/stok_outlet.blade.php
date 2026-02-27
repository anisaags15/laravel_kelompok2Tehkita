@extends('layouts.main')

@section('title', 'Laporan Stok Outlet')
@section('page', 'Laporan Stok Outlet')

@push('css')
     <link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- STATS SECTION --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm card-stats p-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-soft-success text-success rounded-lg mr-3 shadow-sm">
                            <i class="fas fa-store fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small font-weight-bold">TOTAL CABANG</p>
                            <h3 class="font-weight-bold mb-0 text-dark">{{ $outlets->count() }} <small class="text-muted h6">Outlet</small></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm card-stats p-2" style="border-left: 4px solid #20c997;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape text-success mr-3 shadow-sm" style="background-color: #e6fffa;">
                            <i class="fas fa-cubes fa-lg" style="color: #20c997;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small font-weight-bold">TOTAL INVENTORI</p>
                            <h3 class="font-weight-bold mb-0 text-dark">{{ $outlets->sum(fn($o) => $o->stokOutlet->count()) }} <small class="text-muted h6">Item</small></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE SECTION --}}
    <div class="card border-0 shadow-lg" style="border-radius: 20px;">
        <div class="card-header bg-white py-4 border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="font-weight-bold text-dark mb-1">Manajemen Stok Wilayah</h4>
                    <p class="text-muted mb-0 small">Monitoring distribusi dan ketersediaan bahan baku antar outlet.</p>
                </div>
                <div class="bg-light p-2 rounded-pill px-3">
                    <i class="fas fa-calendar-alt text-success mr-2"></i>
                    <span class="small font-weight-bold">{{ date('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-premium align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center py-4 border-0" width="8%">NO</th>
                            <th class="py-4 border-0">NAMA OUTLET CABANG</th>
                            <th class="text-center py-4 border-0">KAPASITAS STOK</th>
                            <th class="text-center py-4 border-0" width="200px">AKSI CEPAT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($outlets as $outlet)
                        <tr class="border-bottom">
                            <td class="text-center">
                                <span class="badge badge-light p-2 shadow-sm text-muted" style="border-radius: 8px;">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-box mr-3">
                                        {{ substr($outlet->nama_outlet, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark h6 mb-0">{{ $outlet->nama_outlet }}</div>
                                        <small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i> ID: OUT-{{ $outlet->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge-count-premium">
                                    <i class="fas fa-box-open mr-2 opacity-50"></i>{{ $outlet->stokOutlet->count() }} <small class="font-weight-normal">Bahan</small>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('admin.laporan.stok-outlet.detail', $outlet->id) }}" 
                                       class="btn-circle btn-circle-detail" 
                                       title="Lihat Detail">
                                        <i class="fas fa-chart-line"></i>
                                    </a>
                                    
                                    <a href="{{ route('admin.laporan.stok-outlet.cetak', $outlet->id) }}" 
                                       target="_blank"
                                       class="btn-circle btn-circle-print" 
                                       title="Cetak PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-5 text-center">
                                <div class="text-center py-5">
                                    <i class="fas fa-box-open fa-4x text-light mb-3"></i>
                                    <h5 class="text-muted">Data outlet masih kosong</h5>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-0 py-4 text-center">
            <small class="text-muted font-italic">
                <i class="fas fa-sync-alt mr-2"></i>Data diperbarui secara otomatis setiap ada transaksi masuk dari outlet.
            </small>
        </div>
    </div>
</div>
@endsection