@extends('layouts.main')

@section('title', 'Riwayat Waste')
@section('page', 'Operasional Outlet')

@push('styles')
    <link rel="stylesheet" href="{{ asset('templates/dist/css/index-waste.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER SECTION --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between info-box-custom p-4 shadow-sm header-waste-wrapper">
                <div class="mb-3 mb-md-0 text-center text-md-left">
                    <h4 class="font-weight-bold text-adaptive mb-1">Riwayat Laporan Waste</h4>
                    <p class="text-muted mb-0 small">Catatan bahan baku rusak, kadaluwarsa, atau tidak layak pakai.</p>
                </div>
                <div class="text-right">
                    <a href="{{ route('user.waste.create') }}" class="btn btn-danger px-4 py-2 shadow-sm font-weight-bold" style="border-radius: 12px;">
                        <i class="fas fa-plus-circle mr-2"></i>Lapor Waste Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card shadow-lg" style="border-radius: 20px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-adaptive">
                    <thead>
                        <tr class="table-header-adaptive border-bottom">
                            <th class="py-4 text-center font-weight-bold text-uppercase small" width="80">No</th>
                            <th class="py-4 font-weight-bold text-uppercase small">Detail Bahan</th>
                            <th class="py-4 text-center font-weight-bold text-uppercase small">Bukti Foto</th>
                            <th class="py-4 text-center font-weight-bold text-uppercase small">Waktu Lapor</th>
                            <th class="py-4 text-center font-weight-bold text-uppercase small">Jumlah Waste</th>
                            <th class="py-4 font-weight-bold text-uppercase small" width="20%">Keterangan</th>
                            <th class="py-4 text-center font-weight-bold text-uppercase small">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wastes as $w)
                        <tr class="table-row-modern border-bottom border-adaptive">
                            <td class="text-center">
                                <span class="font-weight-bold text-muted small">
                                    {{ str_pad(($wastes->currentPage() - 1) * $wastes->perPage() + $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            <td class="py-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-icon mr-3 bg-soft-danger text-danger">
                                        <i class="fas fa-biohazard"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-adaptive mb-0" style="font-size: 1rem;">
                                            {{ $w->bahan->nama_bahan ?? '-' }}
                                        </div>
                                        <div class="text-muted small" style="font-size: 0.7rem;">ID: #WST-{{ str_pad($w->id, 5, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                @if($w->foto)
                                    <img src="{{ asset('storage/' . $w->foto) }}" 
                                         class="img-thumbnail rounded shadow-sm foto-zoom-user" 
                                         style="width: 50px; height: 50px; object-fit: cover; cursor: pointer; border-radius: 10px; border: 2px solid #f8f9fa;"
                                         data-toggle="modal" data-target="#modalFotoUser{{ $w->id }}">
                                @else
                                    <div class="text-muted small opacity-50">
                                        <i class="fas fa-image-slash fa-lg"></i>
                                    </div>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="font-weight-bold text-adaptive mb-0" style="font-size: 0.9rem;">
                                    {{ \Carbon\Carbon::parse($w->tanggal)->translatedFormat('d M Y') }}
                                </div>
                                <small class="text-primary font-weight-bold">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ \Carbon\Carbon::parse($w->created_at)->format('H:i') }} WIB
                                </small>
                            </td>

                            <td class="text-center">
                                <div class="waste-bubble-adaptive shadow-sm">
                                    <span class="amount text-danger">-{{ number_format($w->jumlah, 0, ',', '.') }}</span>
                                    <span class="unit text-muted small text-uppercase">{{ $w->bahan->satuan ?? 'Unit' }}</span>
                                </div>
                            </td>

                            <td>
                                <div class="keterangan-box-adaptive" title="{{ $w->keterangan }}">
                                    "{{ $w->keterangan ?? 'Tanpa alasan spesifik' }}"
                                </div>
                            </td>

                            <td class="text-center">
                                @if($w->status == 'verified')
                                    <span class="badge-pill-custom bg-soft-success text-success">
                                        <i class="fas fa-check-circle mr-1"></i> VERIFIED
                                    </span>
                                @elseif($w->status == 'rejected')
                                    <span class="badge-pill-custom bg-soft-danger text-danger">
                                        <i class="fas fa-times-circle mr-1"></i> REJECTED
                                    </span>
                                @else
                                    <span class="badge-pill-custom bg-soft-warning text-warning">
                                        <i class="fas fa-clock mr-1 pulse"></i> PENDING
                                    </span>
                                @endif
                            </td>
                        </tr>

                        {{-- MODAL ZOOM FOTO --}}
                        @if($w->foto)
                        <div class="modal fade" id="modalFotoUser{{ $w->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                                    <div class="modal-header border-0 pb-0">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body p-4 pt-0 text-center">
                                        <img src="{{ asset('storage/' . $w->foto) }}" class="img-fluid rounded shadow-sm mb-3" style="max-height: 450px; width: 100%; object-fit: contain;">
                                        <h5 class="font-weight-bold mb-1 text-adaptive">{{ $w->bahan->nama_bahan }}</h5>
                                        <p class="text-muted small mb-0">Laporan pada {{ \Carbon\Carbon::parse($w->created_at)->translatedFormat('d F Y, H:i') }} WIB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="7" class="py-5 text-center">
                                <div class="opacity-25 mb-3">
                                    <i class="fas fa-clipboard-list fa-4x text-muted"></i>
                                </div>
                                <h5 class="text-muted font-weight-bold">Belum ada laporan waste</h5>
                                <p class="small text-muted">Data barang rusak akan tampil secara otomatis di sini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($wastes->hasPages())
        <div class="card-footer bg-adaptive py-4 px-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <p class="text-muted small mb-3 mb-md-0">
                    Menampilkan <strong>{{ $wastes->firstItem() }}</strong> - <strong>{{ $wastes->lastItem() }}</strong> dari <strong>{{ $wastes->total() }}</strong> laporan
                </p>
                <div class="pagination-modern">
                    {{ $wastes->appends(request()->input())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection