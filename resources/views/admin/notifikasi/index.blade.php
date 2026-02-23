@extends('layouts.main')

@section('content')
<div class="container-fluid mt-4">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-end">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fas fa-bell text-warning me-2"></i>Pusat Perhatian Admin
                </h3>
                <p class="text-muted mb-0">Kelola semua aktivitas dan laporan masuk dari seluruh outlet.</p>
            </div>
            @if($notifications->count() > 0)
            <form action="{{ route('notifikasi.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold shadow-sm">
                    <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        
                        @forelse($notifications as $n)
                            @php
                                // Konfigurasi Tampilan berdasarkan Tipe Notifikasi
                                $type = $n->data['type'] ?? 'info';
                                $config = match($type) {
                                    'stok_kritis' => [
                                        'icon' => 'fa-exclamation-triangle',
                                        'color' => 'danger',
                                        'bg' => '#fff5f5',
                                        'label' => 'URGENT'
                                    ],
                                    'waste' => [
                                        'icon' => 'fa-trash-alt',
                                        'color' => 'warning',
                                        'bg' => '#fffdf2',
                                        'label' => 'WASTE'
                                    ],
                                    'chat' => [
                                        'icon' => 'fa-comment-dots',
                                        'color' => 'primary',
                                        'bg' => '#f0f7ff',
                                        'label' => 'CHAT'
                                    ],
                                    default => [
                                        'icon' => 'fa-info-circle',
                                        'color' => 'info',
                                        'bg' => '#f0faff',
                                        'label' => 'INFO'
                                    ]
                                };
                            @endphp

                            <div class="list-group-item list-group-item-action py-3 px-4 border-bottom border-light transition-all hover-bg-light">
                                <div class="row align-items-center">
                                    {{-- Kolom 1: Icon --}}
                                    <div class="col-auto">
                                        <div class="icon-box d-flex align-items-center justify-content-center shadow-sm" 
                                             style="width: 55px; height: 55px; background: {{ $config['bg'] }}; border-radius: 16px;">
                                            <i class="fas {{ $config['icon'] }} text-{{ $config['color'] }} fs-5"></i>
                                        </div>
                                    </div>

                                    {{-- Kolom 2: Konten --}}
                                    <div class="col px-3">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="badge bg-{{ $config['color'] }} rounded-pill me-2" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                                {{ $config['label'] }}
                                            </span>
                                            <small class="text-muted fw-medium">
                                                <i class="far fa-clock me-1"></i>{{ $n->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <h6 class="mb-1 fw-bold text-dark">{{ $n->data['title'] }}</h6>
                                        <p class="mb-0 text-secondary small text-truncate" style="max-width: 600px;">
                                            {{ $n->data['message'] }}
                                        </p>
                                    </div>

                                    {{-- Kolom 3: Aksi --}}
                                    <div class="col-auto d-flex align-items-center gap-2">
                                        <a href="{{ $n->data['url'] }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-primary border shadow-sm">
                                            Tindak Lanjut
                                        </a>
                                        
                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('notifikasi.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-2 text-decoration-none hover-scale" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 my-5">
                                <div class="mb-3">
                                    <i class="fas fa-check-circle text-success opacity-25" style="font-size: 5rem;"></i>
                                </div>
                                <h5 class="text-dark fw-bold">Semua Aman!</h5>
                                <p class="text-muted">Tidak ada notifikasi yang memerlukan perhatian saat ini.</p>
                            </div>
                        @endforelse

                    </div>
                </div>

                {{-- Pagination --}}
                @if($notifications->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
                    {{ $notifications->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .transition-all { transition: all 0.3s ease; }
    .hover-bg-light:hover { background-color: #f8f9fa; }
    .hover-scale:hover { transform: scale(1.1); }
    .icon-box { border: 1px solid rgba(0,0,0,0.03); }
    /* Memperbaiki style pagination Laravel agar match dengan Bootstrap */
    .pagination { margin-bottom: 0; }
</style>
@endsection