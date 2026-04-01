@extends('layouts.main')

@section('title', 'Rekapitulasi Stok Kritis')
@section('page', 'Stok Kritis')

@push('css')
    <link rel="stylesheet" href="{{ asset('templates/dist/css/laporan-admin.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h3 class="fw-bold text-dark mb-1">
                <i class="fas fa-exclamation-triangle text-danger mr-2"></i> Rekapitulasi Stok Kritis
            </h3>
            <p class="text-muted">Daftar bahan baku yang <b>belum diproses</b> pengirimannya.</p>
        </div>
        <div class="col-md-4 text-md-right text-left mt-3 mt-md-0">
            <div class="card d-inline-block border-0 shadow-sm rounded-pill px-4 py-2 mb-0">
                <div class="card-body p-0 d-flex align-items-center">
                    <i class="fas fa-bell text-danger mr-2"></i>
                    <span class="font-weight-bold text-dark">{{ $stokKritis->count() }} Perlu Tindakan</span>
                </div>
            </div>
        </div>
    </div>

    {{-- CARD TABEL --}}
    <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center py-4 text-muted border-0" width="60">NO</th>
                            <th class="py-4 text-muted border-0">OUTLET</th>
                            <th class="py-4 text-muted border-0">BAHAN BAKU</th>
                            <th class="text-center py-4 text-muted border-0">SISA STOK</th>
                            <th class="py-4 text-muted border-0 text-center">STATUS</th>
                            <th class="text-right px-4 py-4 text-muted border-0">TINDAKAN CEPAT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokKritis as $s)
                        <tr class="{{ $s->stok == 0 ? 'row-critical' : 'row-warning' }}">
                            <td class="text-center">
                                <div class="no-badge mx-auto shadow-sm border-0 bg-soft-secondary text-dark font-weight-bold">
                                    {{ $loop->iteration }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3 icon-outlet-placeholder">
                                        {{ substr($s->outlet->nama_outlet, 0, 1) }}
                                    </div>
                                    <span class="font-weight-bold text-dark">{{ $s->outlet->nama_outlet }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $s->bahan->nama_bahan }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $s->stok == 0 ? 'badge-danger pulse-danger' : 'badge-warning' }} px-3 py-2 shadow-sm" style="font-size: 0.9rem; border-radius: 10px; font-weight: 800;">
                                    {{ $s->stok }} <small>{{ $s->bahan->satuan }}</small>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="status-pill-custom {{ $s->stok == 0 ? 'border-danger text-danger' : 'border-warning text-warning' }}">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Belum Diproses
                                </span>
                            </td>
                            <td class="text-right px-4">
                                <a href="{{ route('admin.distribusi.create', ['outlet_id' => $s->outlet_id, 'bahan_id' => $s->bahan_id]) }}" 
                                   class="btn btn-success btn-sm rounded-pill px-3 font-weight-bold shadow-sm">
                                    <i class="fas fa-shipping-fast mr-2"></i> Kirim Stok
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center">
                                <div class="py-5">
                                    <i class="fas fa-check-circle fa-4x text-success mb-3 opacity-25"></i>
                                    <h5 class="text-muted fw-light">Semua stok kritis sudah dalam proses pengiriman!</h5>
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

<style>
    .no-badge { width: 30px; height: 30px; line-height: 30px; border-radius: 8px; font-size: 0.8rem; }
    .bg-soft-secondary { background-color: rgba(108, 117, 125, 0.1); }
    .icon-outlet-placeholder {
        width: 35px; height: 35px; background: rgba(71, 85, 105, 0.1); 
        border-radius: 8px; display: flex; align-items: center; 
        justify-content: center; font-weight: 800; color: #475569;
    }
    .status-pill-custom {
        padding: 5px 12px; border-radius: 20px; font-size: 0.75rem;
        font-weight: 700; text-transform: uppercase; border: 1px solid;
    }
    .pulse-danger { animation: pulse-red 2s infinite; }
    @keyframes pulse-red {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
</style>
@endsection