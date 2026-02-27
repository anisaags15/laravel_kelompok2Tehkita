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
            <p class="text-muted">Pantau bahan baku yang perlu segera di-restock agar operasional lancar.</p>
        </div>
        <div class="col-md-4 text-right">
            <div class="bg-white p-2 px-4 rounded-pill shadow-sm border d-inline-block">
                <i class="fas fa-bell text-danger mr-2"></i>
                <span class="font-weight-bold text-dark">{{ $stokKritis->count() }} Item Kritis</span>
            </div>
        </div>
    </div>

    {{-- CARD TABEL --}}
    <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center py-4 text-muted border-0" width="60">NO</th>
                            <th class="py-4 text-muted border-0" width="20%">OUTLET</th>
                            <th class="py-4 text-muted border-0" width="25%">BAHAN BAKU</th>
                            <th class="text-center py-4 text-muted border-0">SISA STOK</th>
                            <th class="py-4 text-muted border-0 text-center">STATUS</th>
                            <th class="text-right px-4 py-4 text-muted border-0">TINDAKAN CEPAT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokKritis as $s)
                        <tr class="{{ $s->stok == 0 ? 'row-critical' : 'row-warning' }}">
                            {{-- KOLOM NO --}}
                            <td class="text-center">
                                <div class="no-badge mx-auto shadow-sm border">
                                    {{ $loop->iteration }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3" style="width: 35px; height: 35px; background: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #475569;">
                                        {{ substr($s->outlet->nama_outlet, 0, 1) }}
                                    </div>
                                    <span class="font-weight-bold text-dark">{{ $s->outlet->nama_outlet }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $s->bahan->nama_bahan }}</div>
                                <small class="text-muted text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">{{ $s->bahan->kategori->nama_kategori ?? 'UMUM' }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $s->stok == 0 ? 'badge-danger pulse-danger' : 'badge-warning' }} px-3 py-2" style="font-size: 0.95rem; border-radius: 10px; font-weight: 800;">
                                    {{ $s->stok }} <small>{{ $s->bahan->satuan }}</small>
                                </span>
                            </td>
                            <td class="text-center">
                                @if($s->stok == 0)
                                    <span class="status-pill bg-white text-danger border border-danger">
                                        <i class="fas fa-times-circle mr-1"></i> Habis Total
                                    </span>
                                @else
                                    <span class="status-pill bg-white text-warning border border-warning">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Hampir Habis
                                    </span>
                                @endif
                            </td>
                            <td class="text-right px-4">
                                <a href="{{ route('admin.distribusi.create', ['outlet_id' => $s->outlet_id, 'bahan_id' => $s->bahan_id]) }}" 
                                   class="btn btn-restock btn-sm">
                                    <i class="fas fa-shipping-fast mr-2"></i> Kirim Stok
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center">
                                <div class="py-5">
                                    <i class="fas fa-shield-check fa-4x text-success mb-3 opacity-25"></i>
                                    <h5 class="text-muted fw-light">Sempurna! Semua stok di outlet dalam kondisi aman.</h5>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-0 py-4 px-4 text-center">
            <span class="text-muted small italic">
                <i class="fas fa-info-circle mr-1 text-success"></i> 
                Klik tombol <strong>Kirim Stok</strong> untuk diarahkan ke halaman pembuatan distribusi otomatis.
            </span>
        </div>
    </div>
</div>
@endsection