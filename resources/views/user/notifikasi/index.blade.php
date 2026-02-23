@extends('layouts.main')

@section('content')
<div class="container-fluid mt-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold text-dark"><i class="fas fa-bell text-primary me-2"></i>Notifikasi Outlet</h3>
                <p class="text-muted small">Pantau stok kritis dan pengiriman barang untuk outlet Anda.</p>
            </div>
            
            {{-- Tombol Tandai Semua Dibaca --}}
            @if($notifications->count() > 0)
            <form action="{{ route('user.notifikasi.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
                    <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        
                        @forelse($notifications as $n)
                            @php
                                $type = $n->data['type'] ?? 'info';
                                // Logika styling berdasarkan tipe notifikasi
                                $config = match($type) {
                                    'stok_kritis' => ['icon' => 'fa-exclamation-triangle', 'color' => 'warning', 'bg' => '#fffdf2', 'label' => 'PERINGATAN'],
                                    'chat' => ['icon' => 'fa-envelope', 'color' => 'primary', 'bg' => '#f0f7ff', 'label' => 'PESAN'],
                                    'info' => ['icon' => 'fa-truck', 'color' => 'success', 'bg' => '#f6fff9', 'label' => 'PENGIRIMAN'],
                                    default => ['icon' => 'fa-info-circle', 'color' => 'secondary', 'bg' => '#f8f9fa', 'label' => 'SISTEM']
                                };
                                // Cek apakah notifikasi sudah dibaca
                                $isUnread = $n->read_at === null;
                            @endphp

                            <div class="list-group-item list-group-item-action py-3 px-4 border-bottom border-light {{ $isUnread ? 'bg-light-blue' : '' }}">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="icon-box d-flex align-items-center justify-content-center shadow-sm" 
                                             style="width: 50px; height: 50px; background: {{ $config['bg'] }}; border-radius: 12px;">
                                            <i class="fas {{ $config['icon'] }} text-{{ $config['color'] }} fs-5"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="badge bg-{{ $config['color'] }} rounded-pill me-2" style="font-size: 0.65rem;">
                                                {{ $config['label'] }}
                                            </span>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>{{ $n->created_at->diffForHumans() }}
                                            </small>
                                            @if($isUnread)
                                                <span class="ms-2 badge bg-danger border border-light rounded-circle p-1"><span class="visually-hidden">Baru</span></span>
                                            @endif
                                        </div>
                                        <h6 class="mb-1 fw-bold {{ $isUnread ? 'text-dark' : 'text-muted' }}">{{ $n->data['title'] }}</h6>
                                        <p class="mb-0 text-muted small">{{ $n->data['message'] }}</p>
                                    </div>
                                    <div class="col-auto d-flex gap-2">
                                        {{-- Tombol Lihat Detail --}}
                                        <a href="{{ $n->data['url'] ?? '#' }}" class="btn btn-sm btn-white border rounded-pill px-3 fw-bold text-primary shadow-sm">
                                            Lihat
                                        </a>
                                        
                                        {{-- Tombol Hapus Notifikasi --}}
                                        <form action="{{ route('user.notifikasi.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-1 shadow-none">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-bell-slash text-muted fa-3x opacity-25"></i>
                                </div>
                                <h6 class="text-dark fw-bold">Kotak Masuk Kosong</h6>
                                <p class="text-muted small">Semua notifikasi sudah dibaca atau dihapus.</p>
                            </div>
                        @endforelse

                    </div>
                </div>
                
                @if($notifications->hasPages())
                    <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light-blue { background-color: #f8fbff; }
    .list-group-item-action:hover { background-color: #f4f8ff; transition: 0.2s; }
    .icon-box { border: 1px solid rgba(0,0,0,0.05); }
    .btn-white { background: white; }
    .btn-link:hover { transform: scale(1.1); }
</style>
@endsection