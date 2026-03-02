@extends('layouts.main')

@section('title', 'Manajemen Distribusi')
@section('page', 'Logistik & Distribusi')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER SECTION --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between info-box-custom p-4 shadow-sm header-distribusi-wrapper">
                <div>
                    <h4 class="font-weight-bold text-adaptive mb-1">Penerimaan Stok</h4>
                    <p class="text-muted mb-0 small">Kelola dan konfirmasi bahan baku yang masuk ke outlet Anda.</p>
                </div>
                <div class="text-right d-none d-md-block">
                    <div class="badge-outlet-adaptive px-3 py-2 mb-1">
                        <i class="fas fa-store-alt mr-2"></i>{{ auth()->user()->outlet->nama_outlet ?? 'Outlet Aktif' }}
                    </div>
                    <div class="text-muted small">
                        <i class="far fa-clock mr-1"></i> Perbaruan: {{ date('H:i') }} WIB
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card shadow-lg border-0" style="border-radius: 20px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-adaptive">
                    <thead>
                        <tr class="table-header-adaptive">
                            <th class="py-4 text-center font-weight-bold text-uppercase small" width="80">No</th>
                            <th class="py-4 font-weight-bold text-uppercase small">Detail Bahan</th>
                            <th class="py-4 text-center font-weight-bold text-uppercase small">Waktu Pengiriman</th>
                            <th class="py-4 text-center font-weight-bold text-uppercase small">Jumlah Stok</th>
                            <th class="py-4 text-center font-weight-bold text-uppercase small">Status</th>
                            <th class="py-4 text-center font-weight-bold text-uppercase small">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($distribusis as $item)
                        <tr class="table-row-modern border-bottom border-adaptive">
                            <td class="text-center">
                                <span class="font-weight-bold text-muted small">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            <td class="py-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-icon mr-3 bg-soft-info text-info">
                                        <i class="fas fa-cube"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-adaptive mb-0" style="font-size: 1.05rem;">
                                            {{ $item->bahan->nama_bahan ?? '-' }}
                                        </div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">ID: #DST-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="font-weight-bold text-adaptive mb-0">
                                    {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                                </div>
                                <small class="text-primary font-weight-bold">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->diffForHumans() }}
                                </small>
                            </td>

                            <td class="text-center">
                                <div class="distribusi-bubble-adaptive shadow-sm">
                                    <span class="amount text-primary">{{ number_format($item->jumlah, 0, ',', '.') }}</span>
                                    <span class="unit text-muted small text-uppercase">{{ $item->bahan->satuan ?? 'Unit' }}</span>
                                </div>
                            </td>

                            <td class="text-center">
                                @if ($item->status === 'dikirim')
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="text-warning small font-weight-bold mb-1 pulse">
                                            <i class="fas fa-shipping-fast mr-1"></i> DALAM PERJALANAN
                                        </span>
                                        <div class="progress progress-adaptive w-75">
                                            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 60%"></div>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="text-success small font-weight-bold mb-1">
                                            <i class="fas fa-check-circle mr-1"></i> TIBA DI OUTLET
                                        </span>
                                        <div class="progress progress-adaptive w-75">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($item->status === 'dikirim')
                                    <form id="terima-form-{{ $item->id }}" action="{{ route('user.distribusi.terima', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="button" 
                                                class="btn btn-success px-4 py-2 font-weight-bold shadow-sm"
                                                style="border-radius: 10px;"
                                                onclick="confirmTerima('terima-form-{{ $item->id }}')">
                                            Terima Barang
                                        </button>
                                    </form>
                                @else
                                    <span class="badge-pill-custom bg-soft-success text-success">
                                        <i class="fas fa-check-double mr-1"></i> SELESAI
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center">
                                <div class="opacity-25 mb-3">
                                    <i class="fas fa-truck-loading fa-4x text-muted"></i>
                                </div>
                                <h5 class="text-muted font-weight-bold">Belum ada kiriman stok</h5>
                                <p class="small text-muted">Data distribusi dari pusat akan muncul secara otomatis di sini.</p>
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
    /* UTILITY UNTUK PENERIMAAN STOK */
    .bg-soft-info { background-color: rgba(54, 185, 204, 0.1); }
    .bg-soft-success { background-color: rgba(28, 200, 138, 0.1); }
    
    .avatar-icon {
        width: 40px; height: 40px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 10px; font-size: 1rem;
    }

    .progress-adaptive {
        height: 6px; 
        border-radius: 10px;
        background-color: #eaecf4;
    }
    .dark-mode .progress-adaptive {
        background-color: #3d4146;
    }

    .badge-outlet-adaptive {
        font-size: 0.9rem; 
        border-radius: 8px; 
        background: linear-gradient(45deg, #4e73df, #224abe); 
        color: white;
        font-weight: bold;
    }

    .pulse { animation: pulse-animation 2s infinite; }
    @keyframes pulse-animation {
        0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; }
    }

    .badge-pill-custom {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
    }
</style>
@endsection