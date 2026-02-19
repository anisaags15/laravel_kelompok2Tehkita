@extends('layouts.main')

@section('title', 'Pusat Notifikasi Admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0 text-dark">
            <i class="fas fa-bell text-success mr-2"></i> Log Notifikasi Sistem
        </h3>
        <span class="badge bg-soft-success px-3 py-2">
            Total {{ $stokKritis->count() + $unreadMessages->count() }} Perhatian
        </span>
    </div>

    <div class="row">
        {{-- SECTION 1: STOK KRITIS OUTLET --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-danger">
                        <i class="fas fa-exclamation-circle mr-2"></i> Stok Kritis di Outlet
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="text-muted small text-uppercase">
                                <tr>
                                    <th class="px-4">Outlet</th>
                                    <th>Bahan Baku</th>
                                    <th class="text-center">Sisa Stok</th>
                                    <th class="text-right px-4">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stokKritis as $item)
                                    <tr>
                                        <td class="px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="dashboard-icon bg-soft-danger mr-3" style="width:40px; height:40px; font-size:16px;">
                                                    <i class="fas fa-store"></i>
                                                </div>
                                                <span class="fw-bold text-dark">{{ $item->outlet->nama_outlet }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border p-2">
                                                {{ $item->bahan->nama_bahan }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <b class="text-danger" style="font-size: 1.1rem;">{{ $item->stok }}</b>
                                            <small class="text-muted d-block">Unit tersisa</small>
                                        </td>
                                        <td class="text-right px-4">
                                            <a href="{{ route('admin.distribusi.create', ['outlet_id' => $item->outlet_id]) }}" 
                                               class="btn btn-success btn-sm px-3 rounded-pill shadow-sm">
                                                <i class="fas fa-truck mr-1"></i> Kirim Stok
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <i class="fas fa-check-circle fa-3x text-success mb-3 opacity-50"></i>
                                            <p class="text-muted mb-0">Semua stok outlet aman terkendali.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: PESAN MASUK --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-envelope-open-text mr-2"></i> Pesan Baru
                    </h5>
                </div>
                <div class="card-body px-3 py-3">
                    @forelse($unreadMessages as $msg)
                        <a href="{{ route('chat.show', $msg->sender_id) }}" class="text-decoration-none">
                            <div class="message-item p-3 mb-2 border-0">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="fw-bold text-primary">{{ $msg->sender->name }}</small>
                                    <small class="text-muted small">{{ $msg->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="text-dark small mb-0 text-truncate">
                                    {{ $msg->message }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-comment-slash fa-2x mb-2 opacity-50"></i>
                            <p class="small">Tidak ada pesan baru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection