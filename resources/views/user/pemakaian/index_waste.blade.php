@extends('layouts.main')

@section('title', 'Riwayat Waste')
@section('page', 'Operasional Outlet')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER SECTION --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between bg-white p-4 shadow-sm border-0" style="border-radius: 15px; border-left: 5px solid #e74a3b !important;">
                <div class="mb-3 mb-md-0 text-center text-md-left">
                    <h4 class="font-weight-bold text-dark mb-1">Riwayat Laporan Waste</h4>
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
    <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted" style="background-color: #fcfcfd; border-bottom: 1px solid #f1f1f4;">
                            <th class="py-4 text-center font-weight-bold text-uppercase small" width="80">No</th>
                            <th class="py-4 font-weight-bold text-uppercase small">Detail Bahan</th>
                            <th class="py-4 text-center font-weight-bold text-uppercase small">Waktu Lapor</th>
                            <th class="py-4 text-center font-weight-bold text-uppercase small">Jumlah Waste</th>
                            <th class="py-4 font-weight-bold text-uppercase small" width="25%">Keterangan</th>
                            <th class="py-4 text-center font-weight-bold text-uppercase small">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wastes as $w)
                        <tr class="table-row-modern border-bottom">
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
                                        <div class="font-weight-bold text-dark mb-0" style="font-size: 1rem;">
                                            {{ $w->bahan->nama_bahan ?? '-' }}
                                        </div>
                                        <div class="text-muted small" style="font-size: 0.7rem;">ID: #WST-{{ str_pad($w->id, 5, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="font-weight-bold text-dark mb-0" style="font-size: 0.9rem;">
                                    {{ \Carbon\Carbon::parse($w->tanggal)->translatedFormat('d M Y') }}
                                </div>
                                <small class="text-primary font-weight-bold">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ \Carbon\Carbon::parse($w->created_at)->format('H:i') }} WIB
                                </small>
                            </td>

                            <td class="text-center">
                                <div class="waste-bubble shadow-sm">
                                    <span class="amount text-danger">-{{ number_format($w->jumlah, 0, ',', '.') }}</span>
                                    <span class="unit text-muted small text-uppercase">{{ $w->bahan->satuan ?? 'Unit' }}</span>
                                </div>
                            </td>

                            <td>
                                <div class="p-2 rounded small text-muted italic" style="background-color: #f8f9fc; border-left: 3px solid #e74a3b;">
                                    "{{ $w->keterangan ?? 'Tanpa alasan spesifik' }}"
                                </div>
                            </td>

                            <td class="text-center">
                                @if($w->status == 'verified')
                                    <span class="badge-pill-custom bg-soft-success text-success">
                                        <i class="fas fa-check-circle mr-1"></i> VERIFIED
                                    </span>
                                @else
                                    <span class="badge-pill-custom bg-soft-warning text-warning">
                                        <i class="fas fa-clock mr-1 pulse"></i> PENDING
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center">
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
        
        {{-- PAGINATION FIX --}}
        @if($wastes->hasPages())
        <div class="card-footer bg-white border-top-0 py-4 px-4">
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

<style>
    /* CSS Styling senada dengan Theme */
    .bg-soft-danger { background-color: rgba(231, 74, 59, 0.1); }
    .bg-soft-success { background-color: rgba(28, 200, 138, 0.1); }
    .bg-soft-warning { background-color: rgba(246, 194, 62, 0.1); }
    
    .avatar-icon {
        width: 40px; height: 40px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 10px; font-size: 1rem;
    }

    .waste-bubble {
        background: #fffafa;
        padding: 6px 14px;
        border-radius: 10px;
        display: inline-block;
        border: 1px solid #ffebeb;
    }

    .waste-bubble .amount { font-weight: 800; font-size: 1rem; }
    .waste-bubble .unit { font-size: 0.7rem; font-weight: 600; }

    .badge-pill-custom {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        display: inline-block;
    }

    .pulse { animation: pulse-animation 2s infinite; }
    @keyframes pulse-animation {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .table-row-modern:hover {
        background-color: #f8f9fc !important;
        transition: 0.2s;
    }

    /* Pagination Styling agar tidak berantakan */
    .pagination-modern .pagination { margin-bottom: 0; gap: 5px; }
    .pagination-modern .page-item .page-link {
        border: none; background: #f8f9fc; color: #e74a3b;
        border-radius: 8px !important; font-weight: 600;
    }
    .pagination-modern .page-item.active .page-link {
        background: #e74a3b; color: white;
    }
</style>
@endsection