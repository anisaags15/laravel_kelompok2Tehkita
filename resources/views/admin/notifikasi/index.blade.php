@extends('layouts.main')

@section('title', 'Pusat Notifikasi')

@section('content')
<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-center card p-4 shadow-sm border-0" style="border-radius: 15px;">
            <div class="mb-3 mb-md-0 text-center text-md-left">
                <h3 class="font-weight-bold text-dark mb-1">
                    <i class="fas fa-bell text-warning mr-2"></i>Pusat Perhatian Admin
                </h3>
                <p class="text-muted mb-0">Kelola semua aktivitas dan laporan masuk dari seluruh outlet.</p>
            </div>
            
            @if($notifications->count() > 0)
            <form action="{{ route('admin.notifikasi.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-4 font-weight-bold shadow-sm" style="border-radius: 20px;">
                    <i class="fas fa-check-double mr-1"></i> Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert bg-soft-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <i class="fas fa-check-circle mr-2 text-success"></i> <span class="text-dark">{{ session('success') }}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        
                        @forelse($notifications as $n)
                            @php
                                $type = $n->data['type'] ?? 'info';
                                $config = match($type) {
                                    'stok_kritis' => [
                                        'icon' => 'fa-exclamation-triangle',
                                        'color' => 'danger',
                                        'bg' => 'rgba(220, 53, 69, 0.15)',
                                        'label' => 'URGENT'
                                    ],
                                    'waste' => [
                                        'icon' => 'fa-trash-alt',
                                        'color' => 'warning',
                                        'bg' => 'rgba(255, 193, 7, 0.15)',
                                        'label' => 'WASTE'
                                    ],
                                    'chat' => [
                                        'icon' => 'fa-comment-dots',
                                        'color' => 'primary',
                                        'bg' => 'rgba(0, 123, 255, 0.15)',
                                        'label' => 'CHAT'
                                    ],
                                    default => [
                                        'icon' => 'fa-info-circle',
                                        'color' => 'info',
                                        'bg' => 'rgba(23, 162, 184, 0.15)',
                                        'label' => 'INFO'
                                    ]
                                };
                                $isUnread = $n->read_at === null;
                            @endphp

                            <div class="list-group-item list-group-item-action py-3 px-4 border-bottom {{ $isUnread ? 'bg-unread' : 'bg-transparent' }}">
                                <div class="row align-items-center">
                                    {{-- Kolom 1: Icon --}}
                                    <div class="col-auto">
                                        <div class="icon-box d-flex align-items-center justify-content-center shadow-sm" 
                                             style="width: 50px; height: 50px; background: {{ $config['bg'] }}; border-radius: 12px;">
                                            <i class="fas {{ $config['icon'] }} text-{{ $config['color'] }}" style="font-size: 1.2rem;"></i>
                                        </div>
                                    </div>

                                    {{-- Kolom 2: Konten --}}
                                    <div class="col px-md-3 mt-2 mt-md-0">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="badge badge-pill badge-{{ $config['color'] }} px-2 mr-2" style="font-size: 0.6rem; letter-spacing: 0.5px;">
                                                {{ $config['label'] }}
                                            </span>
                                            <small class="text-muted font-weight-bold">
                                                <i class="far fa-clock mr-1"></i>{{ $n->created_at->diffForHumans() }}
                                            </small>
                                            @if($isUnread)
                                                <span class="ml-2 badge badge-danger badge-pill" style="padding: 4px; height: 8px; width: 8px;"> </span>
                                            @endif
                                        </div>
                                        <h6 class="mb-1 font-weight-bold text-dark">{{ $n->data['title'] }}</h6>
                                        <p class="mb-0 text-muted small text-truncate d-none d-md-block" style="max-width: 500px;">
                                            {{ $n->data['message'] }}
                                        </p>
                                    </div>

                                    {{-- Kolom 3: Aksi --}}
                                    <div class="col-auto d-flex align-items-center mt-3 mt-md-0">
                                        <a href="{{ $n->data['url'] }}" class="btn btn-sm rounded-pill px-3 font-weight-bold btn-action shadow-sm mr-2">
                                            Tindak Lanjut
                                        </a>
                                        
                                        <form action="{{ route('admin.notifikasi.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-2 shadow-none hover-scale">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                {{-- Mobile Message --}}
                                <div class="d-block d-md-none mt-2">
                                    <p class="mb-0 text-muted small">{{ $n->data['message'] }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 my-5">
                                <div class="mb-3">
                                    <i class="fas fa-check-circle text-success opacity-25" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-dark font-weight-bold">Semua Aman!</h5>
                                <p class="text-muted">Tidak ada notifikasi yang memerlukan perhatian saat ini.</p>
                            </div>
                        @endforelse

                    </div>
                </div>

                {{-- Pagination --}}
                @if($notifications->hasPages())
                <div class="card-footer border-top py-4 bg-transparent">
                    <div class="d-flex justify-content-center">
                        {{ $notifications->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Status Belum Dibaca */
    .bg-unread { 
        background-color: rgba(0, 123, 255, 0.05) !important; 
        border-left: 4px solid #007bff !important; 
    }
    
    .list-group-item {
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
        background-color: transparent; /* Agar ikut warna card */
    }

    /* Hover effect disesuaikan agar tidak nabrak di dark mode */
    .list-group-item:hover {
        background-color: rgba(0,0,0,0.02);
        transform: translateX(5px);
    }

    .dark-mode .list-group-item:hover {
        background-color: rgba(255,255,255,0.02) !important;
    }

    /* Tombol Tindak Lanjut */
    .btn-action {
        background: #f8f9fa;
        color: #007bff;
        border: 1px solid #dee2e6;
    }

    .dark-mode .btn-action {
        background: #2d2d2d;
        color: #3b9bff;
        border-color: #444;
    }

    .hover-scale:hover { transform: scale(1.2); }

    /* Custom Style untuk List Group di Dark Mode */
    .dark-mode .list-group-item {
        border-color: #333 !important;
    }

    .dark-mode .bg-unread {
        background-color: rgba(0, 123, 255, 0.1) !important;
    }

    /* Fix Pagination Styling */
    .page-link {
        border-radius: 8px !important;
        margin: 0 3px;
        background-color: transparent;
        border-color: #dee2e6;
        color: #198754;
    }
    .dark-mode .page-link {
        border-color: #444;
        color: #e0e0e0;
    }
</style>
@endsection