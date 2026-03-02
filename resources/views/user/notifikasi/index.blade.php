@extends('layouts.main')

@section('page', 'Notifikasi Outlet')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4 align-items-center">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="mb-3 mb-md-0">
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i> Pantau stok kritis dan pengiriman barang untuk outlet Anda.
                </p>
            </div>
            
            @if($notifications->count() > 0)
                <form action="{{ route('user.notifikasi.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-4 shadow-sm font-weight-bold">
                        <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Notification List Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        
                        @forelse($notifications as $n)
                            @php
                                $type = $n->data['type'] ?? 'info';
                                $config = match($type) {
                                    'stok_kritis' => [
                                        'icon' => 'fa-exclamation-triangle', 
                                        'color' => 'warning', 
                                        'bg' => 'rgba(255, 193, 7, 0.15)', 
                                        'label' => 'PERINGATAN'
                                    ],
                                    'chat' => [
                                        'icon' => 'fa-envelope', 
                                        'color' => 'primary', 
                                        'bg' => 'rgba(0, 123, 255, 0.15)', 
                                        'label' => 'PESAN'
                                    ],
                                    'info' => [
                                        'icon' => 'fa-truck', 
                                        'color' => 'success', 
                                        'bg' => 'rgba(40, 167, 69, 0.15)', 
                                        'label' => 'PENGIRIMAN'
                                    ],
                                    default => [
                                        'icon' => 'fa-info-circle', 
                                        'color' => 'secondary', 
                                        'bg' => 'rgba(108, 117, 125, 0.15)', 
                                        'label' => 'SISTEM'
                                    ]
                                };
                                $isUnread = $n->read_at === null;
                            @endphp

                            <div class="list-group-item list-group-item-action py-3 px-4 border-bottom {{ $isUnread ? 'bg-unread' : 'bg-transparent' }}">
                                <div class="row align-items-center">
                                    {{-- Column 1: Icon Box --}}
                                    <div class="col-auto">
                                        <div class="icon-box d-flex align-items-center justify-content-center shadow-sm" 
                                             style="width: 50px; height: 50px; background: {{ $config['bg'] }}; border-radius: 12px;">
                                            <i class="fas {{ $config['icon'] }} text-{{ $config['color'] }} fs-5"></i>
                                        </div>
                                    </div>

                                    {{-- Column 2: Content Text --}}
                                    <div class="col px-md-3 mt-2 mt-md-0">
                                        <div class="d-flex align-items-center mb-1 flex-wrap">
                                            <span class="badge bg-{{ $config['color'] }} rounded-pill me-2 mb-1 mb-md-0" style="font-size: 0.65rem; letter-spacing: 0.3px;">
                                                {{ $config['label'] }}
                                            </span>
                                            <small class="text-muted me-2">
                                                <i class="far fa-clock me-1"></i>{{ $n->created_at->diffForHumans() }}
                                            </small>
                                            @if($isUnread)
                                                <span class="badge bg-danger rounded-circle p-1 animate-pulse" style="width: 8px; height: 8px;" title="Baru"> </span>
                                            @endif
                                        </div>
                                        
                                        <h6 class="mb-1 fw-bold {{ $isUnread ? 'text-primary-emphasis' : 'text-muted' }}">
                                            {{ $n->data['title'] }}
                                        </h6>
                                        
                                        <p class="mb-0 text-muted small text-truncate d-none d-md-block" style="max-width: 550px;">
                                            {{ $n->data['message'] }}
                                        </p>
                                        {{-- Mobile Message View --}}
                                        <p class="mb-0 text-muted small d-block d-md-none">
                                            {{ Str::limit($n->data['message'], 50) }}
                                        </p>
                                    </div>

                                    {{-- Column 3: Actions --}}
                                    <div class="col-auto d-flex gap-2 align-items-center mt-3 mt-md-0">
                                        <a href="{{ $n->data['url'] ?? '#' }}" class="btn btn-sm btn-action rounded-pill px-3 fw-bold shadow-sm">
                                            Lihat
                                        </a>
                                        
                                        <form action="{{ route('user.notifikasi.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-1 shadow-none hover-scale">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- Empty State --}}
                            <div class="text-center py-5 my-3">
                                <div class="mb-3">
                                    <i class="fas fa-bell-slash text-muted fa-3x opacity-25"></i>
                                </div>
                                <h6 class="fw-bold">Kotak Masuk Kosong</h6>
                                <p class="text-muted small">Semua notifikasi sudah dibaca atau dihapus.</p>
                            </div>
                        @endforelse

                    </div>
                </div>
                
                {{-- Pagination Section --}}
                @if($notifications->hasPages())
                    <div class="card-footer bg-transparent border-0 py-4 d-flex justify-content-center">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection