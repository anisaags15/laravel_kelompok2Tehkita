@extends('layouts.main')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-12">
            <h3 class="fw-bold text-dark"><i class="fas fa-exclamation-triangle text-danger mr-2"></i> Rekapitulasi Stok Kritis</h3>
            <p class="text-muted">Daftar seluruh bahan baku di outlet yang sudah mencapai ambang batas minimum.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">Outlet</th>
                            <th>Bahan Baku</th>
                            <th class="text-center">Sisa Stok</th>
                            <th>Status</th>
                            <th class="text-right px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokKritis as $s)
                        <tr>
                            <td class="px-4 font-weight-bold">{{ $s->outlet->nama_outlet }}</td>
                            <td>{{ $s->bahan->nama_bahan }}</td>
                            <td class="text-center">
                                <span class="badge {{ $s->stok == 0 ? 'badge-danger' : 'badge-warning' }} px-3 py-2">
                                    {{ $s->stok }} {{ $s->bahan->satuan }}
                                </span>
                            </td>
                            <td>
                                @if($s->stok == 0)
                                    <span class="text-danger small fw-bold">Habis Total</span>
                                @else
                                    <span class="text-warning small fw-bold">Hampir Habis</span>
                                @endif
                            </td>
                            <td class="text-right px-4">
                                <a href="{{ route('admin.distribusi.create', ['outlet_id' => $s->outlet_id]) }}" class="btn btn-primary btn-sm rounded-pill">
                                    <i class="fas fa-plus-circle mr-1"></i> Restock
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Luar biasa! Tidak ada stok kritis saat ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection